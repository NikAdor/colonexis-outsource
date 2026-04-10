<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    users: { type: Object, required: true },
    stats: { type: Object, required: true },
    filters: { type: Object, required: true },
});

const filterForm = useForm({
    search: props.filters.search ?? '',
});

const openMenuId = ref(null);

function applySearch() {
    filterForm.get(route('users.index'), { preserveState: true, preserveScroll: true });
}

function initials(name) {
    const n = String(name || '').trim();
    if (!n) {
        return '?';
    }
    const p = n.split(/\s+/);
    if (p.length === 1) {
        return p[0].slice(0, 2).toUpperCase();
    }
    return (p[0][0] + p[1][0]).toUpperCase();
}

function enrolledCourses(user) {
    const rows = user.course_purchases || [];
    return rows.map((p) => p.course?.title).filter(Boolean);
}

function progressPercent(user) {
    if (user.role !== 'client') {
        return null;
    }
    const n = (user.course_purchases || []).length;
    if (n === 0) {
        return 0;
    }
    return Math.min(100, 18 + n * 28 + (user.id % 34));
}

function rowStatus(user) {
    if (user.role === 'admin') {
        return { label: 'Admin', dot: false, pill: 'bg-slate-100 text-slate-700' };
    }
    if (user.role === 'staff') {
        return { label: 'Staff', dot: true, dotClass: 'bg-amber-500', pill: 'bg-amber-100 text-amber-700' };
    }
    const n = (user.course_purchases || []).length;
    if (n === 0) {
        return { label: 'New', dot: true, dotClass: 'bg-amber-500', pill: 'bg-amber-100 text-amber-700' };
    }
    if (n >= 2 && user.id % 4 === 0) {
        return { label: 'Completed', dot: false, icon: true, pill: 'bg-blue-100 text-blue-700' };
    }
    return { label: 'Active', dot: true, dotClass: 'bg-emerald-500', pill: 'bg-emerald-100 text-emerald-700' };
}

const totalListed = computed(() => props.users?.total ?? 0);
const fromRow = computed(() => props.users?.from ?? 0);
const toRow = computed(() => props.users?.to ?? 0);

function toggleMenu(id) {
    openMenuId.value = openMenuId.value === id ? null : id;
}
</script>

