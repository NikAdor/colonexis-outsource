<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_index_is_available_even_when_module_disabled_in_settings(): void
    {
        SiteSetting::setBool('courses_enabled', false);

        Course::query()->create([
            'title' => 'Public',
            'slug' => 'public-course',
            'price' => 0,
            'is_published' => true,
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Courses/Index')
                ->has('courses', 1)
            );
    }

    public function test_courses_index_lists_only_published_courses(): void
    {
        Course::query()->create([
            'title' => 'Alpha',
            'slug' => 'alpha',
            'description' => 'Desc',
            'price' => 0,
            'is_published' => true,
        ]);

        Course::query()->create([
            'title' => 'Draft',
            'slug' => 'draft',
            'description' => null,
            'price' => 10,
            'is_published' => false,
        ]);

        $response = $this->get(route('courses.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Courses/Index')
            ->has('courses', 1)
            ->where('courses.0.slug', 'alpha')
        );
    }

    public function test_course_show_returns_404_when_unpublished(): void
    {
        $course = Course::query()->create([
            'title' => 'Hidden',
            'slug' => 'hidden',
            'price' => 0,
            'is_published' => false,
        ]);

        $this->get(route('courses.show', $course->slug))->assertNotFound();
    }

    public function test_course_show_renders_when_published(): void
    {
        Course::query()->create([
            'title' => 'Visible',
            'slug' => 'visible',
            'price' => 19.99,
            'is_published' => true,
        ]);

        $this->get(route('courses.show', 'visible'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Courses/Show')
                ->where('course.slug', 'visible')
                ->where('isEnrolled', false)
            );
    }
}
