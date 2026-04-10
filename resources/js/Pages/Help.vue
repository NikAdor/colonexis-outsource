<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Help &amp; Support" />

    <DashboardLayout title="Help">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-ink">
                Help &amp; Support
            </h2>
        </template>

        <div class="space-y-6 rounded-xl border border-border bg-surface p-6 shadow-sm sm:p-8">
            <div>
                <h3 class="font-display text-lg font-semibold text-ink">Get help</h3>
                <p class="mt-2 text-ink-muted">
                    For account questions, billing, or technical issues, contact your administrator or reach us through the Colonexis contact page.
                </p>
            </div>
            <a
                :href="route('contact')"
                class="inline-flex rounded-full bg-accent px-4 py-2 text-sm font-medium text-accent-fg transition-colors hover:bg-accent-hover"
            >
                Contact Colonexis
            </a>
        </div>
    </DashboardLayout>
</template>
