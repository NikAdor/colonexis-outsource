<script setup>
import EventPreviewPanel from '@/Components/Admin/EventPreviewPanel.vue';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    event: {
        type: Object,
        required: true,
    },
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head :title="event.title" />

        <div class="relative min-h-screen bg-paper">
        <CosmicFlowBackground />
        <main class="relative z-10 mx-auto max-w-4xl px-4 pb-24 pt-6 sm:px-6 lg:px-8">
            <nav class="mb-8 text-sm text-ink-muted">
                <Link href="/" class="hover:text-accent">Home</Link>
                <span class="mx-2">/</span>
                <span class="text-ink">Event</span>
            </nav>

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
        </main>

        </div>
    </MarketingLayout>
</template>
