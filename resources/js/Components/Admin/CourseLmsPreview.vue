<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    excerpt: { type: String, default: '' },
    price: { type: [String, Number], default: '0' },
    level: { type: String, default: 'beginner' },
    durationMinutes: { type: Number, default: null },
    language: { type: String, default: 'English' },
    featuredImageSrc: { type: String, default: null },
    isPublished: { type: Boolean, default: true },
    learningOutcomes: { type: Array, default: () => [] },
    sections: { type: Array, default: () => [] },
    draftBadge: { type: Boolean, default: false },
});

const levelLabel = computed(() => {
    const m = {
        beginner: 'Beginner',
        intermediate: 'Intermediate',
        advanced: 'Advanced',
        all_levels: 'All levels',
    };
    return m[props.level] ?? props.level;
});

const priceLabel = computed(() => {
    const n = Number(props.price);
    if (Number.isNaN(n) || n <= 0) {
        return 'Free';
    }
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(n);
});

const lessonCount = computed(() => {
    return (props.sections || []).reduce((acc, s) => acc + (s.lessons?.length || 0), 0);
});

const durationLabel = computed(() => {
    const m = props.durationMinutes;
    if (m == null || m === '' || Number(m) <= 0) {
        return '—';
    }
    const n = Number(m);
    if (n < 60) {
        return `${n} min`;
    }
    const h = Math.floor(n / 60);
    const r = n % 60;
    return r ? `${h}h ${r}m` : `${h}h`;
});

const outcomePreview = computed(() => {
    return (props.learningOutcomes || []).filter((x) => x && String(x).trim()).slice(0, 4);
});
</script>

<template>
    <div class="relative overflow-hidden rounded-2xl border border-border bg-surface shadow-lg ring-1 ring-black/5">
        <div
            v-if="draftBadge"
            class="absolute right-3 top-3 z-10 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-900"
        >
            Draft preview
        </div>
        <div class="aspect-video w-full overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900">
            <img
                v-if="featuredImageSrc"
                :src="featuredImageSrc"
                alt=""
                class="h-full w-full object-cover"
            >
            <div
                v-else
                class="flex h-full min-h-[140px] items-center justify-center px-6 text-center text-sm text-white/70"
            >
                Add a cover image — this is your Udemy-style thumbnail.
            </div>
        </div>
        <div class="p-5">
            <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                <span
                    class="rounded-full px-2 py-0.5"
                    :class="isPublished ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'"
                >
                    {{ isPublished ? 'Published' : 'Draft' }}
                </span>
                <span class="rounded-full bg-surface-raised px-2 py-0.5 text-ink-muted">
                    {{ levelLabel }}
                </span>
                <span class="text-ink-muted">{{ language }}</span>
            </div>
            <h3 class="font-display mt-3 text-lg font-bold leading-snug text-ink">
                {{ title || 'Course title' }}
            </h3>
            <p v-if="subtitle" class="mt-1 text-sm text-ink-muted">
                {{ subtitle }}
            </p>
            <p v-if="excerpt" class="mt-2 line-clamp-3 text-sm leading-relaxed text-ink-muted">
                {{ excerpt }}
            </p>

            <ul v-if="outcomePreview.length" class="mt-4 space-y-1.5 border-t border-border pt-4">
                <li
                    v-for="(o, i) in outcomePreview"
                    :key="i"
                    class="flex gap-2 text-xs text-ink"
                >
                    <span class="text-accent">✓</span>
                    <span>{{ o }}</span>
                </li>
            </ul>

            <div class="mt-4 flex flex-wrap gap-4 border-t border-border pt-4 text-xs text-ink-muted">
                <span>{{ sections.length }} sections</span>
                <span>{{ lessonCount }} lessons</span>
                <span>{{ durationLabel }} total</span>
            </div>

            <div class="mt-4 flex items-center justify-between gap-3">
                <span class="text-xl font-bold text-accent">{{ priceLabel }}</span>
                <span class="rounded-full bg-accent px-4 py-2 text-xs font-semibold text-accent-fg opacity-90">
                    Enroll
                </span>
            </div>
        </div>
    </div>
</template>
