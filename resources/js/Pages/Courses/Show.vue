<script setup>
import { computed, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MarketingLayout from '@/Components/MarketingLayout.vue';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    isEnrolled: { type: Boolean, default: false },
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});

const page = usePage();
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const openSectionId = ref(props.course.sections?.[0]?.id ?? null);

const canEnroll = computed(() => {
    const u = page.props.auth?.user;
    return Boolean(u?.email_verified_at);
});

const curriculumStats = computed(() => {
    const sections = props.course.sections ?? [];
    let lectures = 0;
    let minutes = 0;
    for (const s of sections) {
        const lessons = s.lessons ?? [];
        lectures += lessons.length;
        for (const l of lessons) {
            minutes += Number(l.duration_minutes) || 0;
        }
    }
    return {
        sectionCount: sections.length,
        lectures,
        totalMinutes: minutes,
    };
});

const curriculumSummaryLine = computed(() => {
    const { sectionCount, lectures, totalMinutes } = curriculumStats.value;
    if (!sectionCount && !lectures) {
        return '';
    }
    const len = formatTotalDuration(totalMinutes);
    const parts = [];
    if (sectionCount) {
        parts.push(`${sectionCount} section${sectionCount === 1 ? '' : 's'}`);
    }
    if (lectures) {
        parts.push(`${lectures} lecture${lectures === 1 ? '' : 's'}`);
    }
    if (len) {
        parts.push(`${len} total length`);
    }
    return parts.join(' • ');
});

const lastUpdatedLabel = computed(() => {
    const raw = props.course.updated_at;
    if (!raw) return null;
    const d = new Date(raw);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString(undefined, { month: 'numeric', year: 'numeric' });
});

const pathBadgeLabel = computed(() => {
    const level = String(props.course.level || '').toLowerCase();
    if (level === 'advanced') return 'Engineering Path';
    if (level === 'intermediate') return 'Professional Path';
    if (level === 'beginner') return 'Foundations Path';
    if (props.course.level) {
        return `${String(props.course.level).replace(/_/g, ' ')} Path`;
    }
    return 'Curriculum';
});

const heroLead = computed(() => {
    if (props.course.subtitle) return props.course.subtitle;
    if (props.course.excerpt) return props.course.excerpt;
    return '';
});

const instructorInitials = computed(() => {
    const words = String(appName).trim().split(/\s+/).filter(Boolean);
    if (!words.length) return '?';
    if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
    return (words[0][0] + words[1][0]).toUpperCase();
});

function formatCoursePrice(price) {
    const n = Number(price);
    if (n <= 0) return 'Free';
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(n);
}

function formatTotalDuration(minutes) {
    const m = Number(minutes) || 0;
    if (m <= 0) return '';
    const h = Math.floor(m / 60);
    const r = m % 60;
    if (h === 0) return `${r} min`;
    if (r === 0) return `${h}h`;
    return `${h}h ${r}m`;
}

function contentTypeLabel(type) {
    const t = String(type || 'text');
    if (t === 'video') return 'Video';
    if (t === 'image') return 'Image';
    if (t === 'pdf') return 'PDF';
    return 'Lesson';
}

function sectionMeta(section) {
    const lessons = section.lessons ?? [];
    const n = lessons.length;
    const mins = lessons.reduce((acc, l) => acc + (Number(l.duration_minutes) || 0), 0);
    return {
        count: n,
        duration: formatTotalDuration(mins),
    };
}

function formatLessonDuration(minutes) {
    const m = Number(minutes);
    if (!m || m <= 0) return '—';
    if (m < 60) return `${m} min`;
    const h = Math.floor(m / 60);
    const r = m % 60;
    return r ? `${h}h ${r}m` : `${h}h`;
}

function purchaseActionLabel(price) {
    if (Number(price) <= 0) {
        return 'Enroll free';
    }
    const method = String(page.props?.site?.payment_method ?? 'xendit').toLowerCase();
    return method === 'paymongo' ? 'Pay with PayMongo' : 'Pay with Xendit';
}

function toggleSection(id) {
    openSectionId.value = openSectionId.value === id ? null : id;
}

