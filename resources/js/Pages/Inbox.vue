<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Inbox" />

    <DashboardLayout title="Inbox">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-ink">
                Inbox
            </h2>
        </template>

        <div class="rounded-xl border border-border bg-surface p-6 shadow-sm sm:p-8">
            <p class="text-ink-muted">
                Messages and notifications will appear here. This area is available to administrators and staff.
            </p>
        </div>
    </DashboardLayout>
</template>
