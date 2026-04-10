<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import EventPreviewPanel from '@/Components/Admin/EventPreviewPanel.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    event: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Preview · ${event.title}`" />

    <DashboardLayout title="Event preview">
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold leading-tight text-ink">
                    Event preview
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a
                        v-if="event.slug"
                        :href="route('events.show', event.slug)"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex justify-center rounded-full border border-accent bg-white px-4 py-2 text-sm font-medium text-accent transition-colors hover:bg-surface-raised"
                    >
                        Open public page
                    </a>
                    <Link
                        :href="route('admin.events.edit', event.id)"
                        class="inline-flex justify-center rounded-full border border-border bg-surface px-4 py-2 text-sm font-medium text-ink transition-colors hover:bg-surface-raised"
                    >
                        Edit
                    </Link>
                    <Link
                        :href="route('admin.events.index')"
                        class="inline-flex justify-center rounded-full bg-accent px-4 py-2 text-sm font-medium text-accent-fg transition-colors hover:bg-accent-hover"
                    >
                        Back to events
                    </Link>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-3xl">
            <EventPreviewPanel
                :title="event.title"
                :excerpt="event.excerpt"
                :description-html="event.description"
                :speaker-name="event.speaker_name"
                :location="event.location"
                :timezone="event.timezone"
                :date-summary="event.date_summary"
                :recurrence-summary="event.recurrence_summary"
                :exclusions-summary="event.exclusions_summary"
                :image-src="event.featured_image_url"
            />
        </div>
    </DashboardLayout>
</template>
