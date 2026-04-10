<script setup>
import CourseCurriculumEditor from '@/Components/Admin/CourseCurriculumEditor.vue';
import WysiwygEditor from '@/Components/WysiwygEditor.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useFeaturedPreview } from '@/composables/useAdminEventPreview.js';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
});

const defaultSection = () => ({
    title: '',
    quiz_enabled: false,
    quiz_title: '',
    quiz_questions: [],
    lessons: [
        {
            title: '',
            duration_minutes: null,
            content_type: 'text',
            video_url: '',
            body: '',
            image_path: null,
            pdf_path: null,
            image_url: null,
            pdf_url: null,
        },
    ],
});

const form = useForm({
    title: props.course.title,
    subtitle: props.course.subtitle ?? '',
    slug: props.course.slug,
    excerpt: props.course.excerpt ?? '',
    description: props.course.description ?? '',
    learning_outcomes: [...(props.course.learning_outcomes ?? [])],
    requirements: [...(props.course.requirements ?? [])],
    level: props.course.level ?? 'beginner',
    duration_minutes: props.course.duration_minutes,
    language: props.course.language ?? 'English',
    price: String(props.course.price),
    is_published: props.course.is_published,
    sections:
        props.course.sections?.length > 0
            ? JSON.parse(JSON.stringify(props.course.sections))
            : [defaultSection()],
    featured_image: null,
    remove_featured_image: false,
});

const { displaySrc: coverPreviewSrc } = useFeaturedPreview(form, () => props.course.featured_image_url);

const earlyBirdOn = ref(true);
const bulkLmsOn = ref(false);

const languageOptions = [
    { value: 'English', label: 'English (US)' },
    { value: 'English (UK)', label: 'English (UK)' },
    { value: 'German', label: 'German' },
    { value: 'Japanese', label: 'Japanese' },
];

function onCoverChange(e) {
    const f = e.target.files?.[0];
    form.featured_image = f || null;
    if (f) {
        form.remove_featured_image = false;
    }
}

function addOutcome() {
    form.learning_outcomes.push('');
}

function removeOutcome(i) {
    form.learning_outcomes.splice(i, 1);
}

function addRequirement() {
    form.requirements.push('');
}

function removeRequirement(i) {
    form.requirements.splice(i, 1);
}

function setLessonFile({ sectionIndex, lessonIndex, kind, file }) {
    const key = kind === 'image' ? `lesson_image_${sectionIndex}_${lessonIndex}` : `lesson_pdf_${sectionIndex}_${lessonIndex}`;
    if (file) {
        form[key] = file;
    } else {
        form[key] = null;
    }
}

function submit() {
    form.put(route('admin.courses.update', props.course.id), { forceFormData: true });
}

function archiveDraft() {
    form.is_published = false;
    submit();
}

const levelOptions = [
    { value: 'beginner', label: 'Beginner' },
    { value: 'intermediate', label: 'Intermediate' },
    { value: 'advanced', label: 'Advanced' },
    { value: 'all_levels', label: 'All levels' },
];

const inputSurface =
    'w-full rounded-md border-none bg-ed-surface-container-low p-4 text-ed-on-surface focus:ring-2 focus:ring-ed-secondary/30';
const labelClass = 'text-xs font-bold uppercase tracking-widest text-ed-outline';

const totalLessons = computed(() =>
    form.sections.reduce((n, s) => n + (s.lessons?.length || 0), 0),
);

const readinessBasic = computed(() => form.title.trim().length > 0);
const readinessPricing = computed(() => form.price !== '' && !Number.isNaN(Number(form.price)));
const readinessCurriculum = computed(() => totalLessons.value >= 5);
const readinessInstructor = computed(() => false);

const readinessPct = computed(() => {
    let n = 0;
    if (readinessBasic.value) n++;
    if (readinessPricing.value) n++;
    if (readinessCurriculum.value) n++;
    if (readinessInstructor.value) n++;
    return Math.round((n / 4) * 100);
});

const previewUrl = computed(() => {
    if (!form.is_published || !props.course.slug) {
        return null;
    }
    return route('courses.show', props.course.slug);
});

function openPreview() {
    if (previewUrl.value) {
        window.open(previewUrl.value, '_blank', 'noopener,noreferrer');
    }
}
</script>

