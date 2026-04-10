<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    users: {
        type: Object,
        default: null,
    },
    siteSettings: {
        type: Object,
        default: () => ({
            events_enabled: false,
            courses_enabled: false,
        }),
    },
    upcomingEvents: {
        type: Array,
        default: () => [],
    },
    catalogCourses: {
        type: Array,
        default: () => [],
    },
    purchasedCourseIds: {
        type: Array,
        default: () => [],
    },
    adminOverview: {
        type: Object,
        default: null,
    },
});

const page = usePage();
const authUser = computed(() => page.props?.auth?.user ?? null);
const isAdmin = computed(() => authUser.value?.role === 'admin');
const isClient = computed(() => authUser.value?.role === 'client');

const clientDisplayName = computed(() => authUser.value?.name?.split(' ')?.[0] ?? 'there');

const clientStats = computed(() => {
    const inProgress = props.purchasedCourseIds?.length ?? 0;
    const completed = 0;
    const certificates = 0;

    return { inProgress, completed, certificates };
});

const recommendedCourses = computed(() => (props.catalogCourses ?? []).slice(0, 4));

function formatCoursePrice(price) {
    const n = Number(price);
    if (n <= 0) return 'Free';
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(n);
}

function formatMoney(amount) {
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(Number(amount) || 0);
}