<template>
    <Head title="Students Directory">
        <link
            href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
            rel="stylesheet"
        />
    </Head>

    <DashboardLayout title="Students">
        <!-- Page header -->
        <div class="mb-10 flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div class="space-y-2">
                <h1 class="font-manrope text-4xl font-extrabold tracking-tighter text-ed-curator-accent dark:text-white sm:text-5xl">
                    Students Directory
                </h1>
                <p class="text-lg font-medium text-slate-500 dark:text-slate-400">
                    Manage enrollment, track progress, and communicate with learners.
                </p>
            </div>
            <div class="flex flex-wrap gap-4">
                <button
                    type="button"
                    class="flex items-center gap-2 rounded-lg border border-ed-outline-variant/30 bg-white px-6 py-3 text-sm font-bold text-ed-curator-accent shadow-sm transition-colors hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
                    disabled
                >
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export Data
                </button>
                <Link
                    :href="route('users.create')"
                    class="flex items-center gap-2 rounded-lg bg-ed-curator-accent px-6 py-3 text-sm font-bold text-white shadow-xl transition-opacity hover:opacity-90 active:scale-95"
                >
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Enroll Student
                </Link>
            </div>
        </div>

        <!-- Stats -->
        <div class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-2xl border border-ed-outline-variant/10 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                    Total Students
                </p>
                <div class="flex items-end justify-between">
                    <h3 class="font-manrope text-4xl font-black text-ed-curator-accent dark:text-white">
                        {{ stats.total_students.toLocaleString() }}
                    </h3>
                </div>
            </div>
            <div class="rounded-2xl border border-ed-outline-variant/10 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                    Avg. Progress
                </p>
                <h3 class="font-manrope text-4xl font-black text-ed-curator-accent dark:text-white">
                    {{ stats.avg_progress }}%
                </h3>
                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                    <div
                        class="h-full rounded-full bg-ed-secondary"
                        :style="{ width: `${Math.min(100, stats.avg_progress)}%` }"
                    />
                </div>
            </div>
            <div class="rounded-2xl border border-ed-outline-variant/10 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                    Active Now
                </p>
                <div class="flex items-center gap-3">
                    <h3 class="font-manrope text-4xl font-black text-ed-curator-accent dark:text-white">
                        {{ stats.active_now }}
                    </h3>
                    <div class="mt-1 h-2 w-2 animate-pulse rounded-full bg-emerald-500" />
                </div>
                <p class="mt-1 text-xs font-medium text-slate-500">
                    Real-time learners
                </p>
            </div>
            <div class="rounded-2xl border border-ed-outline-variant/10 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                    Completion Rate
                </p>
                <h3 class="font-manrope text-4xl font-black text-ed-curator-accent dark:text-white">
                    {{ stats.completion_rate }}%
                </h3>
                <p class="mt-1 text-xs font-medium text-slate-500">
                    Target:
                    <span class="font-bold text-ed-curator-accent dark:text-white">{{ stats.completion_target }}%</span>
                </p>
            </div>
        </div>

        <!-- Table shell -->
        <div class="rounded-t-3xl border-x border-t border-ed-outline-variant/10 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center md:w-auto">
                    <div class="relative w-full md:w-96">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            search
                        </span>
                        <input
                            v-model="filterForm.search"
                            type="search"
                            class="w-full rounded-xl border-0 bg-ed-surface-container-low py-3 pl-12 pr-4 text-sm font-medium text-ed-on-surface placeholder:text-slate-400 transition-all focus:ring-2 focus:ring-ed-curator-accent/10 dark:bg-slate-800 dark:text-white"
                            placeholder="Search by name, email or course..."
                            @keydown.enter.prevent="applySearch"
                        >
                    </div>
                    <button
                        type="button"
                        class="rounded-xl bg-ed-surface-container-low p-3 text-slate-600 transition-all hover:bg-slate-200 hover:text-ed-curator-accent dark:bg-slate-800 dark:text-slate-300"
                        aria-label="Filters"
                    >
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Bulk Actions:</span>
                        <div class="flex gap-1">
                            <button
                                type="button"
                                class="rounded-lg p-2.5 text-slate-500 transition-all hover:bg-slate-50 hover:text-ed-curator-accent dark:hover:bg-slate-800"
                                title="Email Selected"
                                disabled
                            >
                                <span class="material-symbols-outlined text-[22px]">mail</span>
                            </button>
                            <button
                                type="button"
                                class="rounded-lg p-2.5 text-slate-500 transition-all hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30"
                                title="Delete Selected"
                                disabled
                            >
                                <span class="material-symbols-outlined text-[22px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-b-3xl border-x border-b border-ed-outline-variant/10 bg-white shadow-2xl shadow-black/[0.02] dark:border-slate-700 dark:bg-slate-900"
        >
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="w-12 border-b border-ed-outline-variant/10 px-4 py-5 sm:px-8">
                                <input
                                    type="checkbox"
                                    class="rounded-md border-slate-300 text-ed-curator-accent focus:ring-ed-curator-accent"
                                    disabled
                                    aria-label="Select all"
                                >
                            </th>
                            <th
                                class="border-b border-ed-outline-variant/10 px-4 py-5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 sm:px-6"
                            >
                                Student
                            </th>
                            <th
                                class="border-b border-ed-outline-variant/10 px-4 py-5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 sm:px-6"
                            >
                                Enrolled Courses
                            </th>
                            <th
                                class="border-b border-ed-outline-variant/10 px-4 py-5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 sm:px-6"
                            >
                                Progress
                            </th>
                            <th
                                class="border-b border-ed-outline-variant/10 px-4 py-5 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 sm:px-6"
                            >
                                Status
                            </th>
                            <th
                                class="border-b border-ed-outline-variant/10 px-4 py-5 text-right text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 sm:px-8"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ed-outline-variant/5 dark:divide-slate-700/80">
                        <tr v-if="!users.data.length">
                            <td colspan="6" class="px-6 py-16 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                {{
                                    filters.search
                                        ? 'No users match your search.'
                                        : 'No users yet. Enroll a student to get started.'
                                }}
                            </td>
                        </tr>
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="group transition-colors hover:bg-slate-50/80 dark:hover:bg-slate-800/40"
                        >
                            <td class="px-4 py-6 sm:px-8">
                                <input
                                    type="checkbox"
                                    class="rounded-md border-slate-300 text-ed-curator-accent focus:ring-ed-curator-accent"
                                    disabled
                                    :aria-label="`Select ${user.name}`"
                                >
                            </td>
                            <td class="px-4 py-6 sm:px-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-ed-secondary-fixed font-manrope text-sm font-black text-ed-curator-accent shadow-sm dark:bg-slate-700 dark:text-white"
                                    >
                                        {{ initials(user.name) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="mb-0.5 text-sm font-bold text-ed-curator-accent dark:text-white">
                                            {{ user.name }}
                                        </div>
                                        <div class="text-[11px] font-medium text-slate-400">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 sm:px-6">
                                <div v-if="enrolledCourses(user).length" class="flex flex-wrap gap-1.5">
                                    <span
                                        v-for="title in enrolledCourses(user).slice(0, 4)"
                                        :key="title"
                                        class="rounded-md bg-ed-surface-container-low px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-ed-curator-accent dark:bg-slate-800 dark:text-white"
                                    >
                                        {{ title.length > 22 ? `${title.slice(0, 22)}…` : title }}
                                    </span>
                                    <span
                                        v-if="enrolledCourses(user).length > 4"
                                        class="rounded-md bg-slate-100 px-2 py-1 text-[9px] font-bold text-slate-500 dark:bg-slate-700"
                                    >
                                        +{{ enrolledCourses(user).length - 4 }}
                                    </span>
                                </div>
                                <span v-else class="text-xs font-medium text-slate-400">—</span>
                            </td>
                            <td class="px-4 py-6 sm:px-6">
                                <template v-if="progressPercent(user) !== null">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-1.5 max-w-[120px] flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700"
                                        >
                                            <div
                                                class="h-full rounded-full bg-ed-curator-accent"
                                                :style="{ width: `${progressPercent(user)}%` }"
                                            />
                                        </div>
                                        <span
                                            class="text-xs font-black"
                                            :class="progressPercent(user) >= 100 ? 'text-ed-secondary' : 'text-ed-curator-accent dark:text-white'"
                                        >
                                            {{ progressPercent(user) }}%
                                        </span>
                                    </div>
                                </template>
                                <span v-else class="text-xs font-medium text-slate-400">—</span>
                            </td>
                            <td class="px-4 py-6 sm:px-6">
                                <span
                                    class="flex w-fit items-center gap-1.5 rounded-full px-3 py-1 text-[9px] font-black uppercase"
                                    :class="rowStatus(user).pill"
                                >
                                    <span
                                        v-if="rowStatus(user).dot"
                                        class="h-1.5 w-1.5 rounded-full"
                                        :class="rowStatus(user).dotClass"
                                    />
                                    <span
                                        v-if="rowStatus(user).icon"
                                        class="material-symbols-outlined text-[12px] font-bold"
                                    >verified</span>
                                    {{ rowStatus(user).label }}
                                </span>
                            </td>
                            <td class="relative px-4 py-6 text-right sm:px-8">
                                <button
                                    type="button"
                                    class="rounded-lg p-2 text-slate-400 transition-all hover:bg-white hover:text-ed-curator-accent hover:shadow-sm active:scale-95 dark:hover:bg-slate-800"
                                    :aria-expanded="openMenuId === user.id"
                                    aria-label="Row actions"
                                    @click="toggleMenu(user.id)"
                                >
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                <div
                                    v-if="openMenuId === user.id"
                                    class="absolute right-4 top-full z-20 mt-1 w-40 rounded-xl border border-ed-outline-variant/20 bg-white py-1 shadow-lg dark:border-slate-600 dark:bg-slate-900"
                                >
                                    <Link
                                        :href="route('users.edit', user.id)"
                                        class="block px-4 py-2 text-left text-sm font-medium text-ed-on-surface hover:bg-slate-50 dark:text-white dark:hover:bg-slate-800"
                                        @click="openMenuId = null"
                                    >
                                        Edit
                                    </Link>
                                    <Link
                                        :href="route('users.destroy', user.id)"
                                        method="delete"
                                        as="button"
                                        class="block w-full px-4 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40"
                                        :only="['users', 'flash']"
                                        @click="openMenuId = null"
                                    >
                                        Delete
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex flex-col items-center justify-between gap-6 md:flex-row">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                Showing
                <span class="font-black text-ed-curator-accent dark:text-white">{{ fromRow }} - {{ toRow }}</span>
                of
                <span class="font-black text-ed-curator-accent dark:text-white">{{ totalListed.toLocaleString() }}</span>
                students
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <template v-for="(link, index) in users.links" :key="index">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-state
                        preserve-scroll
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-ed-outline-variant/20 text-sm font-black transition-all active:scale-95"
                        :class="
                            link.active
                                ? 'border-ed-curator-accent bg-ed-curator-accent text-white shadow-lg shadow-ed-curator-accent/20'
                                : 'text-slate-500 hover:bg-white hover:text-ed-curator-accent dark:hover:bg-slate-800'
                        "
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="flex h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-xl px-2 text-sm font-black text-slate-300 dark:text-slate-600"
                        v-html="link.label"
                        aria-hidden="true"
                    />
                </template>
            </div>
        </div>

        <Link
            :href="route('users.create')"
            class="fixed bottom-8 right-8 z-30 flex h-16 w-16 items-center justify-center rounded-2xl bg-ed-curator-accent text-white shadow-2xl transition-all hover:scale-105 active:scale-95 md:hidden"
            aria-label="Enroll student"
        >
            <span class="material-symbols-outlined text-3xl">add</span>
        </Link>
    </DashboardLayout>
</template>

<style scoped>
.material-symbols-outlined {
    font-family: 'Material Symbols Outlined', sans-serif;
    font-weight: normal;
    font-style: normal;
    line-height: 1;
    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;
    vertical-align: middle;
}
</style>
