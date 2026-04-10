<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import EventPreviewPanel from '@/Components/Admin/EventPreviewPanel.vue';
import WysiwygEditor from '@/Components/WysiwygEditor.vue';
import {
    buildDateSummaryFromForm,
    buildExclusionsSummaryFromForm,
    buildRecurrenceSummaryFromForm,
    useFeaturedPreview,
} from '@/composables/useAdminEventPreview.js';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    defaultTimezone: {
        type: String,
        default: 'UTC',
    },
    timezones: {
        type: Array,
        default: () => [],
    },
});

const showRecurrenceDetails = ref(false);

const recurrenceOptions = [
    { value: 'daily', label: 'Daily' },
    { value: 'weekly', label: 'Weekly' },
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly', label: 'Yearly' },
    { value: 'once', label: 'Once' },
    { value: 'none', label: 'No repeat' },
];

const form = useForm({
    title: '',
    excerpt: '',
    description: '',
    speaker_name: '',
    location: '',
    timezone: props.defaultTimezone || 'UTC',
    all_day: false,
    start_date: '',
    end_date: '',
    starts_at: '',
    ends_at: '',
    recurrence_frequency: 'none',
    recurrence_on: '',
    exclusion_dates: [],
    featured_image: null,
    remove_featured_image: false,
});

function onFeaturedChange(e) {
    const f = e.target.files?.[0];
    form.featured_image = f || null;
    if (f) {
        form.remove_featured_image = false;
    }
}

function addExclusion() {
    form.exclusion_dates.push('');
}

function removeExclusion(index) {
    form.exclusion_dates.splice(index, 1);
}

