<script setup>
import StudentLearningLayout from '@/Layouts/StudentLearningLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    course: { type: Object, required: true },
    activeLessonId: { type: Number, default: null },
    progressPct: { type: Number, default: 0 },
});

const flatLessons = computed(() => {
    const sections = props.course.sections ?? [];
    return sections.flatMap((s) => (s.lessons ?? []).map((l) => ({ ...l, sectionId: s.id, sectionTitle: s.title })));
});

const activeLesson = computed(() => flatLessons.value.find((l) => Number(l.id) === Number(props.activeLessonId)) ?? flatLessons.value[0] ?? null);

const activeLessonNumber = computed(() => {
    if (!activeLesson.value) return 0;
    const idx = flatLessons.value.findIndex((l) => Number(l.id) === Number(activeLesson.value.id));
    return idx >= 0 ? idx + 1 : 1;
});

const totalLessons = computed(() => flatLessons.value.length || 0);

const contentTab = 'overview';

function lessonIcon(type) {
    const t = String(type || 'text');
    if (t === 'video') return 'play_circle';
    if (t === 'pdf') return 'article';
    if (t === 'image') return 'image';
    return 'menu_book';
}
</script>

<template>
    <Head :title="`${course.title} — Learning`" />

    <StudentLearningLayout :course-title="course.title" :progress-pct="progressPct">
        <template #sidebar>
            <div class="learning-sidebar">
                <div class="learning-sidebar__head">
                    <h2 class="learning-sidebar__title">Mastering Architecture</h2>
                    <p class="learning-sidebar__subtitle">Progress: {{ progressPct }}%</p>
                </div>

                <nav class="learning-sidebar__nav">
                    <Link :href="route('courses.learn', { course: course.slug })" class="learning-sidebar__link">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                        <span>Course Overview</span>
                    </Link>
                    <a href="#curriculum" class="learning-sidebar__link is-active">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                        <span>Curriculum</span>
                    </a>
                    <a href="#notes" class="learning-sidebar__link">
                        <span class="material-symbols-outlined text-[20px]">sticky_note_2</span>
                        <span>Notes</span>
                    </a>
                    <a href="#resources" class="learning-sidebar__link">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        <span>Resources</span>
                    </a>
                    <a href="#qa" class="learning-sidebar__link">
                        <span class="material-symbols-outlined text-[20px]">forum</span>
                        <span>Q&amp;A</span>
                    </a>
                </nav>

                <div class="learning-sidebar__current">
                    <div class="learning-sidebar__current-card">
                        <div class="learning-sidebar__current-dot" aria-hidden="true" />
                        <span class="learning-sidebar__current-label">Lesson</span>
                        <p class="learning-sidebar__current-title">
                            {{ activeLesson?.title || '—' }}
                        </p>
                    </div>
                </div>

                <Link :href="route('courses.learn', { course: course.slug, lesson: activeLesson?.id })" class="learning-sidebar__cta">
                    Continue learning
                </Link>
            </div>
        </template>

        <div class="learning-page">
            <div class="learning-page__inner">
                <section class="learning-player">
                    <div class="learning-player__frame">
                        <template v-if="activeLesson?.content_type === 'video' && activeLesson?.video_url">
                            <iframe
                                class="learning-player__iframe"
                                :src="activeLesson.video_url"
                                title="Lesson video"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            />
                        </template>
                        <template v-else-if="activeLesson?.content_type === 'image' && activeLesson?.image_url">
                            <img :src="activeLesson.image_url" alt="" class="learning-player__img" />
                        </template>
                        <template v-else-if="activeLesson?.content_type === 'pdf' && activeLesson?.pdf_url">
                            <iframe class="learning-player__iframe" :src="activeLesson.pdf_url" title="Lesson PDF" />
                        </template>
                        <template v-else>
                            <div class="learning-player__placeholder">
                                <span class="material-symbols-outlined text-white/90 text-5xl">menu_book</span>
                                <p class="text-white/70 text-sm">Reading lesson</p>
                            </div>
                        </template>
                    </div>
                </section>

                <div class="learning-lesson-head">
                    <div>
                        <span class="learning-lesson-head__eyebrow">
                            Lecture {{ String(activeLessonNumber).padStart(2, '0') }}
                        </span>
                        <h1 class="learning-lesson-head__title">
                            {{ activeLesson?.title || course.title }}
                        </h1>
                        <p class="learning-lesson-head__meta">
                            {{ activeLessonNumber }} of {{ totalLessons }}
                        </p>
                    </div>
                    <div class="learning-lesson-head__actions">
                        <Link
                            v-if="activeLessonNumber > 1"
                            :href="route('courses.learn', { course: course.slug, lesson: flatLessons[activeLessonNumber - 2]?.id })"
                            class="learning-lesson-head__btn is-ghost"
                        >
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            Previous
                        </Link>
                        <Link
                            v-if="activeLessonNumber < totalLessons"
                            :href="route('courses.learn', { course: course.slug, lesson: flatLessons[activeLessonNumber]?.id })"
                            class="learning-lesson-head__btn"
                        >
                            Next Lesson
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </Link>
                    </div>
                </div>

                <section class="learning-tabs">
                    <div class="learning-tabs__bar">
                        <button class="learning-tabs__tab is-active" type="button">Overview</button>
                        <button class="learning-tabs__tab" type="button">Resources</button>
                        <button class="learning-tabs__tab" type="button">Notes</button>
                        <button class="learning-tabs__tab" type="button">Q&amp;A</button>
                    </div>

                    <div class="learning-tabs__content">
                        <div class="learning-tabs__grid">
                            <div class="learning-tabs__main">
                                <div class="learning-prose">
                                    <p v-if="activeLesson?.body" class="text-lg">
                                        {{ activeLesson.body }}
                                    </p>
                                    <p v-else>
                                        This lesson content will appear here. Add lesson body/video in the course editor.
                                    </p>
                                </div>
                            </div>
                            <aside class="learning-tabs__side" id="curriculum">
                                <div class="learning-modules">
                                    <h4 class="learning-modules__title">Curriculum modules</h4>
                                    <div class="learning-modules__list">
                                        <template v-for="section in course.sections" :key="section.id">
                                            <div class="learning-modules__section">{{ section.title }}</div>
                                            <Link
                                                v-for="lesson in section.lessons"
                                                :key="lesson.id"
                                                :href="route('courses.learn', { course: course.slug, lesson: lesson.id })"
                                                class="learning-modules__row"
                                                :class="{ 'is-active': Number(lesson.id) === Number(activeLessonId) }"
                                            >
                                                <span class="learning-modules__num">{{ String(lesson.id).slice(-2) }}</span>
                                                <span class="learning-modules__name">{{ lesson.title }}</span>
                                                <span class="material-symbols-outlined learning-modules__icon">
                                                    {{ Number(lesson.id) === Number(activeLessonId) ? 'play_circle' : lessonIcon(lesson.content_type) }}
                                                </span>
                                            </Link>
                                        </template>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </StudentLearningLayout>
</template>

