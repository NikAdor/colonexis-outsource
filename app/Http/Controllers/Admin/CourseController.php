<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Course::query()
            ->withCount(['sections', 'lessons', 'purchases' => fn ($q) => $q->where('status', CoursePurchase::STATUS_PAID)])
            ->withSum(['purchases as purchases_sum_amount_paid' => fn ($q) => $q->where('status', CoursePurchase::STATUS_PAID)], 'amount_paid');

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        $level = $request->input('level', 'all');
        if (is_string($level) && $level !== '' && $level !== 'all') {
            $query->where('level', $level);
        }

        $status = $request->input('status', 'all');
        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft' || $status === 'under_review') {
            $query->where('is_published', false);
        }

        $courses = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'live_courses' => Course::query()->where('is_published', true)->count(),
            'draft_courses' => Course::query()->where('is_published', false)->count(),
            'total_enrollments' => CoursePurchase::query()->where('status', CoursePurchase::STATUS_PAID)->count(),
            'total_revenue' => (float) CoursePurchase::query()->where('status', CoursePurchase::STATUS_PAID)->sum('amount_paid'),
            'total_clients' => User::query()->where('role', User::ROLE_CLIENT)->count(),
        ];

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'level' => is_string($level) && $level !== '' ? $level : 'all',
                'status' => is_string($status) && $status !== '' ? $status : 'all',
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Courses/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeCourseNumericFields($request);
        $data = $this->validatedCourseData($request);
        $this->validateLessonUploads($request);
        $slug = $this->resolveSlug($request, null);

        $payload = array_merge($data, [
            'slug' => $slug,
            'is_published' => $request->boolean('is_published'),
            'learning_outcomes' => $this->filterStringList($request->input('learning_outcomes')),
            'requirements' => $this->filterStringList($request->input('requirements')),
        ]);

        if ($request->hasFile('featured_image')) {
            $payload['featured_image'] = $request->file('featured_image')->store('courses', 'public');
        }

        unset($payload['sections'], $payload['remove_featured_image']);

        $course = Course::create($payload);

        $this->syncCurriculum($course, $request->input('sections', []), $request);

        return redirect()->route('admin.courses.index')->with('success', 'Course created.');
    }

    public function edit(Course $course): Response
    {
        $course->load(['sections.lessons']);

        return Inertia::render('Admin/Courses/Edit', [
            'course' => $this->prepareCourseForForm($course),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->normalizeCourseNumericFields($request);
        $data = $this->validatedCourseData($request);
        $this->validateLessonUploads($request);
        $slug = $this->resolveSlug($request, $course);

        $payload = array_merge($data, [
            'slug' => $slug,
            'is_published' => $request->boolean('is_published'),
            'learning_outcomes' => $this->filterStringList($request->input('learning_outcomes')),
            'requirements' => $this->filterStringList($request->input('requirements')),
        ]);

        if ($request->hasFile('featured_image')) {
            if ($course->featured_image) {
                Storage::disk('public')->delete($course->featured_image);
            }
            $payload['featured_image'] = $request->file('featured_image')->store('courses', 'public');
        } elseif ($request->boolean('remove_featured_image') && $course->featured_image) {
            Storage::disk('public')->delete($course->featured_image);
            $payload['featured_image'] = null;
        }

        unset($payload['sections'], $payload['remove_featured_image']);

        $course->update($payload);

        $this->syncCurriculum($course, $request->input('sections', []), $request);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->featured_image) {
            Storage::disk('public')->delete($course->featured_image);
        }

        $course->load('sections.lessons');
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->image_path) {
                    Storage::disk('public')->delete($lesson->image_path);
                }
                if ($lesson->pdf_path) {
                    Storage::disk('public')->delete($lesson->pdf_path);
                }
            }
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    private function normalizeCourseNumericFields(Request $request): void
    {
        $request->merge([
            'duration_minutes' => $request->filled('duration_minutes') && $request->input('duration_minutes') !== ''
                ? (int) $request->input('duration_minutes')
                : null,
        ]);
    }

    private function prepareCourseForForm(Course $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'subtitle' => $course->subtitle,
            'slug' => $course->slug,
            'excerpt' => $course->excerpt,
            'description' => $course->description ?? '',
            'learning_outcomes' => $course->learning_outcomes ?? [],
            'requirements' => $course->requirements ?? [],
            'level' => $course->level ?? 'beginner',
            'duration_minutes' => $course->duration_minutes,
            'language' => $course->language ?? 'English',
            'price' => (string) $course->price,
            'is_published' => $course->is_published,
            'featured_image_url' => $course->featured_image_url,
            'sections' => $course->sections->map(fn ($s) => [
                'title' => $s->title,
                'quiz_enabled' => (bool) $s->quiz_enabled,
                'quiz_title' => $s->quiz_title ?? '',
                'quiz_questions' => $s->quiz_questions ?? [],
                'lessons' => $s->lessons->map(fn ($l) => [
                    'title' => $l->title,
                    'duration_minutes' => $l->duration_minutes,
                    'content_type' => $l->content_type ?? 'text',
                    'video_url' => $l->video_url ?? '',
                    'image_path' => $l->image_path,
                    'pdf_path' => $l->pdf_path,
                    'image_url' => $l->image_url,
                    'pdf_url' => $l->pdf_url,
                    'body' => $l->body ?? '',
                ])->values()->all(),
            ])->values()->all(),
        ];
    }

    private function validatedCourseData(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'slug' => ['nullable', 'string', 'max:255'],
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
            'level' => 'nullable|in:beginner,intermediate,advanced,all_levels',
            'duration_minutes' => 'nullable|integer|min:0',
            'language' => 'nullable|string|max:100',
            'learning_outcomes' => 'nullable|array',
            'learning_outcomes.*' => 'nullable|string|max:200',
            'requirements' => 'nullable|array',
            'requirements.*' => 'nullable|string|max:200',
            'sections' => 'nullable|array',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.quiz_enabled' => 'boolean',
            'sections.*.quiz_title' => 'nullable|string|max:255',
            'sections.*.quiz_questions' => 'nullable|array',
            'sections.*.quiz_questions.*.question' => 'nullable|string|max:500',
            'sections.*.quiz_questions.*.options' => 'nullable|array',
            'sections.*.quiz_questions.*.options.*' => 'nullable|string|max:200',
            'sections.*.quiz_questions.*.correct_option_index' => 'nullable|integer|min:0',
            'sections.*.lessons' => 'nullable|array',
            'sections.*.lessons.*.title' => 'nullable|string|max:255',
            'sections.*.lessons.*.duration_minutes' => 'nullable|integer|min:0',
            'sections.*.lessons.*.content_type' => 'nullable|in:video,image,pdf,text',
            'sections.*.lessons.*.video_url' => 'nullable|string|max:2000',
            'sections.*.lessons.*.body' => 'nullable|string',
            'sections.*.lessons.*.image_path' => 'nullable|string|max:255',
            'sections.*.lessons.*.pdf_path' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:5120',
            'remove_featured_image' => 'boolean',
        ]);

        unset($validated['featured_image'], $validated['remove_featured_image']);

        return $validated;
    }

    private function validateLessonUploads(Request $request): void
    {
        $rules = [];
        foreach ($request->allFiles() as $key => $file) {
            $key = (string) $key;
            if (preg_match('/^lesson_image_\d+_\d+$/', $key)) {
                $rules[$key] = ['file', 'image', 'max:5120'];
            }
            if (preg_match('/^lesson_pdf_\d+_\d+$/', $key)) {
                $rules[$key] = ['file', 'mimes:pdf', 'max:15360'];
            }
        }
        if (count($rules) > 0) {
            $request->validate($rules);
        }
    }

    private function resolveSlug(Request $request, ?Course $course): string
    {
        $data = $request->only(['title', 'slug']);
        $slugInput = $data['slug'] ?? null;
        $slug = $slugInput ? Str::slug($slugInput) : Str::slug($data['title']);

        if ($slug === '') {
            $slug = 'course';
        }

        if ($course && $slug === $course->slug) {
            return $course->slug;
        }

        return $this->uniqueSlug($slug, $course?->id);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $n = 1;
        while (Course::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }

    private function filterStringList(?array $list): ?array
    {
        if ($list === null) {
            return null;
        }

        $out = array_values(array_filter(array_map('trim', $list), fn ($s) => $s !== ''));

        return count($out) ? $out : null;
    }

    private function deleteUnusedLessonMedia(Course $course, array $incomingSections): void
    {
        $retain = [];
        foreach ($incomingSections as $sectionData) {
            foreach ($sectionData['lessons'] ?? [] as $lessonData) {
                $type = $lessonData['content_type'] ?? 'text';
                if ($type === 'image' && ! empty($lessonData['image_path'])) {
                    $retain[] = $lessonData['image_path'];
                }
                if ($type === 'pdf' && ! empty($lessonData['pdf_path'])) {
                    $retain[] = $lessonData['pdf_path'];
                }
            }
        }

        $course->load('sections.lessons');
        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->image_path && ! in_array($lesson->image_path, $retain, true)) {
                    Storage::disk('public')->delete($lesson->image_path);
                }
                if ($lesson->pdf_path && ! in_array($lesson->pdf_path, $retain, true)) {
                    Storage::disk('public')->delete($lesson->pdf_path);
                }
            }
        }
    }

    private function normalizeQuizQuestions(array $raw, bool $enabled): ?array
    {
        if (! $enabled) {
            return null;
        }

        $out = [];
        foreach ($raw as $q) {
            $question = trim($q['question'] ?? '');
            $options = array_values(array_filter(array_map('trim', $q['options'] ?? []), fn ($o) => $o !== ''));
            if ($question === '' || count($options) < 2) {
                continue;
            }
            $idx = (int) ($q['correct_option_index'] ?? 0);
            if ($idx < 0 || $idx >= count($options)) {
                $idx = 0;
            }
            $out[] = [
                'question' => $question,
                'options' => $options,
                'correct_option_index' => $idx,
            ];
        }

        return count($out) ? $out : null;
    }

    private function syncCurriculum(Course $course, array $sections, Request $request): void
    {
        $this->deleteUnusedLessonMedia($course, $sections);
        $course->sections()->delete();

        foreach ($sections as $si => $sectionData) {
            if (empty(trim($sectionData['title'] ?? ''))) {
                continue;
            }

            $quizEnabled = filter_var($sectionData['quiz_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $section = $course->sections()->create([
                'title' => $sectionData['title'],
                'sort_order' => $si,
                'quiz_enabled' => $quizEnabled,
                'quiz_title' => ! empty(trim($sectionData['quiz_title'] ?? '')) ? trim($sectionData['quiz_title']) : null,
                'quiz_questions' => $this->normalizeQuizQuestions($sectionData['quiz_questions'] ?? [], $quizEnabled),
            ]);

            $lessons = $sectionData['lessons'] ?? [];
            foreach ($lessons as $li => $lessonData) {
                if (empty(trim($lessonData['title'] ?? ''))) {
                    continue;
                }

                $type = $lessonData['content_type'] ?? 'text';
                if (! in_array($type, ['video', 'image', 'pdf', 'text'], true)) {
                    $type = 'text';
                }

                $row = [
                    'title' => $lessonData['title'],
                    'duration_minutes' => $lessonData['duration_minutes'] ?? null,
                    'sort_order' => $li,
                    'content_type' => $type,
                    'video_url' => null,
                    'image_path' => null,
                    'pdf_path' => null,
                    'body' => null,
                ];

                if ($type === 'video') {
                    $url = trim($lessonData['video_url'] ?? '');
                    $row['video_url'] = $url !== '' ? $url : null;
                }

                if ($type === 'text') {
                    $body = $lessonData['body'] ?? null;
                    $row['body'] = is_string($body) && trim($body) !== '' ? $body : null;
                }

                if ($type === 'image') {
                    $imgKey = "lesson_image_{$si}_{$li}";
                    if ($request->hasFile($imgKey)) {
                        $row['image_path'] = $request->file($imgKey)->store('course_lessons', 'public');
                    } else {
                        $row['image_path'] = $lessonData['image_path'] ?? null;
                    }
                }

                if ($type === 'pdf') {
                    $pdfKey = "lesson_pdf_{$si}_{$li}";
                    if ($request->hasFile($pdfKey)) {
                        $row['pdf_path'] = $request->file($pdfKey)->store('course_lessons', 'public');
                    } else {
                        $row['pdf_path'] = $lessonData['pdf_path'] ?? null;
                    }
                }

                $section->lessons()->create($row);
            }
        }
    }
}
