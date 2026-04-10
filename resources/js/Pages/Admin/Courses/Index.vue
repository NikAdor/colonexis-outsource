<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    courses: { type: Object, required: true },
    stats: { type: Object, required: true },
    filters: { type: Object, required: true },
});

const page = usePage();
const authUser = computed(() => page.props?.auth?.user ?? null);

const filterForm = useForm({
    search: props.filters.search ?? '',
    level: props.filters.level ?? 'all',
    status: props.filters.status ?? 'all',
});

const levelOptions = [
    { value: 'all', label: 'All Categories' },
    { value: 'beginner', label: 'Foundations' },
    { value: 'intermediate', label: 'Applied Practice' },
    { value: 'advanced', label: 'Specialist' },
    { value: 'all_levels', label: 'Open Program' },
];

const statusOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'published', label: 'Published' },
    { value: 'draft', label: 'Draft' },
    { value: 'under_review', label: 'Under Review' },
];

const rowIcons = ['code', 'brush', 'analytics'];

function applyFilters() {
    filterForm.get(route('admin.courses.index'), {
        preserveState: true,
        preserveScroll: true,
    });
}

function formatMoney(value) {
    const n = Number(value);
    if (!Number.isFinite(n)) {
        return '$0';
    }
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(n);
}

function formatCompact(n) {
    const num = Number(n);
    if (!Number.isFinite(num)) {
        return '0';
    }
    if (num >= 1_000_000) {
        return `${(num / 1_000_000).toFixed(1)}M`;
    }
    if (num >= 1_000) {
        return `${(num / 1_000).toFixed(1)}k`;
    }
    return String(num);
}

function categoryLabel(level) {
    const row = levelOptions.find((o) => o.value === level);
    return row ? row.label : 'Course';
}

function courseIcon(course) {
    return rowIcons[course.id % rowIcons.length];
}

function courseRowStatus(course) {
    if (course.is_published) {
        return {
            dot: 'bg-green-500',
            text: 'text-green-700',
            label: 'Published',
        };
    }
    if (props.filters.status === 'under_review') {
        return {
            dot: 'bg-blue-500',
            text: 'text-blue-700',
            label: 'Under Review',
        };
    }
    return {
        dot: 'bg-slate-400',
        text: 'text-slate-600',
        label: 'Draft',
    };
}

function viewHref(course) {
    if (course.is_published) {
        return route('courses.show', course.slug);
    }
    return route('admin.courses.edit', course.id);
}

function instructorLabel() {
    return authUser.value?.name || 'Platform';
}

const totalCourses = computed(() => props.courses?.total ?? 0);
const fromRow = computed(() => {
    const c = props.courses;
    if (!c?.from && c?.from !== 0) {
        return 0;
    }
    return c.from;
});
const toRow = computed(() => props.courses?.to ?? 0);
</script>