function lessonIcon(type) {
    const t = String(type || 'text');
    if (t === 'video') return 'play_circle';
    if (t === 'pdf') return 'article';
    if (t === 'image') return 'image';
    return 'menu_book';
}

async function shareCourse() {
    const url = typeof window !== 'undefined' ? window.location.href : '';
    if (!url) return;
    try {
        if (navigator.share) {
            await navigator.share({ title: props.course.title, url });
        } else {
            await navigator.clipboard.writeText(url);
        }
    } catch {
        /* dismissed or unsupported */
    }
}
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head :title="course.title" />

        <div
            class="min-h-screen bg-ed-surface font-inter text-ed-on-surface leading-relaxed selection:bg-ed-secondary-container"
        >
            <!-- Hero -->
            <header
                class="course-show-editorial-gradient relative overflow-hidden py-16 lg:py-24 [&_.material-symbols-outlined]:text-[24px] [&_.material-symbols-outlined]:leading-none"
            >
                <div class="pointer-events-none absolute inset-0 opacity-10">
                    <div
                        class="absolute -left-24 -top-24 h-96 w-96 rounded-full bg-ed-on-tertiary-container blur-[100px]"
                    />
                    <div
                        class="absolute -right-24 top-1/2 h-64 w-64 rounded-full bg-ed-secondary-container blur-[80px]"
                    />
                </div>
                <div class="relative z-10 mx-auto grid max-w-screen-2xl grid-cols-1 gap-12 px-8 lg:grid-cols-12">
                    <div class="lg:col-span-8">
                        <nav class="mb-6 text-sm text-white/70">
                            <Link :href="route('courses.index')" class="transition-colors hover:text-white">
                                Courses
                            </Link>
                            <span class="mx-2 opacity-60" aria-hidden="true">/</span>
                            <span class="text-white/90">{{ course.title }}</span>
                        </nav>

                        <div class="mb-6 flex flex-wrap gap-2">
                            <span
                                class="rounded-full bg-ed-tertiary-container px-3 py-1 font-inter text-xs font-bold uppercase tracking-widest text-ed-on-tertiary-container"
                            >
                                {{ pathBadgeLabel }}
                            </span>
                            <span
                                v-if="course.language"
                                class="rounded-full bg-white/10 px-3 py-1 font-inter text-xs font-bold uppercase tracking-widest text-white/90"
                            >
                                {{ course.language }}
                            </span>
                        </div>

                        <h1
                            class="mb-6 font-manrope text-4xl font-extrabold leading-tight tracking-tight text-white lg:text-6xl"
                        >
                            {{ course.title }}
                        </h1>

                        <p
                            v-if="heroLead"
                            class="mb-8 max-w-2xl text-xl font-light text-ed-on-primary-container"
                        >
                            {{ heroLead }}
                        </p>

                        <div class="flex flex-wrap items-center gap-6 text-sm text-white">
                            <div
                                v-if="curriculumStats.totalMinutes"
                                class="flex items-center gap-2 text-ed-on-primary-container"
                            >
                                <span class="material-symbols-outlined text-base">schedule</span>
                                <span>{{ formatTotalDuration(curriculumStats.totalMinutes) }} content</span>
                            </div>
                            <div
                                v-if="lastUpdatedLabel"
                                class="flex items-center gap-2 text-ed-on-primary-container"
                            >
                                <span class="material-symbols-outlined text-base">update</span>
                                <span>Last updated {{ lastUpdatedLabel }}</span>
                            </div>
                            <div v-if="curriculumStats.lectures" class="font-medium text-white">
                                {{ curriculumStats.lectures }} lesson{{ curriculumStats.lectures === 1 ? '' : 's' }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Grid -->
            <div
                id="main-content"
                class="mx-auto grid max-w-screen-2xl grid-cols-1 items-start gap-12 px-8 py-12 lg:grid-cols-12"
            >
                <div v-if="page.props.flash?.success" class="lg:col-span-12">
                    <div
                        class="rounded-[0.25rem] border border-ed-outline-variant/30 bg-ed-surface-container-low px-4 py-3 text-sm text-ed-on-surface"
                    >
                        {{ page.props.flash.success }}
                    </div>
                </div>
                <div v-if="page.props.flash?.info" class="lg:col-span-12">
                    <div
                        class="rounded-[0.25rem] border border-ed-outline-variant/30 bg-ed-surface-container px-4 py-3 text-sm text-ed-on-surface-variant"
                    >
                        {{ page.props.flash.info }}
                    </div>
                </div>

                <!-- Left column -->
                <div class="space-y-16 lg:col-span-8">
                    <section
                        v-if="course.learning_outcomes?.length"
                        class="rounded-[0.25rem] bg-ed-surface-container-low p-8"
                    >
                        <h2 class="mb-8 font-manrope text-2xl font-bold text-ed-on-surface">What you'll learn</h2>
                        <div class="grid gap-x-12 gap-y-4 md:grid-cols-2">
                            <div
                                v-for="(item, i) in course.learning_outcomes"
                                :key="i"
                                class="flex items-start gap-3"
                            >
                                <span class="material-symbols-outlined mt-0.5 text-ed-on-tertiary-container">
                                    check_circle
                                </span>
                                <span class="text-sm font-medium text-ed-on-surface-variant">{{ item }}</span>
                            </div>
                        </div>
                    </section>

                    <section v-if="course.sections?.length" id="course-content">
                        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                            <h2 class="font-manrope text-3xl font-bold text-ed-on-surface">Course content</h2>
                            <span
                                v-if="curriculumSummaryLine"
                                class="text-sm font-medium text-ed-on-surface-variant"
                            >
                                {{ curriculumSummaryLine }}
                            </span>
                        </div>
                        <div class="space-y-4">
                            <div
                                v-for="section in course.sections"
                                :key="section.id"
                                :class="[
                                    openSectionId === section.id
                                        ? 'bg-ed-surface-container-high'
                                        : 'border-l-4 border-ed-primary bg-ed-surface-container-lowest',
                                ]"
                            >
                                <button
                                    type="button"
                                    class="flex w-full cursor-pointer items-center justify-between p-6 text-left transition-colors hover:bg-black/[0.02]"
                                    :class="openSectionId === section.id ? 'border-b border-ed-outline-variant/10' : ''"
                                    @click="toggleSection(section.id)"
                                >
                                    <div class="flex items-center gap-4">
                                        <span class="material-symbols-outlined text-ed-on-surface-variant">
                                            {{ openSectionId === section.id ? 'expand_less' : 'expand_more' }}
                                        </span>
                                        <h3 class="font-manrope text-lg font-bold text-ed-on-surface">
                                            {{ section.title }}
                                        </h3>
                                        <span
                                            v-if="section.quiz_enabled"
                                            class="rounded-full bg-ed-tertiary-fixed px-2 py-0.5 font-inter text-xs font-semibold text-ed-on-tertiary-fixed"
                                        >
                                            Quiz
                                        </span>
                                    </div>
                                    <span class="font-inter text-xs text-ed-on-surface-variant">
                                        {{ sectionMeta(section).count }} lecture{{
                                            sectionMeta(section).count === 1 ? '' : 's'
                                        }}
                                        <template v-if="sectionMeta(section).duration">
                                            • {{ sectionMeta(section).duration }}
                                        </template>
                                    </span>
                                </button>
                                <div
                                    v-if="openSectionId === section.id && section.lessons?.length"
                                    class="space-y-4 bg-white/50 p-6"
                                >
                                    <div
                                        v-for="lesson in section.lessons"
                                        :key="lesson.id"
                                        class="flex items-center justify-between gap-4 text-sm"
                                    >
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="material-symbols-outlined shrink-0 text-ed-on-surface-variant">
                                                {{ lessonIcon(lesson.content_type) }}
                                            </span>
                                            <span class="truncate font-medium text-ed-secondary">
                                                {{ lesson.title }}
                                            </span>
                                        </div>
                                        <span class="shrink-0 text-ed-on-surface-variant">
                                            {{ formatLessonDuration(lesson.duration_minutes) }}
                                        </span>
                                    </div>
                                </div>
                                <div
                                    v-else-if="openSectionId === section.id"
                                    class="bg-white/50 p-6 text-sm text-ed-on-surface-variant"
                                >
                                    No lessons in this section yet.
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-12">
                        <div v-if="course.requirements?.length">
                            <h2 class="mb-6 font-manrope text-2xl font-bold text-ed-on-surface">Requirements</h2>
                            <ul class="list-disc space-y-3 pl-5 font-medium text-ed-on-surface-variant">
                                <li v-for="(item, i) in course.requirements" :key="i">
                                    {{ item }}
                                </li>
                            </ul>
                        </div>
                        <div v-if="course.description">
                            <h2 class="mb-6 font-manrope text-2xl font-bold text-ed-on-surface">Description</h2>
                            <div class="course-show-prose max-w-none space-y-6 text-ed-on-surface-variant" v-html="course.description" />
                        </div>
                    </section>

                    <section class="rounded-[0.25rem] bg-ed-surface-container-low p-10">
                        <h2 class="mb-8 font-manrope text-2xl font-bold text-ed-on-surface">Your instructor</h2>
                        <div class="flex flex-col gap-8 md:flex-row">
                            <div
                                class="flex h-32 w-32 shrink-0 items-center justify-center rounded-[0.25rem] bg-ed-primary-container font-manrope text-2xl font-bold text-white"
                                aria-hidden="true"
                            >
                                {{ instructorInitials }}
                            </div>
                            <div>
                                <h3 class="mb-1 font-manrope text-xl font-extrabold text-ed-on-surface">
                                    {{ appName }}
                                </h3>
                                <p
                                    class="mb-4 font-inter text-sm font-bold uppercase tracking-tighter text-ed-on-tertiary-container"
                                >
                                    Instructor team
                                </p>
                                <p class="max-w-xl text-sm leading-relaxed text-ed-on-surface-variant">
                                    This course is produced and supported by the {{ appName }} instructor collective.
                                    Enrolled learners get structured curriculum, updates, and access through your
                                    dashboard.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="mb-8 font-manrope text-2xl font-bold text-ed-on-surface">Student reviews</h2>
                        <p class="text-sm text-ed-on-surface-variant">
                            Reviews will appear here as learners complete the course.
                        </p>
                    </section>
                </div>

                <!-- Sticky card -->
                <aside class="lg:col-span-4 lg:sticky lg:top-24">
                    <div
                        class="flex flex-col overflow-hidden rounded-[0.25rem] bg-ed-surface-container-lowest shadow-2xl"
                    >
                        <div class="group relative">
                            <div class="h-48 w-full overflow-hidden bg-ed-primary-container">
                                <img
                                    v-if="course.featured_image_url"
                                    :src="course.featured_image_url"
                                    alt=""
                                    class="h-full w-full object-cover"
                                >
                            </div>
                            <div
                                class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/40"
                            >
                                <span
                                    class="material-symbols-outlined text-6xl text-white opacity-90"
                                    aria-hidden="true"
                                >
                                    play_circle
                                </span>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="mb-6 flex flex-wrap items-center gap-4">
                                <span class="font-manrope text-3xl font-extrabold text-ed-on-surface">
                                    {{ formatCoursePrice(course.price) }}
                                </span>
                            </div>

                            <div v-if="isEnrolled" class="mb-8 space-y-3">
                                <p class="text-sm font-medium text-ed-on-surface">You are enrolled.</p>
                                <Link
                                    :href="route('dashboard')"
                                    class="course-show-editorial-gradient flex w-full items-center justify-center rounded-[0.25rem] py-4 text-center text-sm font-bold text-white transition-transform active:scale-[0.98]"
                                >
                                    Open dashboard
                                </Link>
                            </div>
                            <div v-else-if="canEnroll" class="mb-8 space-y-3">
                                <Link
                                    :href="route('courses.purchase', course.id)"
                                    method="post"
                                    as="button"
                                    class="course-show-editorial-gradient flex w-full items-center justify-center rounded-[0.25rem] py-4 text-center text-sm font-bold text-white transition-transform active:scale-[0.98]"
                                >
                                    {{ purchaseActionLabel(course.price) }}
                                </Link>
                                <Link
                                    href="#course-content"
                                    class="flex w-full items-center justify-center rounded-[0.25rem] border border-ed-outline-variant/20 bg-ed-tertiary-fixed py-4 text-center text-sm font-bold text-ed-on-tertiary-fixed transition-colors hover:bg-ed-tertiary-fixed-dim"
                                >
                                    Preview curriculum
                                </Link>
                                <p class="text-xs text-ed-on-surface-variant">
                                    After enrolling, use the dashboard to track progress.
                                </p>
                            </div>
                            <div
                                v-else-if="page.props.auth?.user && !page.props.auth.user.email_verified_at"
                                class="mb-8 text-sm text-ed-on-surface-variant"
                            >
                                <p>Verify your email address to enroll in courses.</p>
                                <Link
                                    :href="route('verification.notice')"
                                    class="mt-3 inline-block text-sm font-bold text-ed-secondary hover:underline"
                                >
                                    Resend verification
                                </Link>
                            </div>
                            <div v-else class="mb-8 space-y-3">
                                <Link
                                    :href="route('login')"
                                    class="course-show-editorial-gradient flex w-full items-center justify-center rounded-[0.25rem] py-4 text-center text-sm font-bold text-white transition-transform active:scale-[0.98]"
                                >
                                    Log in to enroll
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="flex w-full items-center justify-center rounded-[0.25rem] border border-ed-outline-variant/20 bg-ed-tertiary-fixed py-4 text-center text-sm font-bold text-ed-on-tertiary-fixed transition-colors hover:bg-ed-tertiary-fixed-dim"
                                >
                                    Create an account
                                </Link>
                            </div>

                            <div class="space-y-4">
                                <h4
                                    class="font-manrope text-sm font-bold uppercase tracking-wider text-ed-on-surface-variant"
                                >
                                    This course includes
                                </h4>
                                <ul class="space-y-3">
                                    <li
                                        v-if="curriculumStats.totalMinutes"
                                        class="flex items-center gap-3 text-sm font-medium text-ed-on-surface"
                                    >
                                        <span class="material-symbols-outlined text-ed-on-tertiary-container">
                                            videocam
                                        </span>
                                        {{ formatTotalDuration(curriculumStats.totalMinutes) }} on-demand content
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-medium text-ed-on-surface">
                                        <span class="material-symbols-outlined text-ed-on-tertiary-container">
                                            article
                                        </span>
                                        Structured lessons &amp; readings
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-medium text-ed-on-surface">
                                        <span class="material-symbols-outlined text-ed-on-tertiary-container">
                                            all_inclusive
                                        </span>
                                        Full access while enrolled
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-medium text-ed-on-surface">
                                        <span class="material-symbols-outlined text-ed-on-tertiary-container">
                                            smartphone
                                        </span>
                                        Learn on desktop or mobile
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-medium text-ed-on-surface">
                                        <span class="material-symbols-outlined text-ed-on-tertiary-container">
                                            workspace_premium
                                        </span>
                                        Certificate when available
                                    </li>
                                </ul>
                            </div>

                            <div
                                class="mt-8 flex justify-between border-t border-ed-outline-variant/10 pt-8"
                            >
                                <button
                                    type="button"
                                    class="flex items-center gap-1 text-sm font-bold text-ed-secondary hover:underline"
                                    @click="shareCourse"
                                >
                                    Share
                                </button>
                                <Link
                                    :href="route('contact')"
                                    class="text-sm font-bold text-ed-secondary hover:underline"
                                >
                                    Apply coupon
                                </Link>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </MarketingLayout>
</template>

<style scoped>
.course-show-editorial-gradient {
    background: linear-gradient(45deg, #000000 0%, #141939 100%);
}

.course-show-prose :deep(p) {
    margin-top: 0.75rem;
    line-height: 1.65;
}
.course-show-prose :deep(p:first-child) {
    margin-top: 0;
}
.course-show-prose :deep(ul),
.course-show-prose :deep(ol) {
    margin-top: 0.75rem;
    padding-left: 1.25rem;
}
.course-show-prose :deep(li) {
    margin-top: 0.35rem;
}
.course-show-prose :deep(a) {
    color: #335f9b;
    text-decoration: underline;
}
.course-show-prose :deep(strong) {
    color: #1a1c1c;
    font-weight: 600;
}
</style>