function formatLongDate(ymd) {
    if (!ymd) {
        return '';
    }
    const d = new Date(`${ymd}T12:00:00`);
    if (Number.isNaN(d.getTime())) {
        return ymd;
    }
    return d.toLocaleDateString(undefined, {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

const allDayHelp = computed(() => {
    if (!form.all_day || !form.start_date) {
        return '';
    }
    const end = form.end_date || form.start_date;
    const startLabel = formatLongDate(form.start_date);
    const endLabel = formatLongDate(end);
    if (form.start_date === end) {
        return `This event is all day on ${startLabel}.`;
    }
    return `This event is all day starting on ${startLabel} and ending on ${endLabel}.`;
});

const recurrenceHelp = computed(() => {
    const freq = form.recurrence_frequency;
    if (freq === 'once' && form.recurrence_on) {
        return `One multi-day event starting on ${formatLongDate(form.recurrence_on)}`;
    }
    if (freq === 'none') {
        return 'Does not repeat.';
    }
    return `Repeats ${freq}.`;
});

const { displaySrc: previewImageSrc } = useFeaturedPreview(form, () => null);

const previewDateSummary = computed(() => buildDateSummaryFromForm(form));
const previewRecurrenceSummary = computed(() => buildRecurrenceSummaryFromForm(form));
const previewExclusionsSummary = computed(() => buildExclusionsSummaryFromForm(form.exclusion_dates));

const submit = () => {
    form.post(route('admin.events.store'), { forceFormData: true });
};
</script>

<template>
    <Head title="Add event" />

    <DashboardLayout title="Add event">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-ink">
                Add event
            </h2>
        </template>

        <div class="mx-auto max-w-6xl">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-start">
                <div class="min-w-0">
                    <div class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
                        <div class="p-6">
                            <form class="space-y-8" @submit.prevent="submit">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                            required
                        >
                        <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Short description / excerpt</label>
                        <textarea
                            v-model="form.excerpt"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                            placeholder="Brief summary for cards and listings"
                        />
                        <div v-if="form.errors.excerpt" class="mt-1 text-sm text-red-600">
                            {{ form.errors.excerpt }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-0.5 text-xs text-ink-muted">
                            Full details (rich text)
                        </p>
                        <div class="mt-2">
                            <WysiwygEditor v-model="form.description" />
                        </div>
                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Featured image</label>
                        <input
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-ink-muted file:mr-4 file:rounded-md file:border-0 file:bg-accent file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-accent-fg hover:file:bg-accent-hover"
                            @change="onFeaturedChange"
                        >
                        <div v-if="form.errors.featured_image" class="mt-1 text-sm text-red-600">
                            {{ form.errors.featured_image }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Speaker name</label>
                        <input
                            v-model="form.speaker_name"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                            placeholder="Optional"
                        >
                        <div v-if="form.errors.speaker_name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.speaker_name }}
                        </div>
                    </div>

                    <div class="border-t border-border pt-6">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-ink">
                            Time &amp; date
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start / end</label>
                                <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                                    <template v-if="form.all_day">
                                        <div class="flex flex-1 flex-col gap-1 sm:max-w-[12rem]">
                                            <span class="text-xs text-ink-muted">Start date</span>
                                            <input
                                                v-model="form.start_date"
                                                type="date"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                                required
                                            >
                                        </div>
                                        <span class="hidden pb-2 text-sm text-ink-muted sm:inline">to</span>
                                        <div class="flex flex-1 flex-col gap-1 sm:max-w-[12rem]">
                                            <span class="text-xs text-ink-muted">End date</span>
                                            <input
                                                v-model="form.end_date"
                                                type="date"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                            >
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="flex flex-1 flex-col gap-1">
                                            <span class="text-xs text-ink-muted">Starts</span>
                                            <input
                                                v-model="form.starts_at"
                                                type="datetime-local"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                                required
                                            >
                                        </div>
                                        <span class="hidden pb-2 text-sm text-ink-muted sm:inline">to</span>
                                        <div class="flex flex-1 flex-col gap-1">
                                            <span class="text-xs text-ink-muted">Ends (optional)</span>
                                            <input
                                                v-model="form.ends_at"
                                                type="datetime-local"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                            >
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <label class="flex items-center gap-2 text-sm text-ink">
                                        <input
                                            v-model="form.all_day"
                                            type="checkbox"
                                            class="rounded border-gray-300 text-accent focus:ring-accent"
                                        >
                                        All day event
                                    </label>
                                </div>

                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-ink-muted">Time zone</label>
                                    <select
                                        v-model="form.timezone"
                                        class="mt-1 block w-full max-w-md rounded-md border-gray-300 text-sm shadow-sm focus:border-accent focus:ring-accent"
                                    >
                                        <option v-for="tz in timezones" :key="tz" :value="tz">
                                            {{ tz }}
                                        </option>
                                    </select>
                                </div>

                                <p v-if="allDayHelp" class="mt-2 text-sm italic text-ink-muted">
                                    {{ allDayHelp }}
                                </p>
                                <div v-if="form.errors.starts_at" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.starts_at }}
                                </div>
                                <div v-if="form.errors.ends_at" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.ends_at }}
                                </div>
                                <div v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.start_date }}
                                </div>
                                <div v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.end_date }}
                                </div>
                                <div v-if="form.errors.timezone" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.timezone }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Recurring event</label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button
                                        v-for="opt in recurrenceOptions"
                                        :key="opt.value"
                                        type="button"
                                        class="rounded-md border px-3 py-1.5 text-sm transition-colors"
                                        :class="
                                            form.recurrence_frequency === opt.value
                                                ? 'border-gray-800 bg-gray-800 text-white'
                                                : 'border-border bg-surface-raised text-ink hover:bg-border'
                                        "
                                        @click="form.recurrence_frequency = opt.value"
                                    >
                                        {{ opt.label }}
                                    </button>
                                </div>

                                <div class="mt-3">
                                    <label class="block text-xs font-medium text-ink-muted">On (for “once” / anchor date)</label>
                                    <input
                                        v-model="form.recurrence_on"
                                        type="date"
                                        class="mt-1 block max-w-xs rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                    >
                                </div>
                                <p class="mt-2 text-sm italic text-ink-muted">
                                    {{ recurrenceHelp }}
                                </p>

                                <button
                                    type="button"
                                    class="mt-2 flex items-center gap-1 text-sm text-accent hover:text-accent-hover"
                                    @click="showRecurrenceDetails = !showRecurrenceDetails"
                                >
                                    <span class="inline-block transition-transform" :class="showRecurrenceDetails ? 'rotate-180' : ''">▼</span>
                                    Show details
                                </button>

                                <div v-show="showRecurrenceDetails" class="mt-4 space-y-3 rounded-lg border border-border bg-surface-raised/50 p-4">
                                    <p class="text-xs text-ink-muted">
                                        Exclusion dates are stored with the event. Full calendar expansion for complex repeats can be added later.
                                    </p>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Event will not occur</label>
                                        <div class="mt-2 space-y-2">
                                            <div
                                                v-for="(_, index) in form.exclusion_dates"
                                                :key="index"
                                                class="flex flex-wrap items-center gap-2"
                                            >
                                                <input
                                                    v-model="form.exclusion_dates[index]"
                                                    type="date"
                                                    class="rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                                                >
                                                <button
                                                    type="button"
                                                    class="text-sm text-red-600 hover:text-red-800"
                                                    @click="removeExclusion(index)"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            class="mt-2 rounded-md border border-accent bg-white px-3 py-1.5 text-sm font-medium text-accent hover:bg-surface-raised"
                                            @click="addExclusion"
                                        >
                                            Add exclusion
                                        </button>
                                    </div>
                                </div>

                                <div v-if="form.errors.recurrence_frequency" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.recurrence_frequency }}
                                </div>
                                <div v-if="form.errors.recurrence_on" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.recurrence_on }}
                                </div>
                                <div v-if="form.errors.exclusion_dates" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.exclusion_dates }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location (optional)</label>
                        <input
                            v-model="form.location"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent"
                        >
                        <div v-if="form.errors.location" class="mt-1 text-sm text-red-600">
                            {{ form.errors.location }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            type="submit"
                            class="rounded-full bg-accent px-4 py-2 text-sm font-medium text-accent-fg transition-colors hover:bg-accent-hover disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Create
                        </button>
                        <Link :href="route('admin.events.index')" class="text-sm text-gray-600 hover:text-gray-900">Cancel</Link>
                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                <aside class="min-w-0 lg:sticky lg:top-6 lg:self-start">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-muted">
                        Live preview
                    </p>
                    <EventPreviewPanel
                        :title="form.title"
                        :excerpt="form.excerpt"
                        :description-html="form.description"
                        :speaker-name="form.speaker_name"
                        :location="form.location"
                        :timezone="form.timezone"
                        :date-summary="previewDateSummary"
                        :recurrence-summary="previewRecurrenceSummary"
                        :exclusions-summary="previewExclusionsSummary"
                        :image-src="previewImageSrc"
                        draft-badge
                    />
                </aside>
            </div>
        </div>
    </DashboardLayout>
</template>
