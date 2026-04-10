<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    events: Object,
});
</script>

<template>
    <Head title="Events" />

    <DashboardLayout title="Events">
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold leading-tight text-ink">
                    Events
                </h2>
                <Link
                    :href="route('admin.events.create')"
                    class="inline-flex justify-center rounded-full bg-accent px-4 py-2 text-sm font-medium text-accent-fg transition-colors hover:bg-accent-hover"
                >
                    Add event
                </Link>
            </div>
        </template>

        <div class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
            <div class="overflow-x-auto p-6">
                <table class="w-full min-w-[40rem]">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Title</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Excerpt</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Speaker</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Starts</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Location</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-ink-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ev in events.data" :key="ev.id" class="border-b border-border/60 hover:bg-surface-raised/80">
                            <td class="px-4 py-3 font-medium text-ink">{{ ev.title }}</td>
                            <td class="max-w-xs px-4 py-3 text-sm text-ink-muted line-clamp-2">{{ ev.excerpt || '—' }}</td>
                            <td class="px-4 py-3 text-sm text-ink-muted">{{ ev.speaker_name || '—' }}</td>
                            <td class="px-4 py-3 text-sm text-ink-muted">{{ new Date(ev.starts_at).toLocaleString() }}</td>
                            <td class="px-4 py-3 text-sm text-ink-muted">{{ ev.location || '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a
                                        v-if="ev.slug"
                                        :href="route('events.show', ev.slug)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-accent hover:text-accent-hover"
                                    >
                                        Site
                                    </a>
                                    <Link
                                        :href="route('admin.events.preview', ev.id)"
                                        class="text-sm text-accent hover:text-accent-hover"
                                    >
                                        Preview
                                    </Link>
                                    <Link
                                        :href="route('admin.events.edit', ev.id)"
                                        class="text-sm text-accent hover:text-accent-hover"
                                    >
                                        Edit
                                    </Link>
                                    <Link
                                        :href="route('admin.events.destroy', ev.id)"
                                        method="delete"
                                        as="button"
                                        class="text-sm text-red-600 hover:text-red-800"
                                    >
                                        Delete
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="events.links" class="border-t border-border px-6 py-4">
                <div class="flex flex-wrap gap-2">
                    <template v-for="(link, index) in events.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label"
                            class="rounded px-3 py-1 text-sm"
                            :class="link.active ? 'bg-accent text-accent-fg' : 'bg-surface-raised text-ink-muted hover:bg-border'"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="cursor-not-allowed rounded px-3 py-1 text-sm opacity-50"
                            :class="link.active ? 'bg-accent text-accent-fg' : 'bg-surface-raised text-ink-muted'"
                            aria-hidden="true"
                        />
                    </template>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
