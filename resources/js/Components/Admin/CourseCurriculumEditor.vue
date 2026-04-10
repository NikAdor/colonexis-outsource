<script setup>
import WysiwygEditor from '@/Components/WysiwygEditor.vue';

const sections = defineModel({
    type: Array,
    default: () => [],
});

const emit = defineEmits(['set-lesson-file']);

const contentTypes = [
    { value: 'text', label: 'Text (editor)' },
    { value: 'video', label: 'Video (URL)' },
    { value: 'image', label: 'Image (upload)' },
    { value: 'pdf', label: 'PDF (upload)' },
];

function defaultLesson() {
    return {
        title: '',
        duration_minutes: null,
        content_type: 'text',
        video_url: '',
        body: '',
        image_path: null,
        pdf_path: null,
        image_url: null,
        pdf_url: null,
    };
}

function defaultQuizQuestion() {
    return {
        question: '',
        options: ['', ''],
        correct_option_index: 0,
    };
}

function addSection() {
    sections.value = [
        ...sections.value,
        {
            title: '',
            quiz_enabled: false,
            quiz_title: '',
            quiz_questions: [],
            lessons: [defaultLesson()],
        },
    ];
}

function removeSection(index) {
    sections.value = sections.value.filter((_, i) => i !== index);
}

function addLesson(sectionIndex) {
    const next = [...sections.value];
    next[sectionIndex].lessons = [...(next[sectionIndex].lessons || []), defaultLesson()];
    sections.value = next;
}

function removeLesson(sectionIndex, lessonIndex) {
    const next = [...sections.value];
    next[sectionIndex].lessons = next[sectionIndex].lessons.filter((_, i) => i !== lessonIndex);
    if (!next[sectionIndex].lessons.length) {
        next[sectionIndex].lessons = [defaultLesson()];
    }
    sections.value = next;
}

function onContentTypeChange(sectionIndex, lessonIndex) {
    const lesson = sections.value[sectionIndex].lessons[lessonIndex];
    lesson.video_url = '';
    lesson.body = '';
    lesson.image_path = null;
    lesson.pdf_path = null;
    lesson.image_url = null;
    lesson.pdf_url = null;
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'image', file: null });
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'pdf', file: null });
}

function onLessonImageChange(sectionIndex, lessonIndex, e) {
    const file = e.target.files?.[0] || null;
    const lesson = sections.value[sectionIndex].lessons[lessonIndex];
    if (file) {
        lesson.image_path = null;
        lesson.image_url = null;
    }
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'image', file });
    e.target.value = '';
}

function onLessonPdfChange(sectionIndex, lessonIndex, e) {
    const file = e.target.files?.[0] || null;
    const lesson = sections.value[sectionIndex].lessons[lessonIndex];
    if (file) {
        lesson.pdf_path = null;
        lesson.pdf_url = null;
    }
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'pdf', file });
    e.target.value = '';
}

function clearLessonImage(sectionIndex, lessonIndex) {
    const lesson = sections.value[sectionIndex].lessons[lessonIndex];
    lesson.image_path = null;
    lesson.image_url = null;
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'image', file: null });
}

function clearLessonPdf(sectionIndex, lessonIndex) {
    const lesson = sections.value[sectionIndex].lessons[lessonIndex];
    lesson.pdf_path = null;
    lesson.pdf_url = null;
    emit('set-lesson-file', { sectionIndex, lessonIndex, kind: 'pdf', file: null });
}

function addQuizQuestion(sectionIndex) {
    const next = [...sections.value];
    if (!next[sectionIndex].quiz_questions) {
        next[sectionIndex].quiz_questions = [];
    }
    next[sectionIndex].quiz_questions = [...next[sectionIndex].quiz_questions, defaultQuizQuestion()];
    sections.value = next;
}

function removeQuizQuestion(sectionIndex, qi) {
    const next = [...sections.value];
    next[sectionIndex].quiz_questions = next[sectionIndex].quiz_questions.filter((_, i) => i !== qi);
    sections.value = next;
}

function addQuizOption(sectionIndex, qi) {
    const next = [...sections.value];
    next[sectionIndex].quiz_questions[qi].options.push('');
    sections.value = next;
}

function removeQuizOption(sectionIndex, qi, oi) {
    const next = [...sections.value];
    const opts = next[sectionIndex].quiz_questions[qi].options.filter((_, i) => i !== oi);
    next[sectionIndex].quiz_questions[qi].options = opts.length >= 2 ? opts : ['', ''];
    if (next[sectionIndex].quiz_questions[qi].correct_option_index >= next[sectionIndex].quiz_questions[qi].options.length) {
        next[sectionIndex].quiz_questions[qi].correct_option_index = 0;
    }
    sections.value = next;
}
</script>