<template>
    <Head title="Course Management">
        <link
            href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
            rel="stylesheet"
        />
    </Head>

    <DashboardLayout title="Courses">
        <!-- Hero -->
        <section class="mb-10 flex flex-col items-end justify-between gap-6 md:flex-row">
            <div class="max-w-2xl">
                <h2 class="mb-2 font-manrope text-4xl font-extrabold tracking-tighter text-ed-primary">
                    Course Architecture
                </h2>
                <p class="font-body text-lg leading-relaxed text-ed-on-surface-variant">
                    Manage the technical curriculum. From draft states to live production environments, oversee the
                    integrity of every instructional module.
                </p>
            </div>
            <Link
                :href="route('admin.courses.create')"
                class="editorial-gradient flex shrink-0 items-center gap-2 rounded-md px-6 py-3 font-manrope font-bold text-ed-on-primary shadow-lg shadow-ed-primary-container/10 transition-all hover:opacity-90"
            >
                <span class="material-symbols-outlined text-sm">add</span>
                Create New Course
            </Link>
        </section>

        <!-- Stats -->
        <section class="mb-10 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg border-none bg-ed-surface-container-lowest p-6 shadow-sm">
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">
                    Live Courses
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="font-manrope text-3xl font-extrabold text-ed-primary">{{ stats.live_courses }}</span>
                </div>
            </div>
            <div class="rounded-lg border-none bg-ed-surface-container-lowest p-6 shadow-sm">
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">
                    Total Students
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="font-manrope text-3xl font-extrabold text-ed-primary">{{
                        formatCompact(stats.total_enrollments)
                    }}</span>
                </div>
            </div>
            <div class="rounded-lg border-none bg-ed-surface-container-lowest p-6 shadow-sm">
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">
                    Total Revenue
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="font-manrope text-3xl font-extrabold text-ed-primary">{{
                        formatMoney(stats.total_revenue)
                    }}</span>
                </div>
            </div>
            <div class="rounded-lg border-none bg-ed-surface-container-lowest p-6 shadow-sm">
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">
                    Under Review
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="font-manrope text-3xl font-extrabold text-ed-secondary">{{
                        String(stats.draft_courses).padStart(2, '0')
                    }}</span>
                </div>
            </div>
        </section>

        <!-- Filters -->
        <section class="mb-6 flex flex-wrap items-center gap-4 rounded-xl bg-ed-surface-container-low p-4">
            <div class="min-w-[200px] flex-1">
                <label class="mb-1 ml-1 block text-[10px] font-bold uppercase text-ed-on-surface-variant">Search</label>
                <input
                    v-model="filterForm.search"
                    type="text"
                    class="w-full rounded-md border-none bg-ed-surface-container-lowest px-3 py-2 text-sm focus:ring-1 focus:ring-ed-primary/20"
                    placeholder="Filter by name..."
                >
            </div>
            <div class="w-48">
                <label class="mb-1 ml-1 block text-[10px] font-bold uppercase text-ed-on-surface-variant">Category</label>
                <select
                    v-model="filterForm.level"
                    class="w-full rounded-md border-none bg-ed-surface-container-lowest px-3 py-2 text-sm focus:ring-1 focus:ring-ed-primary/20"
                >
                    <option v-for="opt in levelOptions" :key="opt.value" :value="opt.value">
                        {{ opt.label }}
                    </option>
                </select>
            </div>
            <div class="w-40">
                <label class="mb-1 ml-1 block text-[10px] font-bold uppercase text-ed-on-surface-variant">Status</label>
                <select
                    v-model="filterForm.status"
                    class="w-full rounded-md border-none bg-ed-surface-container-lowest px-3 py-2 text-sm focus:ring-1 focus:ring-ed-primary/20"
                >
                    <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                        {{ opt.label }}
                    </option>
                </select>
            </div>
            <div class="w-48">
                <label class="mb-1 ml-1 block text-[10px] font-bold uppercase text-ed-on-surface-variant">Date Range</label>
                <div class="relative">
                    <input
                        type="text"
                        readonly
                        class="w-full cursor-default rounded-md border-none bg-ed-surface-container-lowest px-3 py-2 pr-10 text-sm text-ed-on-surface-variant focus:ring-1 focus:ring-ed-primary/20"
                        placeholder="Last 30 days"
                        aria-hidden="true"
                    >
                    <span class="material-symbols-outlined pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-base text-slate-400">
                        calendar_month
                    </span>
                </div>
            </div>
            <div class="pt-5">
                <button
                    type="button"
                    class="rounded-md bg-ed-primary px-4 py-2 font-manrope text-xs font-bold uppercase tracking-widest text-ed-on-primary transition-colors hover:bg-slate-800"
                    :disabled="filterForm.processing"
                    @click="applyFilters"
                >
                    Apply Filters
                </button>
            </div>
        </section>

        <!-- Empty -->
        <section
            v-if="!courses.data.length"
            class="rounded-sm border border-ed-outline-variant/10 bg-ed-surface-container-lowest px-6 py-16 text-center"
        >
            <p class="font-manrope text-lg font-bold text-ed-primary">
                No courses match these filters
            </p>
            <p class="mt-2 text-sm text-ed-on-surface-variant">
                Create a course or adjust search and filters.
            </p>
            <Link
                :href="route('admin.courses.create')"
                class="editorial-gradient mt-6 inline-flex items-center gap-2 rounded-md px-5 py-2.5 font-manrope text-sm font-bold text-ed-on-primary"
            >
                Create New Course
            </Link>
        </section>

        <!-- Table -->
        <section
            v-else
            class="overflow-hidden rounded-sm border border-ed-outline-variant/10 bg-ed-surface-container-lowest"
        >
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-none bg-ed-surface-container-high">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Course Title
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Instructor
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Category
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Students
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Revenue
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ed-surface-container-low">
                        <tr
                            v-for="c in courses.data"
                            :key="c.id"
                            class="group transition-colors hover:bg-ed-surface-container-low/30"
                        >
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-sm bg-slate-100">
                                        <span class="material-symbols-outlined text-slate-400">{{ courseIcon(c) }}</span>
                                    </div>
                                    <div>
                                        <p
                                            class="font-manrope font-bold text-ed-primary transition-colors group-hover:text-ed-secondary"
                                        >
                                            {{ c.title }}
                                        </p>
                                        <p class="font-body text-xs text-ed-on-surface-variant">
                                            ID: CRS-{{ String(c.id).padStart(4, '0') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-200 font-manrope text-[9px] font-bold text-slate-600 grayscale"
                                    >
                                        {{
                                            String(instructorLabel())
                                                .trim()
                                                .split(/\s+/)
                                                .filter(Boolean)
                                                .slice(0, 2)
                                                .map((w) => w[0])
                                                .join('')
                                                .toUpperCase() || '?'
                                        }}
                                    </div>
                                    <span class="text-sm font-medium text-ed-on-surface">{{ instructorLabel() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span
                                    class="rounded-full bg-ed-tertiary-container px-3 py-1 text-[10px] font-bold uppercase text-ed-on-tertiary-container"
                                >
                                    {{ categoryLabel(c.level) }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-semibold text-ed-primary">{{ c.purchases_count ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-bold text-ed-primary">{{
                                    formatMoney(c.purchases_sum_amount_paid)
                                }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-full" :class="courseRowStatus(c).dot" />
                                    <span class="text-xs font-semibold" :class="courseRowStatus(c).text">{{
                                        courseRowStatus(c).label
                                    }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        :href="viewHref(c)"
                                        class="rounded-md p-2 text-ed-on-surface-variant transition-all hover:bg-ed-surface-container-high hover:text-ed-primary"
                                        :aria-label="c.is_published ? 'View course' : 'Open in editor'"
                                    >
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </Link>
                                    <Link
                                        :href="route('admin.courses.edit', c.id)"
                                        class="rounded-md p-2 text-ed-on-surface-variant transition-all hover:bg-ed-surface-container-high hover:text-ed-primary"
                                        aria-label="Edit course"
                                    >
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </Link>
                                    <Link
                                        :href="route('admin.courses.destroy', c.id)"
                                        method="delete"
                                        as="button"
                                        class="rounded-md p-2 text-ed-on-surface-variant transition-all hover:bg-red-50 hover:text-red-600"
                                        aria-label="Delete course"
                                    >
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col gap-3 border-t border-ed-surface-container-low bg-ed-surface px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="font-body text-xs text-ed-on-surface-variant">
                    Showing
                    <span class="font-bold text-ed-primary">{{ fromRow }}-{{ toRow }}</span>
                    of {{ totalCourses }} courses
                </p>
                <div class="flex flex-wrap items-center gap-1">
                    <template v-for="(link, index) in courses.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            preserve-state
                            preserve-scroll
                            class="flex min-h-8 min-w-8 items-center justify-center rounded p-1.5 text-xs font-bold transition-colors"
                            :class="
                                link.active
                                    ? 'bg-ed-primary text-ed-on-primary'
                                    : 'text-ed-primary hover:bg-ed-surface-container-high'
                            "
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="flex min-h-8 min-w-8 cursor-not-allowed items-center justify-center rounded p-1.5 text-xs font-bold opacity-40"
                            :class="link.active ? 'bg-ed-primary text-ed-on-primary' : 'text-ed-on-surface-variant'"
                            v-html="link.label"
                            aria-hidden="true"
                        />
                    </template>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>

<style scoped>
.editorial-gradient {
    background: linear-gradient(45deg, #000000 0%, #141939 100%);
}
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
