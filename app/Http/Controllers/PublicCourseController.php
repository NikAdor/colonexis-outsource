<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PublicCourseController extends Controller
{
    public function index(): Response
    {
        $courses = Course::query()
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function show(Course $course): Response
    {
        abort_unless($course->is_published, 404);

        $course->load(['sections.lessons']);

        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = auth()->user()
                ->coursePurchases()
                ->where('course_id', $course->id)
                ->exists();
        }

        return Inertia::render('Courses/Show', [
            'course' => $this->transformCourseForPublic($course),
            'isEnrolled' => $isEnrolled,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformCourseForPublic(Course $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'subtitle' => $course->subtitle,
            'slug' => $course->slug,
            'excerpt' => $course->excerpt,
            'description' => $course->description,
            'learning_outcomes' => $course->learning_outcomes ?? [],
            'requirements' => $course->requirements ?? [],
            'level' => $course->level,
            'duration_minutes' => $course->duration_minutes,
            'language' => $course->language,
            'price' => $course->price,
            'featured_image_url' => $course->featured_image_url,
            'updated_at' => $course->updated_at?->toIso8601String(),
            'sections' => $course->sections->map(fn ($section) => [
                'id' => $section->id,
                'title' => $section->title,
                'quiz_enabled' => (bool) $section->quiz_enabled,
                'lessons' => $section->lessons->map(fn ($lesson) => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'duration_minutes' => $lesson->duration_minutes,
                    'content_type' => $lesson->content_type ?? 'text',
                ]),
            ]),
        ];
    }
}