<template>
    <Head :title="`Edit · ${course.title}`">
        <link
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
            rel="stylesheet"
        />
    </Head>

    <DashboardLayout title="Course designer">
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-ink-muted">
                        Course Designer
                    </p>
                    <h2 class="truncate text-xl font-semibold leading-tight text-ink">
                        Edit · {{ course.title }}
                    </h2>
                </div>
                <button
                    type="submit"
                    form="admin-course-form"
                    class="shrink-0 rounded-md bg-ink px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-opacity hover:opacity-90 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Save course
                </button>
            </div>
        </template>
        <template #mobileToolbar>
            <button
                type="submit"
                form="admin-course-form"
                class="rounded-lg bg-ink px-3 py-2 text-xs font-semibold text-white disabled:opacity-50"
                :disabled="form.processing"
            >
                Save
            </button>
        </template>

        <div class="mx-auto max-w-6xl">
            <form id="admin-course-form" class="grid grid-cols-1 gap-8 lg:grid-cols-12" @submit.prevent="submit">
                <div class="space-y-12 lg:col-span-8">
                    <section class="space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="font-manrope text-2xl font-extrabold tracking-tight text-ed-primary sm:text-3xl">
                                1. Basic info
                            </h2>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-bold tracking-widest"
                                :class="
                                    form.is_published
                                        ? 'bg-emerald-100 text-emerald-800'
                                        : 'bg-ed-tertiary-fixed text-ed-on-tertiary-container'
                                "
                            >
                                {{ form.is_published ? 'Live' : 'Draft' }}
                            </span>
                        </div>
                        <div class="space-y-8 rounded-lg bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label :class="labelClass">Course title</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        required
                                        :class="`${inputSurface} font-manrope text-lg font-semibold`"
                                    >
                                    <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label :class="labelClass">Course subtitle</label>
                                    <textarea v-model="form.subtitle" rows="2" :class="`${inputSurface} text-ed-on-surface-variant`" />
                                </div>
                                <div class="space-y-2">
                                    <label :class="labelClass">URL slug</label>
                                    <input
                                        v-model="form.slug"
                                        type="text"
                                        required
                                        :class="`${inputSurface} font-mono text-sm`"
                                    >
                                    <p v-if="form.errors.slug" class="text-sm text-red-600">{{ form.errors.slug }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label :class="labelClass">Promo excerpt</label>
                                    <textarea v-model="form.excerpt" rows="3" maxlength="500" :class="inputSurface" />
                                    <p class="text-xs text-ed-on-surface-variant">{{ (form.excerpt || '').length }}/500</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label :class="labelClass">Level</label>
                                    <select v-model="form.level" :class="`${inputSurface} appearance-none`">
                                        <option v-for="opt in levelOptions" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label :class="labelClass">Primary language</label>
                                    <select v-model="form.language" :class="`${inputSurface} appearance-none`">
                                        <option v-for="opt in languageOptions" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label :class="labelClass">Estimated duration (minutes)</label>
                                <input
                                    v-model.number="form.duration_minutes"
                                    type="number"
                                    min="0"
                                    step="1"
                                    :class="`${inputSurface} max-w-xs`"
                                >
                            </div>

                            <div class="border-t border-ed-outline-variant/20 pt-6">
                                <label :class="labelClass">What you’ll learn</label>
                                <div class="mt-3 space-y-2">
                                    <div v-for="(_, i) in form.learning_outcomes" :key="'o' + i" class="flex gap-2">
                                        <input v-model="form.learning_outcomes[i]" type="text" :class="inputSurface">
                                        <button
                                            type="button"
                                            class="shrink-0 text-sm text-ed-error hover:underline"
                                            @click="removeOutcome(i)"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="mt-2 text-sm font-bold text-ed-secondary hover:underline"
                                    @click="addOutcome"
                                >
                                    + Add outcome
                                </button>
                            </div>

                            <div class="border-t border-ed-outline-variant/20 pt-6">
                                <label :class="labelClass">Requirements</label>
                                <div class="mt-3 space-y-2">
                                    <div v-for="(_, i) in form.requirements" :key="'r' + i" class="flex gap-2">
                                        <input v-model="form.requirements[i]" type="text" :class="inputSurface">
                                        <button
                                            type="button"
                                            class="shrink-0 text-sm text-ed-error hover:underline"
                                            @click="removeRequirement(i)"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="mt-2 text-sm font-bold text-ed-secondary hover:underline"
                                    @click="addRequirement"
                                >
                                    + Add requirement
                                </button>
                            </div>

                            <div class="border-t border-ed-outline-variant/20 pt-6">
                                <label :class="labelClass">Full description</label>
                                <div class="mt-2">
                                    <WysiwygEditor v-model="form.description" placeholder="Full course description…" />
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-6">
                        <h2 class="font-manrope text-2xl font-extrabold tracking-tight text-ed-primary sm:text-3xl">
                            2. Curriculum builder
                        </h2>
                        <CourseCurriculumEditor v-model="form.sections" @set-lesson-file="setLessonFile" />
                    </section>

                    <section class="space-y-6">
                        <h2 class="font-manrope text-2xl font-extrabold tracking-tight text-ed-primary sm:text-3xl">
                            3. Pricing &amp; distribution
                        </h2>
                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                            <div class="space-y-6 rounded-lg bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                                <h3 class="text-sm font-black uppercase tracking-tighter text-ed-on-surface-variant">
                                    Standard enrollment
                                </h3>
                                <div class="space-y-2">
                                    <label :class="labelClass">Base price (USD)</label>
                                    <div class="relative">
                                        <span
                                            class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 font-bold text-ed-outline"
                                        >
                                            $
                                        </span>
                                        <input
                                            v-model="form.price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            required
                                            class="w-full rounded-md border-none bg-ed-surface-container-low py-4 pl-10 pr-4 font-manrope text-2xl font-bold focus:ring-2 focus:ring-ed-secondary/30"
                                        >
                                    </div>
                                    <p v-if="form.errors.price" class="text-sm text-red-600">{{ form.errors.price }}</p>
                                </div>
                                <label class="flex items-center gap-3 pt-2">
                                    <input
                                        v-model="form.is_published"
                                        type="checkbox"
                                        class="rounded border-ed-outline-variant text-ed-secondary focus:ring-ed-secondary/30"
                                    >
                                    <span class="text-sm font-medium text-ed-on-surface">Published (visible in catalog)</span>
                                </label>
                            </div>
                            <div class="space-y-6 rounded-lg bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                                <h3 class="text-sm font-black uppercase tracking-tighter text-ed-on-surface-variant">
                                    Discounting logic
                                </h3>
                                <p class="text-xs text-ed-on-surface-variant">Visual only — pricing rules can be wired later.</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Early bird discount</span>
                                    <button
                                        type="button"
                                        class="relative h-5 w-10 rounded-full bg-ed-secondary transition-colors"
                                        :class="!earlyBirdOn && 'bg-ed-surface-container-highest'"
                                        aria-label="Toggle early bird"
                                        @click="earlyBirdOn = !earlyBirdOn"
                                    >
                                        <span
                                            class="absolute top-1 h-3 w-3 rounded-full bg-white transition-all"
                                            :class="earlyBirdOn ? 'right-1' : 'left-1'"
                                        />
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Bulk purchase (LMS)</span>
                                    <button
                                        type="button"
                                        class="relative h-5 w-10 rounded-full bg-ed-surface-container-highest transition-colors"
                                        :class="bulkLmsOn && 'bg-ed-secondary'"
                                        aria-label="Toggle bulk LMS"
                                        @click="bulkLmsOn = !bulkLmsOn"
                                    >
                                        <span
                                            class="absolute top-1 h-3 w-3 rounded-full bg-white transition-all"
                                            :class="bulkLmsOn ? 'right-1' : 'left-1'"
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="flex flex-wrap gap-4 pb-8">
                        <Link
                            :href="route('admin.courses.index')"
                            class="text-sm font-medium text-ed-on-surface-variant hover:text-ed-primary"
                        >
                            ← Back to courses
                        </Link>
                    </div>
                </div>

                <div class="space-y-8 lg:col-span-4">
                    <div class="space-y-6 rounded-lg bg-ed-surface-container-low p-6">
                        <h3 class="font-manrope text-xs font-black uppercase tracking-widest text-ed-outline-variant">
                            Course media
                        </h3>
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-ed-on-surface-variant">Cover thumbnail</label>
                            <div
                                v-if="course.featured_image_url && !form.remove_featured_image"
                                class="mb-3 overflow-hidden rounded-md border border-ed-outline-variant/30"
                            >
                                <img :src="course.featured_image_url" alt="" class="aspect-video w-full object-cover">
                            </div>
                            <label
                                v-if="course.featured_image_url"
                                class="mb-3 flex items-center gap-2 text-sm text-ed-on-surface"
                            >
                                <input
                                    v-model="form.remove_featured_image"
                                    type="checkbox"
                                    class="rounded border-ed-outline-variant text-ed-secondary focus:ring-ed-secondary/30"
                                >
                                Remove current image
                            </label>
                            <label
                                class="group relative flex aspect-video cursor-pointer flex-col items-center justify-center overflow-hidden rounded-md border-2 border-dashed border-ed-outline-variant/50 bg-ed-surface-container-high"
                            >
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                    @change="onCoverChange"
                                >
                                <img
                                    v-if="coverPreviewSrc && !form.remove_featured_image"
                                    :src="coverPreviewSrc"
                                    alt=""
                                    class="absolute inset-0 z-[1] h-full w-full object-cover"
                                >
                                <div
                                    class="space-y-2 p-6 text-center transition-opacity"
                                    :class="coverPreviewSrc && !form.remove_featured_image ? 'opacity-0 group-hover:opacity-100' : ''"
                                >
                                    <span class="material-symbols-outlined text-4xl text-ed-outline-variant">
                                        add_photo_alternate
                                    </span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-ed-outline">
                                        Upload or replace (16:9)
                                    </p>
                                </div>
                                <div
                                    class="absolute inset-0 z-[5] flex items-center justify-center bg-slate-900/60 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <span class="border border-white/40 px-4 py-2 text-xs font-bold uppercase text-white">
                                        Replace image
                                    </span>
                                </div>
                            </label>
                            <p v-if="form.errors.featured_image" class="text-sm text-red-600">{{ form.errors.featured_image }}</p>
                        </div>
                        <div class="space-y-4 border-t border-ed-outline-variant/20 pt-4">
                            <label class="block text-xs font-bold text-ed-on-surface-variant">Promo video</label>
                            <div
                                class="flex items-center gap-4 rounded border border-ed-outline-variant/10 bg-ed-surface-container-lowest p-4 opacity-60"
                            >
                                <span class="material-symbols-outlined text-ed-secondary">videocam</span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold">Not configured</p>
                                    <p class="text-[10px] text-ed-outline">Upload trailers in a future release</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 rounded-lg bg-ed-primary-container p-6 text-white">
                        <h3 class="font-manrope text-xs font-black uppercase tracking-widest text-ed-on-primary-container">
                            Publishing readiness
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span
                                    class="material-symbols-outlined"
                                    :class="readinessBasic ? 'text-sky-400' : 'text-ed-on-primary-container/70'"
                                >
                                    {{ readinessBasic ? 'check_circle' : 'radio_button_unchecked' }}
                                </span>
                                <span :class="!readinessBasic && 'text-ed-on-primary-container/60'">Basic information</span>
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span
                                    class="material-symbols-outlined"
                                    :class="readinessPricing ? 'text-sky-400' : 'text-ed-on-primary-container/70'"
                                >
                                    {{ readinessPricing ? 'check_circle' : 'radio_button_unchecked' }}
                                </span>
                                <span :class="!readinessPricing && 'text-ed-on-primary-container/60'">Pricing &amp; tiers</span>
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span
                                    class="material-symbols-outlined"
                                    :class="readinessCurriculum ? 'text-sky-400' : 'text-ed-on-primary-container/70'"
                                >
                                    {{ readinessCurriculum ? 'check_circle' : 'radio_button_unchecked' }}
                                </span>
                                <span :class="!readinessCurriculum && 'text-ed-on-primary-container/60'">
                                    Curriculum structure (min 5 lessons)
                                </span>
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-ed-on-primary-container/60">
                                <span class="material-symbols-outlined">radio_button_unchecked</span>
                                Instructor verification
                            </li>
                        </ul>
                        <div class="border-t border-white/10 pt-4">
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-sky-400 transition-all" :style="{ width: `${readinessPct}%` }" />
                            </div>
                            <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-ed-on-primary-container">
                                {{ readinessPct }}% complete
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button
                            type="button"
                            class="flex w-full items-center justify-center gap-2 rounded-md bg-ed-surface-container-high py-4 font-manrope text-sm font-bold text-ed-on-surface-variant transition-colors hover:bg-ed-surface-container-highest disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="!previewUrl"
                            :title="previewUrl ? 'Open public course page' : 'Publish the course and keep a slug to preview'"
                            @click="openPreview"
                        >
                            <span class="material-symbols-outlined">visibility</span>
                            Preview course page
                        </button>
                        <button
                            type="button"
                            class="flex w-full items-center justify-center gap-2 rounded-md border-2 border-ed-outline-variant bg-transparent py-4 font-manrope text-sm font-bold text-ed-outline transition-colors hover:border-slate-400"
                            @click.prevent="archiveDraft"
                        >
                            <span class="material-symbols-outlined">archive</span>
                            Archive draft
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.material-symbols-outlined {
    font-family: 'Material Symbols Outlined', sans-serif;
    font-size: 22px;
    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;
    vertical-align: middle;
}
</style>