<template>
    <div class="space-y-6">
        <p class="text-sm text-ink-muted">
            Each lesson can be <strong>rich text</strong>, an embedded <strong>video URL</strong> (YouTube/Vimeo/direct), an <strong>image</strong>, or a <strong>PDF</strong>. Add an optional <strong>section quiz</strong> at the end of each module.
        </p>

        <div
            v-for="(section, si) in sections"
            :key="si"
            class="overflow-hidden rounded-xl border border-border bg-surface-raised/40 shadow-sm"
        >
            <div class="flex items-start gap-3 border-b border-border bg-surface-raised px-4 py-3">
                <span class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-accent/15 text-sm font-bold text-accent">
                    {{ si + 1 }}
                </span>
                <div class="min-w-0 flex-1 space-y-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Section title</label>
                        <input
                            v-model="section.title"
                            type="text"
                            class="mt-1 block w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ink shadow-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                            placeholder="e.g. Module 1 — Foundations"
                        >
                    </div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-ink">
                        <input
                            v-model="section.quiz_enabled"
                            type="checkbox"
                            class="rounded border-border text-accent focus:ring-accent"
                        >
                        <span>Include a quiz for this section</span>
                    </label>
                    <div v-if="section.quiz_enabled" class="rounded-lg border border-dashed border-accent/40 bg-surface p-4">
                        <label class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Quiz title</label>
                        <input
                            v-model="section.quiz_title"
                            type="text"
                            class="mt-1 block w-full rounded-lg border border-border px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                            placeholder="e.g. Check your understanding"
                        >
                        <div class="mt-4 space-y-4">
                            <div
                                v-for="(q, qi) in (section.quiz_questions || [])"
                                :key="qi"
                                class="rounded-lg border border-border bg-surface-raised/50 p-3"
                            >
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <span class="text-xs font-semibold text-ink-muted">Question {{ qi + 1 }}</span>
                                    <button
                                        type="button"
                                        class="text-xs text-red-600 hover:text-red-800"
                                        @click="removeQuizQuestion(si, qi)"
                                    >
                                        Remove
                                    </button>
                                </div>
                                <input
                                    v-model="q.question"
                                    type="text"
                                    class="mb-3 block w-full rounded-md border border-border px-2 py-1.5 text-sm"
                                    placeholder="Question text"
                                >
                                <p class="mb-2 text-xs text-ink-muted">
                                    Answer choices (select the correct one)
                                </p>
                                <div class="space-y-2">
                                    <label
                                        v-for="(opt, oi) in q.options"
                                        :key="oi"
                                        class="flex items-center gap-2"
                                    >
                                        <input
                                            v-model.number="q.correct_option_index"
                                            type="radio"
                                            class="text-accent focus:ring-accent"
                                            :name="`correct-${si}-${qi}`"
                                            :value="oi"
                                        >
                                        <input
                                            v-model="q.options[oi]"
                                            type="text"
                                            class="min-w-0 flex-1 rounded-md border border-border px-2 py-1 text-sm"
                                            :placeholder="`Option ${oi + 1}`"
                                        >
                                        <button
                                            v-if="q.options.length > 2"
                                            type="button"
                                            class="text-xs text-red-600"
                                            @click="removeQuizOption(si, qi, oi)"
                                        >
                                            ×
                                        </button>
                                    </label>
                                </div>
                                <button
                                    type="button"
                                    class="mt-2 text-xs font-medium text-accent hover:text-accent-hover"
                                    @click="addQuizOption(si, qi)"
                                >
                                    + Add option
                                </button>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="mt-3 text-sm font-medium text-accent hover:text-accent-hover"
                            @click="addQuizQuestion(si)"
                        >
                            + Add quiz question
                        </button>
                    </div>
                </div>
                <button
                    v-if="sections.length > 1"
                    type="button"
                    class="shrink-0 text-sm text-red-600 hover:text-red-800"
                    @click="removeSection(si)"
                >
                    Remove section
                </button>
            </div>

            <div class="space-y-4 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                    Lessons
                </p>

                <div
                    v-for="(lesson, li) in section.lessons"
                    :key="li"
                    class="rounded-xl border border-border/80 bg-surface p-4"
                >
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="min-w-0 flex-1">
                            <label class="text-xs text-ink-muted">Lesson title</label>
                            <input
                                v-model="lesson.title"
                                type="text"
                                class="mt-0.5 block w-full rounded-md border border-border px-2 py-1.5 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                placeholder="Lesson title"
                            >
                        </div>
                        <div class="w-36">
                            <label class="text-xs text-ink-muted">Content type</label>
                            <select
                                v-model="lesson.content_type"
                                class="mt-0.5 block w-full rounded-md border border-border px-2 py-1.5 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                @change="onContentTypeChange(si, li)"
                            >
                                <option v-for="ct in contentTypes" :key="ct.value" :value="ct.value">
                                    {{ ct.label }}
                                </option>
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="text-xs text-ink-muted">Min</label>
                            <input
                                v-model.number="lesson.duration_minutes"
                                type="number"
                                min="0"
                                step="1"
                                class="mt-0.5 block w-full rounded-md border border-border px-2 py-1.5 text-sm"
                                placeholder="—"
                            >
                        </div>
                        <button
                            v-if="section.lessons.length > 1"
                            type="button"
                            class="pb-1 text-xs text-red-600 hover:text-red-800"
                            @click="removeLesson(si, li)"
                        >
                            Remove lesson
                        </button>
                    </div>

                    <div class="mt-4 border-t border-border pt-4">
                        <div v-if="lesson.content_type === 'video'" class="space-y-2">
                            <label class="text-xs font-medium text-ink-muted">Video URL</label>
                            <input
                                v-model="lesson.video_url"
                                type="url"
                                class="block w-full rounded-lg border border-border px-3 py-2 text-sm font-mono focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                placeholder="https://www.youtube.com/watch?v=… or direct file URL"
                            >
                            <p class="text-xs text-ink-muted">
                                Paste a YouTube/Vimeo link or a direct video URL.
                            </p>
                        </div>

                        <div v-else-if="lesson.content_type === 'image'" class="space-y-2">
                            <div v-if="lesson.image_url || lesson.image_path" class="flex flex-wrap items-center gap-3">
                                <img
                                    v-if="lesson.image_url"
                                    :src="lesson.image_url"
                                    alt=""
                                    class="h-24 max-w-xs rounded-md border border-border object-cover"
                                >
                                <button
                                    type="button"
                                    class="text-sm text-red-600 hover:text-red-800"
                                    @click="clearLessonImage(si, li)"
                                >
                                    Remove image
                                </button>
                            </div>
                            <label class="text-xs font-medium text-ink-muted">Upload image</label>
                            <input
                                type="file"
                                accept="image/*"
                                class="block w-full text-sm text-ink-muted file:mr-3 file:rounded-md file:border-0 file:bg-accent file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-accent-fg"
                                @change="onLessonImageChange(si, li, $event)"
                            >
                        </div>

                        <div v-else-if="lesson.content_type === 'pdf'" class="space-y-2">
                            <div v-if="lesson.pdf_url || lesson.pdf_path" class="flex flex-wrap items-center gap-3">
                                <a
                                    v-if="lesson.pdf_url"
                                    :href="lesson.pdf_url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-sm font-medium text-accent hover:underline"
                                >
                                    Current PDF (open)
                                </a>
                                <button
                                    type="button"
                                    class="text-sm text-red-600 hover:text-red-800"
                                    @click="clearLessonPdf(si, li)"
                                >
                                    Remove PDF
                                </button>
                            </div>
                            <label class="text-xs font-medium text-ink-muted">Upload PDF</label>
                            <input
                                type="file"
                                accept=".pdf,application/pdf"
                                class="block w-full text-sm text-ink-muted file:mr-3 file:rounded-md file:border-0 file:bg-accent file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-accent-fg"
                                @change="onLessonPdfChange(si, li, $event)"
                            >
                        </div>

                        <div v-else class="space-y-2">
                            <label class="text-xs font-medium text-ink-muted">Lesson content</label>
                            <WysiwygEditor v-model="lesson.body" placeholder="Write lesson content…" />
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    class="text-sm font-medium text-accent hover:text-accent-hover"
                    @click="addLesson(si)"
                >
                    + Add lesson
                </button>
            </div>
        </div>

        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-full border border-dashed border-accent/50 bg-accent/5 px-4 py-2 text-sm font-medium text-accent transition-colors hover:bg-accent/10"
            @click="addSection"
        >
            + Add section
        </button>
    </div>
</template>