function formatEventWhen(iso) {
    try {
        return new Date(iso).toLocaleString(undefined, {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    } catch {
        return iso;
    }
}

function eventCardSummary(ev) {
    if (ev.excerpt) {
        return ev.excerpt;
    }
    if (!ev.description) {
        return '';
    }
    const raw = String(ev.description).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
    return raw.length > 220 ? `${raw.slice(0, 220)}…` : raw;
}

function isPurchased(courseId) {
    return props.purchasedCourseIds.some((id) => Number(id) === Number(courseId));
}

function purchaseActionLabel(price) {
    if (Number(price) <= 0) {
        return 'Enroll free';
    }
    const method = String(page.props?.site?.payment_method ?? 'xendit').toLowerCase();
    return method === 'paymongo' ? 'Pay with PayMongo' : 'Pay with Xendit';
}

const courseTableIcons = ['code', 'cloud', 'security'];

function courseTableIcon(index) {
    return courseTableIcons[index % courseTableIcons.length];
}

function courseTableIconBg(index) {
    const tones = ['bg-slate-900', 'bg-slate-800', 'bg-slate-700'];
    return tones[index % tones.length];
}

function statusBadgeClass(status) {
    if (status === 'trending') {
        return 'bg-ed-tertiary-fixed text-ed-on-tertiary-fixed-variant';
    }
    if (status === 'new') {
        return 'bg-ed-primary-fixed text-ed-on-primary-fixed-variant';
    }
    return 'bg-ed-secondary-fixed text-ed-on-secondary-fixed-variant';
}

function statusLabel(status) {
    if (status === 'trending') return 'Trending';
    if (status === 'new') return 'New';
    return 'Stable';
}

function enrollmentStatusLabel(status) {
    if (status === 'success') return 'SUCCESS';
    if (status === 'pending') return 'PENDING';
    return String(status).toUpperCase();
}

function enrollmentStatusClass(status) {
    if (status === 'success') {
        return 'bg-ed-tertiary-fixed text-ed-on-tertiary-fixed';
    }
    if (status === 'pending') {
        return 'bg-ed-secondary-fixed text-ed-on-secondary-fixed';
    }
    return 'bg-ed-surface-container text-ed-on-surface-variant';
}

/** SVG line + area from monthly revenue bar heights (admin chart). */
function revenueChartSvgPaths(chart) {
    if (!chart?.length) {
        return { line: '', area: '' };
    }
    const n = chart.length;
    const padX = 2;
    const padY = 8;
    const w = 100 - padX * 2;
    const h = 100 - padY * 2;
    const pts = chart.map((bar, i) => {
        const x = padX + (n === 1 ? w / 2 : (i / (n - 1)) * w);
        const y = padY + h - (h * bar.actualPct) / 100;
        return { x, y };
    });
    const line = pts.reduce((acc, p, i) => (i === 0 ? `M ${p.x} ${p.y}` : `${acc} L ${p.x} ${p.y}`), '');
    const area = `${line} L ${pts[pts.length - 1].x} ${padY + h} L ${pts[0].x} ${padY + h} Z`;
    return { line, area };
}
</script>

<template>
    <!-- Admin: Editorial analytics console -->
    <template v-if="isAdmin && adminOverview">
        <Head title="Admin Dashboard" />

        <DashboardLayout title="Analytics">
            <div class="admin-analytics">
            <!-- Hero stats -->
            <section class="admin-analytics__stats">
                <div class="admin-analytics__stat-card border-b-4 border-ed-primary">
                    <p class="text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">Gross revenue</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-ed-primary dark:text-white">
                            {{ formatMoney(adminOverview.totalRevenue) }}
                        </h3>
                        <span class="mb-1 text-xs font-bold text-ed-secondary">Live</span>
                    </div>
                </div>
                <div class="admin-analytics__stat-card border-b-4 border-ed-on-primary-container">
                    <p class="text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">Active learners</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-ed-primary dark:text-white">
                            {{ adminOverview.totalStudents.toLocaleString() }}
                        </h3>
                        <span class="mb-1 text-xs font-bold text-ed-secondary">Live</span>
                    </div>
                </div>
                <div class="admin-analytics__stat-card border-b-4 border-ed-secondary">
                    <p class="text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">Enrollment rate</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-ed-primary dark:text-white">
                            {{ adminOverview.enrollmentRate }}%
                        </h3>
                        <span class="mb-1 text-xs font-bold text-ed-on-surface-variant">Clients w/ course</span>
                    </div>
                </div>
                <div class="admin-analytics__stat-card border-b-4 border-ed-tertiary-fixed-dim">
                    <p class="text-xs font-bold uppercase tracking-widest text-ed-on-surface-variant">Active catalog</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-ed-primary dark:text-white">
                            {{ adminOverview.activeCourses }}
                        </h3>
                        <span class="mb-1 text-xs font-bold text-ed-secondary">Published</span>
                    </div>
                </div>
            </section>

            <!-- Bento grid -->
            <section class="admin-analytics__bento">
                <div class="admin-analytics__panel col-span-12 lg:col-span-8">
                    <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="font-manrope text-xl font-extrabold tracking-tight text-ed-on-surface dark:text-white">
                                Revenue trends
                            </h2>
                            <p class="text-sm text-ed-on-surface-variant">Monthly growth from enrollments (10 months)</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="rounded-full bg-ed-surface-container-low px-3 py-1 text-[10px] font-bold dark:bg-white/10">
                                ACTUAL
                            </span>
                            <span class="rounded-full bg-ed-primary px-3 py-1 text-[10px] font-bold text-white dark:bg-white dark:text-ed-primary-container">
                                10 MO
                            </span>
                        </div>
                    </div>
                    <div class="relative flex h-64 w-full items-end gap-1">
                        <div class="pointer-events-none absolute inset-0 flex flex-col justify-between">
                            <div v-for="i in 4" :key="i" class="h-0 w-full border-t border-ed-outline-variant/10" />
                        </div>
                        <svg class="absolute bottom-0 left-0 h-full w-full opacity-25" preserveAspectRatio="none" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="dash-revenue-fill" x1="0%" x2="0%" y1="0%" y2="100%">
                                    <stop offset="0%" stop-color="#335f9b" stop-opacity="0.9" />
                                    <stop offset="100%" stop-color="#141939" stop-opacity="0.35" />
                                </linearGradient>
                            </defs>
                            <path
                                :d="revenueChartSvgPaths(adminOverview.revenueChart).area"
                                fill="url(#dash-revenue-fill)"
                            />
                        </svg>
                        <svg class="absolute bottom-0 left-0 h-full w-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                            <path
                                :d="revenueChartSvgPaths(adminOverview.revenueChart).line"
                                fill="none"
                                stroke="#141939"
                                stroke-width="0.75"
                                class="dark:stroke-white"
                            />
                        </svg>
                        <div
                            class="absolute -bottom-6 flex w-full justify-between text-[10px] font-bold text-ed-on-surface-variant/50"
                        >
                            <span v-for="(bar, idx) in adminOverview.revenueChart" :key="'m-' + idx">{{ bar.label }}</span>
                        </div>
                    </div>
                </div>

                <div class="admin-analytics__panel-dark col-span-12 flex flex-col lg:col-span-4">
                    <h2 class="mb-2 font-manrope text-xl font-extrabold tracking-tight">Course popularity</h2>
                    <p class="mb-8 text-sm text-ed-on-primary-container">Top performing subjects by enrollment</p>
                    <div class="flex flex-grow flex-col justify-center gap-6">
                        <template v-if="adminOverview.topCourses.length">
                            <div v-for="c in adminOverview.topCourses" :key="c.id" class="flex flex-col gap-2">
                                <div class="flex justify-between text-[10px] font-bold tracking-widest">
                                    <span class="truncate pr-2 uppercase">{{ c.title }}</span>
                                    <span>{{ c.enrollmentPct }}%</span>
                                </div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-white/10">
                                    <div
                                        class="h-full bg-ed-secondary-container transition-all"
                                        :style="{ width: `${c.enrollmentPct}%` }"
                                    />
                                </div>
                            </div>
                        </template>
                        <p v-else class="text-sm text-white/70">No course data yet.</p>
                    </div>
                </div>

                <div class="admin-analytics__panel col-span-12 bg-ed-surface-container-low dark:bg-white/5 lg:col-span-5">
                    <h2 class="mb-8 font-manrope text-xl font-extrabold tracking-tight text-ed-on-surface dark:text-white">
                        Engagement core
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="flex flex-col items-center justify-center rounded-lg bg-ed-surface-container-lowest p-6 text-center dark:bg-ed-primary-container/40"
                        >
                            <span class="material-symbols-outlined mb-2 text-4xl text-ed-secondary">timer</span>
                            <span class="text-2xl font-black text-ed-on-surface dark:text-white">42m</span>
                            <span class="text-[10px] font-bold uppercase text-ed-on-surface-variant">Est. session</span>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center rounded-lg bg-ed-surface-container-lowest p-6 text-center dark:bg-ed-primary-container/40"
                        >
                            <span class="material-symbols-outlined mb-2 text-4xl text-ed-secondary">forum</span>
                            <span class="text-2xl font-black text-ed-on-surface dark:text-white">
                                {{ adminOverview.totalEnrollments >= 1000
                                    ? `${(adminOverview.totalEnrollments / 1000).toFixed(1)}k`
                                    : adminOverview.totalEnrollments }}
                            </span>
                            <span class="text-[10px] font-bold uppercase text-ed-on-surface-variant">Enrollments</span>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center rounded-lg bg-ed-surface-container-lowest p-6 text-center dark:bg-ed-primary-container/40"
                        >
                            <span class="material-symbols-outlined mb-2 text-4xl text-ed-secondary">assignment_turned_in</span>
                            <span class="text-2xl font-black text-ed-on-surface dark:text-white">
                                {{ adminOverview.enrollmentRate }}%
                            </span>
                            <span class="text-[10px] font-bold uppercase text-ed-on-surface-variant">Active learners</span>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center rounded-lg bg-ed-surface-container-lowest p-6 text-center dark:bg-ed-primary-container/40"
                        >
                            <span class="material-symbols-outlined mb-2 text-4xl text-ed-secondary">local_library</span>
                            <span class="text-2xl font-black text-ed-on-surface dark:text-white">
                                {{ adminOverview.coursesPerUser }}
                            </span>
                            <span class="text-[10px] font-bold uppercase text-ed-on-surface-variant">Courses / user</span>
                        </div>
                    </div>
                </div>

                <div class="admin-analytics__panel relative col-span-12 overflow-hidden dark:bg-white/5 lg:col-span-7">
                    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                        <div>
                            <h2 class="font-manrope text-xl font-extrabold tracking-tight text-ed-on-surface dark:text-white">
                                Geographic reach
                            </h2>
                            <p class="text-sm text-ed-on-surface-variant">Distribution index (illustrative)</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-ed-primary dark:text-white">Global</div>
                            <div class="text-[10px] font-bold uppercase tracking-tighter text-ed-secondary">Online delivery</div>
                        </div>
                    </div>
                    <div class="flex h-48 flex-col gap-8 sm:flex-row sm:items-center">
                        <div
                            class="h-full w-full rounded-lg bg-gradient-to-br from-ed-surface-container-low to-ed-surface-container sm:w-1/2 dark:from-ed-primary-container/50 dark:to-ed-curator-shell"
                        />
                        <div class="flex w-full flex-col gap-4 sm:w-1/2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full bg-ed-primary dark:bg-white" />
                                    <span class="text-xs font-bold text-ed-on-surface dark:text-slate-200">North America</span>
                                </div>
                                <span class="text-xs font-bold">42%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full bg-ed-secondary" />
                                    <span class="text-xs font-bold text-ed-on-surface dark:text-slate-200">Europe</span>
                                </div>
                                <span class="text-xs font-bold">28%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full bg-ed-tertiary-fixed-dim" />
                                    <span class="text-xs font-bold text-ed-on-surface dark:text-slate-200">Asia Pacific</span>
                                </div>
                                <span class="text-xs font-bold">18%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full bg-ed-outline" />
                                    <span class="text-xs font-bold text-ed-on-surface dark:text-slate-200">Rest of world</span>
                                </div>
                                <span class="text-xs font-bold">12%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recent enrollment feed -->
            <section class="admin-analytics__activity">
                <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <h2 class="font-manrope text-xl font-extrabold tracking-tight text-ed-on-surface dark:text-white">
                        Recent activity log
                    </h2>
                    <Link
                        :href="route('users.index')"
                        class="flex items-center gap-1 text-xs font-bold text-ed-secondary hover:underline"
                    >
                        View audit trail
                        <span class="material-symbols-outlined text-base leading-none">chevron_right</span>
                    </Link>
                </div>
                <div class="flex flex-col">
                    <div
                        class="grid grid-cols-12 border-b border-ed-outline-variant/10 py-4 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant"
                    >
                        <div class="col-span-12 sm:col-span-4">Student</div>
                        <div class="col-span-12 sm:col-span-4">Course enrollment</div>
                        <div class="col-span-6 sm:col-span-2">Value</div>
                        <div class="col-span-6 sm:col-span-2 text-right">Status</div>
                    </div>
                    <template v-if="adminOverview.recentEnrollments?.length">
                        <div
                            v-for="(row, ri) in adminOverview.recentEnrollments"
                            :key="ri"
                            class="grid grid-cols-12 items-center rounded py-5 hover:bg-ed-surface-container-low dark:hover:bg-white/5"
                        >
                            <div class="col-span-12 flex items-center gap-3 sm:col-span-4">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-ed-surface-container-high text-[10px] font-bold text-ed-curator-accent dark:bg-white/10 dark:text-white"
                                >
                                    {{ row.studentName?.charAt(0) ?? '?' }}
                                </div>
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-bold text-ed-on-surface dark:text-white">
                                        {{ row.studentName }}
                                    </div>
                                    <div class="truncate text-[10px] text-ed-on-surface-variant">{{ row.studentEmail }}</div>
                                </div>
                            </div>
                            <div class="col-span-12 truncate text-sm font-medium text-ed-on-surface dark:text-slate-200 sm:col-span-4">
                                {{ row.courseTitle }}
                            </div>
                            <div class="col-span-6 text-sm font-bold text-ed-on-surface dark:text-white sm:col-span-2">
                                {{ formatMoney(row.value) }}
                            </div>
                            <div class="col-span-6 text-right sm:col-span-2">
                                <span
                                    class="inline-block rounded px-2 py-1 text-[10px] font-bold"
                                    :class="enrollmentStatusClass(row.status)"
                                >
                                    {{ enrollmentStatusLabel(row.status) }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <p v-else class="py-10 text-center text-sm text-ed-on-surface-variant">
                        No enrollments yet. Purchases will appear here.
                    </p>
                </div>
            </section>
            </div>

            <!-- Top courses -->
            <section
                class="mt-10 overflow-hidden rounded-[0.25rem] border border-ed-outline-variant/10 bg-ed-surface-container-lowest shadow-sm dark:border-white/10"
            >
                <div class="border-b border-ed-surface-container p-8 dark:border-white/10">
                    <h2 class="font-manrope text-xl font-extrabold text-ed-on-surface dark:text-white">Top performing courses</h2>
                    <p class="text-sm text-ed-on-surface-variant">
                        Based on enrollment count and revenue from purchases.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-ed-surface-container-low/50 text-[10px] font-bold uppercase tracking-widest text-ed-outline"
                            >
                                <th class="px-8 py-4">Course name</th>
                                <th class="px-8 py-4">Students</th>
                                <th class="px-8 py-4">Revenue</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ed-surface-container">
                            <tr v-for="(c, idx) in adminOverview.topCourses" :key="c.id" class="transition-colors hover:bg-ed-surface-container-low/20">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded"
                                            :class="courseTableIconBg(idx)"
                                        >
                                            <span class="material-symbols-outlined text-lg text-white">
                                                {{ courseTableIcon(idx) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-ed-on-surface">{{ c.title }}</p>
                                            <p class="text-xs text-ed-outline">By {{ page.props.auth?.user?.name || 'Studio' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm font-medium text-ed-on-surface">
                                    {{ c.students.toLocaleString() }}
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-ed-primary">
                                    {{ formatMoney(c.revenue) }}
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold"
                                        :class="statusBadgeClass(c.status)"
                                    >
                                        {{ statusLabel(c.status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <Link
                                        :href="route('admin.courses.edit', c.id)"
                                        class="inline-flex text-ed-on-surface-variant transition-colors hover:text-ed-primary"
                                        aria-label="Edit course"
                                    >
                                        <span class="material-symbols-outlined">edit</span>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="!adminOverview.topCourses.length">
                                <td colspan="5" class="px-8 py-10 text-center text-sm text-ed-on-surface-variant">
                                    No courses yet.
                                    <Link :href="route('admin.courses.create')" class="font-bold text-ed-secondary hover:underline">
                                        Create a course
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-ed-surface-container-low/30 p-4 text-center">
                    <Link
                        :href="route('admin.courses.index')"
                        class="text-xs font-bold uppercase tracking-widest text-ed-outline transition-colors hover:text-ed-primary"
                    >
                        View all courses
                    </Link>
                </div>
            </section>

            <!-- Users -->
            <section
                v-if="users"
                class="mt-10 overflow-hidden rounded-[0.25rem] border border-ed-outline-variant/10 bg-ed-surface-container-lowest shadow-sm"
            >
                <div class="flex flex-col gap-4 border-b border-ed-surface-container p-8 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-manrope text-xl font-extrabold text-ed-on-surface">Users</h2>
                        <p class="text-sm text-ed-on-surface-variant">Manage staff, admins, and student accounts.</p>
                    </div>
                    <Link
                        :href="route('users.create')"
                        class="inline-flex justify-center rounded-[0.25rem] bg-ed-primary-container px-5 py-2.5 text-sm font-semibold text-white transition-transform active:scale-95"
                    >
                        Add user
                    </Link>
                </div>
                <div class="overflow-x-auto p-4 sm:p-6">
                    <table class="w-full min-w-[32rem] text-left">
                        <thead>
                            <tr class="border-b border-ed-outline-variant/20 text-xs font-bold uppercase tracking-wider text-ed-outline">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in users.data"
                                :key="row.id"
                                class="border-b border-ed-surface-container/80 transition-colors hover:bg-ed-surface-container-low/30"
                            >
                                <td class="px-4 py-3 font-medium text-ed-on-surface">{{ row.name }}</td>
                                <td class="px-4 py-3 text-sm text-ed-on-surface-variant">{{ row.email }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-semibold capitalize"
                                        :class="{
                                            'bg-ed-tertiary-fixed/40 text-ed-on-tertiary-fixed-variant': row.role === 'admin',
                                            'bg-ed-secondary-fixed/50 text-ed-on-secondary-container': row.role === 'staff',
                                            'bg-ed-surface-container text-ed-on-surface-variant': row.role === 'client',
                                        }"
                                    >
                                        {{ row.role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <Link :href="route('users.edit', row.id)" class="font-semibold text-ed-secondary hover:underline">
                                            Edit
                                        </Link>
                                        <Link
                                            :href="route('users.destroy', row.id)"
                                            method="delete"
                                            as="button"
                                            class="text-ed-on-error-container hover:underline"
                                            :only="['users']"
                                        >
                                            Delete
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="users.links" class="border-t border-ed-surface-container px-6 py-4">
                    <nav class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-ed-on-surface-variant">
                            Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template v-for="(link, index) in users.links" :key="index">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="rounded px-3 py-1 text-sm"
                                    :class="link.active ? 'bg-ed-primary text-white' : 'bg-ed-surface-container-low text-ed-on-surface-variant'"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    v-html="link.label"
                                    class="cursor-not-allowed rounded px-3 py-1 text-sm opacity-50"
                                    :class="link.active ? 'bg-ed-primary text-white' : 'bg-ed-surface-container-low'"
                                    aria-hidden="true"
                                />
                            </template>
                        </div>
                    </nav>
                </div>
            </section>

            <!-- Upcoming events (optional) -->
            <section
                v-if="siteSettings.events_enabled && upcomingEvents.length"
                class="mt-10 overflow-hidden rounded-[0.25rem] border border-ed-outline-variant/10 bg-ed-surface-container-lowest p-8 shadow-sm"
            >
                <h2 class="font-manrope text-xl font-extrabold text-ed-on-surface">Upcoming events</h2>
                <ul class="mt-4 divide-y divide-ed-surface-container">
                    <li
                        v-for="ev in upcomingEvents"
                        :key="ev.id"
                        class="flex flex-col gap-2 py-4 sm:flex-row sm:justify-between"
                    >
                        <div>
                            <Link
                                v-if="ev.slug"
                                :href="route('events.show', ev.slug)"
                                class="font-semibold text-ed-secondary hover:underline"
                            >
                                {{ ev.title }}
                            </Link>
                            <p v-else class="font-semibold">{{ ev.title }}</p>
                            <p v-if="eventCardSummary(ev)" class="mt-1 line-clamp-2 text-sm text-ed-on-surface-variant">
                                {{ eventCardSummary(ev) }}
                            </p>
                        </div>
                        <p class="shrink-0 text-sm text-ed-outline">{{ formatEventWhen(ev.starts_at) }}</p>
                    </li>
                </ul>
            </section>
        </DashboardLayout>
    </template>

    <!-- Everyone else: original dashboard -->
    <template v-else>
        <Head title="Dashboard" />

        <DashboardLayout title="My Courses">
            <!-- Student dashboard (client role) -->
            <div v-if="isClient" class="student-dashboard">
                <section class="student-dashboard__welcome">
                    <h1 class="student-dashboard__title">Welcome back, {{ clientDisplayName }}</h1>
                    <p class="student-dashboard__subtitle">
                        Ready to continue your journey through the architectural blueprints? Your courses and recommendations are waiting.
                    </p>
                </section>

                <div class="student-dashboard__grid">
                    <div class="student-dashboard__stats">
                        <div class="student-dashboard__stat-card">
                            <span class="student-dashboard__stat-label">Courses in progress</span>
                            <div class="student-dashboard__stat-value-row">
                                <span class="student-dashboard__stat-value">{{ clientStats.inProgress }}</span>
                                <span class="material-symbols-outlined text-sky-500">pending</span>
                            </div>
                        </div>
                        <div class="student-dashboard__stat-card">
                            <span class="student-dashboard__stat-label">Completed</span>
                            <div class="student-dashboard__stat-value-row">
                                <span class="student-dashboard__stat-value">{{ clientStats.completed }}</span>
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                            </div>
                        </div>
                        <div class="student-dashboard__stat-card">
                            <span class="student-dashboard__stat-label">Certificates earned</span>
                            <div class="student-dashboard__stat-value-row">
                                <span class="student-dashboard__stat-value">{{ clientStats.certificates }}</span>
                                <span class="material-symbols-outlined text-amber-500">workspace_premium</span>
                            </div>
                        </div>
                    </div>

                    <div class="student-dashboard__hero">
                        <div class="student-dashboard__hero-bg" aria-hidden="true" />
                        <div class="student-dashboard__hero-inner">
                            <div>
                                <span class="student-dashboard__hero-tag">Last accessed: Today</span>
                                <h2 class="student-dashboard__hero-title">Continue learning</h2>
                                <p class="student-dashboard__hero-subtitle">
                                    Jump back into your latest lesson or explore new recommendations tailored for you.
                                </p>
                            </div>
                            <div class="student-dashboard__hero-progress">
                                <div class="student-dashboard__hero-progress-head">
                                    <span class="text-sm font-bold">Keep going</span>
                                    <span class="text-xs text-slate-400">Your next lesson is ready</span>
                                </div>
                                <div class="student-dashboard__hero-track">
                                    <div class="student-dashboard__hero-fill" style="width: 65%" />
                                </div>
                                <Link :href="route('courses.index')" class="student-dashboard__hero-cta">
                                    Continue lesson
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="student-dashboard__row2">
                    <div class="student-dashboard__left">
                        <section>
                            <div class="student-dashboard__section-head">
                                <h3 class="student-dashboard__section-title">Recommended for you</h3>
                                <Link :href="route('courses.index')" class="student-dashboard__section-link">View library</Link>
                            </div>
                            <div class="student-dashboard__recs">
                                <div
                                    v-for="course in recommendedCourses"
                                    :key="course.id"
                                    class="student-dashboard__course-card group"
                                >
                                    <div class="student-dashboard__course-media">
                                        <img
                                            v-if="course.featured_image_url"
                                            :src="course.featured_image_url"
                                            alt=""
                                            class="student-dashboard__course-img"
                                        />
                                    </div>
                                    <span class="student-dashboard__course-pill">Recommended</span>
                                    <h4 class="student-dashboard__course-title">{{ course.title }}</h4>
                                    <p v-if="course.excerpt" class="student-dashboard__course-desc">{{ course.excerpt }}</p>
                                    <div class="student-dashboard__course-foot">
                                        <div class="student-dashboard__course-meta">
                                            <span class="material-symbols-outlined text-sm text-slate-400">schedule</span>
                                            <span class="text-[10px] text-slate-500">Self-paced</span>
                                        </div>
                                        <Link
                                            :href="course.slug ? route('courses.show', course.slug) : route('courses.index')"
                                            class="student-dashboard__course-action"
                                        >
                                            View
                                        </Link>
                                    </div>
                                </div>
                                <p v-if="!recommendedCourses.length" class="text-sm text-slate-500">
                                    No recommendations yet.
                                </p>
                            </div>
                        </section>

                        <section>
                            <h3 class="student-dashboard__section-title mb-6">Recent activity</h3>
                            <div class="student-dashboard__activity">
                                <div class="student-dashboard__activity-row">
                                    <div class="student-dashboard__activity-icon bg-sky-50">
                                        <span class="material-symbols-outlined text-sky-500 text-xl">play_circle</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-900"><span class="font-bold">You started</span> a new lesson</p>
                                        <p class="text-[10px] text-slate-400 mt-1">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="student-dashboard__activity-row">
                                    <div class="student-dashboard__activity-icon bg-green-50">
                                        <span class="material-symbols-outlined text-green-500 text-xl">assignment_turned_in</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-900"><span class="font-bold">Module completed</span></p>
                                        <p class="text-[10px] text-slate-400 mt-1">Yesterday</p>
                                    </div>
                                </div>
                                <div class="student-dashboard__activity-row">
                                    <div class="student-dashboard__activity-icon bg-amber-50">
                                        <span class="material-symbols-outlined text-amber-500 text-xl">comment</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-900"><span class="font-bold">Instructor replied</span> to your question</p>
                                        <p class="text-[10px] text-slate-400 mt-1">2 days ago</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="student-dashboard__right">
                        <div class="student-dashboard__sticky">
                            <div class="student-dashboard__sessions">
                                <div class="student-dashboard__sessions-head">
                                    <span class="material-symbols-outlined text-sky-500">calendar_month</span>
                                    <h3 class="text-lg font-black text-slate-900">Upcoming live sessions</h3>
                                </div>
                                <div class="space-y-6">
                                    <div class="student-dashboard__session">
                                        <p class="student-dashboard__session-eyebrow">Live Workshop • Soon</p>
                                        <h4 class="font-bold text-slate-900">Architectural Review: Banking at Scale</h4>
                                        <p class="student-dashboard__session-meta">with Marcus Thorne</p>
                                    </div>
                                    <div class="student-dashboard__session">
                                        <p class="student-dashboard__session-eyebrow">Q&amp;A Session • Soon</p>
                                        <h4 class="font-bold text-slate-900">Microservices: When to Say No</h4>
                                        <p class="student-dashboard__session-meta">with Elena Rodriguez</p>
                                    </div>
                                </div>
                                <button type="button" class="student-dashboard__sync-btn">Sync to calendar</button>
                            </div>

                            <div class="student-dashboard__newsletter">
                                <h4 class="font-bold text-slate-900 mb-2">Blueprint Theory</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                                    New editorial updates land here. Keep an eye on the next release.
                                </p>
                                <Link :href="route('help')" class="student-dashboard__newsletter-link group">
                                    Read article
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff fallback (non-admin, non-client) -->
            <div v-else class="space-y-8">
                <div class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-display text-2xl font-bold text-ink">
                            Welcome, {{ authUser?.name ?? 'there' }}!
                        </h3>
                        <p class="mt-2 text-ink-muted">
                            Signed in as
                            <span class="font-semibold capitalize text-ink">{{ authUser?.role ?? '—' }}</span>
                        </p>
                    </div>
                </div>

                <div
                    v-if="siteSettings.events_enabled && upcomingEvents.length"
                    class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm"
                >
                    <div class="border-b border-border px-6 py-4 sm:px-8">
                        <h3 class="font-display text-lg font-semibold text-ink">
                            Upcoming events
                        </h3>
                        <p class="mt-1 text-sm text-ink-muted">
                            Next on the calendar
                        </p>
                    </div>
                    <ul class="divide-y divide-border">
                        <li
                            v-for="ev in upcomingEvents"
                            :key="ev.id"
                            class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-start sm:justify-between sm:px-8"
                        >
                            <div class="flex min-w-0 flex-1 gap-4">
                                <img
                                    v-if="ev.featured_image_url"
                                    :src="ev.featured_image_url"
                                    alt=""
                                    class="h-20 w-28 shrink-0 rounded-lg border border-border object-cover"
                                >
                                <div class="min-w-0">
                                    <Link
                                        v-if="ev.slug"
                                        :href="route('events.show', ev.slug)"
                                        class="font-medium text-ink hover:text-accent"
                                    >
                                        {{ ev.title }}
                                    </Link>
                                    <p v-else class="font-medium text-ink">
                                        {{ ev.title }}
                                    </p>
                                    <p v-if="ev.speaker_name" class="mt-0.5 text-sm text-accent">
                                        {{ ev.speaker_name }}
                                    </p>
                                    <p v-if="eventCardSummary(ev)" class="mt-1 line-clamp-3 text-sm text-ink-muted">
                                        {{ eventCardSummary(ev) }}
                                    </p>
                                </div>
                            </div>
                            <div class="shrink-0 text-sm text-ink-muted sm:text-right">
                                <p>{{ formatEventWhen(ev.starts_at) }}</p>
                                <p v-if="ev.location" class="mt-0.5">
                                    {{ ev.location }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div
                    v-if="siteSettings.courses_enabled"
                    class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm"
                >
                    <div class="border-b border-border px-6 py-4 sm:px-8">
                        <h3 class="font-display text-lg font-semibold text-ink">
                            Courses
                        </h3>
                        <p class="mt-1 text-sm text-ink-muted">
                            Enroll in free courses or purchase access — like Udemy, integrated here.
                        </p>
                    </div>
                    <div v-if="!catalogCourses.length" class="px-6 py-10 text-center text-sm text-ink-muted sm:px-8">
                        No published courses yet. Admins can add courses under <span class="font-medium text-ink">Catalog admin → Courses</span>.
                    </div>
                    <div
                        v-else
                        class="grid gap-4 p-6 sm:grid-cols-2 sm:p-8 lg:grid-cols-3"
                    >
                        <article
                            v-for="course in catalogCourses"
                            :key="course.id"
                            class="flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-sm transition-shadow hover:shadow-md"
                        >
                            <div class="aspect-video overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900">
                                <img
                                    v-if="course.featured_image_url"
                                    :src="course.featured_image_url"
                                    alt=""
                                    class="h-full w-full object-cover"
                                >
                                <div
                                    v-else
                                    class="flex h-full min-h-[100px] items-center justify-center px-3 text-center text-xs text-white/55"
                                >
                                    Course
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-if="course.level"
                                        class="rounded-full bg-surface-raised px-2 py-0.5 text-xs font-medium capitalize text-ink-muted"
                                    >
                                        {{ String(course.level).replace('_', ' ') }}
                                    </span>
                                </div>
                                <h4 class="font-display mt-2 text-lg font-semibold leading-snug text-ink">
                                    <Link
                                        v-if="course.slug"
                                        :href="route('courses.show', course.slug)"
                                        class="hover:text-accent"
                                    >
                                        {{ course.title }}
                                    </Link>
                                    <template v-else>{{ course.title }}</template>
                                </h4>
                                <p
                                    v-if="course.subtitle"
                                    class="mt-1 line-clamp-2 text-sm text-accent"
                                >
                                    {{ course.subtitle }}
                                </p>
                                <p
                                    v-else-if="course.excerpt"
                                    class="mt-2 line-clamp-3 flex-1 text-sm text-ink-muted"
                                >
                                    {{ course.excerpt }}
                                </p>
                                <p
                                    v-else-if="course.description"
                                    class="mt-2 line-clamp-3 flex-1 text-sm text-ink-muted"
                                >
                                    {{ String(course.description).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between gap-3 border-t border-border px-5 pb-5 pt-4">
                                <span class="text-sm font-semibold text-accent">{{ formatCoursePrice(course.price) }}</span>
                                <span
                                    v-if="isPurchased(course.id)"
                                    class="rounded-full bg-surface-raised px-3 py-1 text-xs font-medium text-ink-muted"
                                >
                                    Enrolled
                                </span>
                                <Link
                                    v-else
                                    :href="route('courses.purchase', course.id)"
                                    method="post"
                                    as="button"
                                    class="rounded-full bg-accent px-4 py-2 text-xs font-medium text-accent-fg transition-colors hover:bg-accent-hover"
                                >
                                    {{ purchaseActionLabel(course.price) }}
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>

                <div v-if="authUser?.role === 'staff'" class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-display text-lg font-semibold text-ink">Staff workspace</h3>
                        <p class="mt-2 max-w-2xl text-ink-muted">
                            You have access to team tools including Inbox. Use the sidebar to move between sections.
                        </p>
                    </div>
                </div>

                <div v-else-if="authUser?.role !== 'admin'" class="overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-display text-lg font-semibold text-ink">Your account</h3>
                        <p class="mt-2 max-w-2xl text-ink-muted">
                            Welcome to the Colonexis platform. Update your profile or reach out via Help &amp; Support whenever you need assistance.
                        </p>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    </template>
</template>

<!-- icon font styling is global in app.css -->
