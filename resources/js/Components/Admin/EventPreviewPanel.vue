<script setup>
defineProps({
    title: {
        type: String,
        default: '',
    },
    excerpt: {
        type: String,
        default: '',
    },
    descriptionHtml: {
        type: String,
        default: '',
    },
    speakerName: {
        type: String,
        default: '',
    },
    location: {
        type: String,
        default: '',
    },
    timezone: {
        type: String,
        default: 'UTC',
    },
    /** Preformatted date/time line (built server-side or from the form) */
    dateSummary: {
        type: String,
        default: '',
    },
    recurrenceSummary: {
        type: String,
        default: '',
    },
    exclusionsSummary: {
        type: String,
        default: '',
    },
    imageSrc: {
        type: String,
        default: null,
    },
    /** Shown in the card corner when previewing an unsaved draft */
    draftBadge: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="relative overflow-hidden rounded-2xl border border-border bg-surface shadow-sm">
        <div
            v-if="draftBadge"
            class="absolute right-4 top-4 z-10 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-900"
        >
            Draft preview
        </div>
        <div v-if="imageSrc" class="aspect-[21/9] w-full overflow-hidden bg-surface-raised">
            <img :src="imageSrc" alt="" class="h-full w-full object-cover">
        </div>
        <div class="p-6 sm:p-8">
            <p class="font-display text-2xl font-bold text-ink sm:text-3xl">
                {{ title || 'Untitled event' }}
            </p>
            <p v-if="speakerName" class="mt-2 text-base font-medium text-accent">
                {{ speakerName }}
            </p>
            <p v-if="excerpt" class="mt-3 text-ink-muted leading-relaxed">
                {{ excerpt }}
            </p>

            <div class="mt-6 space-y-3 border-t border-border pt-6 text-sm">
                <div v-if="dateSummary">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        When
                    </p>
                    <p class="mt-1 text-ink">
                        {{ dateSummary }}
                    </p>
                </div>
                <div v-if="timezone">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        Time zone
                    </p>
                    <p class="mt-1 font-mono text-sm text-ink">
                        {{ timezone }}
                    </p>
                </div>
                <div v-if="location">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        Location
                    </p>
                    <p class="mt-1 text-ink">
                        {{ location }}
                    </p>
                </div>
                <div v-if="recurrenceSummary">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        Recurrence
                    </p>
                    <p class="mt-1 text-ink">
                        {{ recurrenceSummary }}
                    </p>
                </div>
                <div v-if="exclusionsSummary">
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        Excludes
                    </p>
                    <p class="mt-1 text-ink">
                        {{ exclusionsSummary }}
                    </p>
                </div>
            </div>

            <div
                v-if="descriptionHtml && descriptionHtml.replace(/<[^>]+>/g, '').trim()"
                class="event-preview-prose mt-8 border-t border-border pt-8 text-ink"
                v-html="descriptionHtml"
            />
        </div>
    </div>
</template>

<style scoped>
.event-preview-prose :deep(p) {
    margin-top: 0.75em;
    margin-bottom: 0.75em;
    line-height: 1.6;
}
.event-preview-prose :deep(p:first-child) {
    margin-top: 0;
}
.event-preview-prose :deep(ul),
.event-preview-prose :deep(ol) {
    margin: 0.5em 0 0.5em 1.25em;
    padding-left: 0.5em;
}
.event-preview-prose :deep(li) {
    margin: 0.25em 0;
}
.event-preview-prose :deep(a) {
    color: var(--color-accent);
    text-decoration: underline;
}
.event-preview-prose :deep(strong) {
    font-weight: 600;
}
</style>
