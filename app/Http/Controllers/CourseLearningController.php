<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CoursePurchase;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseLearningController extends Controller
{
    public function show(Request $request, Course $course, ?CourseLesson $lesson = null): Response
    {
        if (! SiteSetting::bool('courses_enabled')) {
            abort(404);
        }

        abort_unless($course->is_published, 404);

        $user = $request->user();
        abort_unless($user, 403);

        $isEnrolled = $user->coursePurchases()
            ->where('course_id', $course->id)
            ->where('status', CoursePurchase::STATUS_PAID)
            ->exists();
        abort_unless($isEnrolled, 403);

        $course->load(['sections.lessons']);

        $lessonsFlat = $course->sections
            ->flatMap(fn ($s) => $s->lessons)
            ->values();

        $activeLesson = null;
        if ($lesson && $lesson->exists) {
            $activeLesson = $lessonsFlat->firstWhere('id', $lesson->id);
        }
        if (! $activeLesson) {
            $activeLesson = $lessonsFlat->first();
        }

        $totalLessons = max(1, $lessonsFlat->count());
        $activeIndex = $activeLesson ? (int) $lessonsFlat->search(fn ($l) => $l->id === $activeLesson->id) : 0;
        $activeIndex = $activeIndex < 0 ? 0 : $activeIndex;
        $progressPct = (int) round((($activeIndex + 1) / $totalLessons) * 100);

        return Inertia::render('Courses/Learn', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'featured_image_url' => $course->featured_image_url,
                'sections' => $course->sections->map(fn ($section) => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'quiz_enabled' => (bool) $section->quiz_enabled,
                    'lessons' => $section->lessons->map(fn ($l) => [
                        'id' => $l->id,
                        'title' => $l->title,
                        'duration_minutes' => $l->duration_minutes,
                        'content_type' => $l->content_type ?? 'text',
                        'video_url' => $l->video_url,
                        'image_url' => $l->image_url,
                        'pdf_url' => $l->pdf_url,
                        'body' => $l->body,
                    ]),
                ]),
            ],
            'activeLessonId' => $activeLesson?->id,
            'progressPct' => $progressPct,
        ]);
    }
}
