<script setup>
import ColonexisLogo from '@/Components/ColonexisLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const page = usePage();
const sidebarOpen = ref(false);

const authUser = computed(() => page.props?.auth?.user ?? null);
const appName = computed(() => page.props?.appName ?? 'Colonexis');
const isClient = computed(() => authUser.value?.role === 'client');

const roleLabel = computed(() => {
    const r = authUser.value?.role;
    if (r === 'client') return 'Learning hub';
    if (r === 'staff') return 'Operations';
    if (r === 'admin') return 'Institutional control';
    return '';
});

const shellTitle = computed(() => {
    const r = authUser.value?.role;
    if (r === 'admin') return 'Admin console';
    if (r === 'staff') return 'Staff console';
    return 'Your workspace';
});

const showInbox = computed(() => ['admin', 'staff'].includes(authUser.value?.role));
const showUsers = computed(() => authUser.value?.role === 'admin');
const showAdminCatalog = computed(() => authUser.value?.role === 'admin');

const flashSuccess = computed(() => page.props?.flash?.success ?? null);
const flashInfo = computed(() => page.props?.flash?.info ?? null);

function isActive(match) {
    const comp = page.component;

    switch (match) {
        case 'dashboard':
            return comp === 'Dashboard';
        case 'profile.edit':
            return comp === 'Profile/Edit';
        case 'inbox':
            return comp === 'Inbox';
        case 'users.*':
            return typeof comp === 'string' && comp.startsWith('Users/');
        case 'help':
            return comp === 'Help';
        case 'admin.settings':
            return comp === 'Admin/Settings';
        case 'admin.events':
            return typeof comp === 'string' && comp.startsWith('Admin/Events');
        case 'admin.courses':
            return typeof comp === 'string' && comp.startsWith('Admin/Courses');
        default:
            return false;
    }
}

function isTopNavActive(match) {
    switch (match) {
        case 'dashboard':
            return isActive('dashboard');
        case 'users':
            return isActive('users.*');
        case 'settings':
            return isActive('admin.settings');
        case 'inbox':
            return isActive('inbox');
        case 'profile':
            return isActive('profile.edit');
        default:
            return false;
    }
}

function sidebarNavClass(active) {
    return ['admin-sidebar-link', { 'is-active': active }];
}

function topNavClass(active) {
    return ['admin-top-link', { 'is-active': active }];
}

const topNavItems = computed(() => {
    const role = authUser.value?.role;
    const items = [{ label: 'Overview', href: route('dashboard'), match: 'dashboard' }];

    if (role === 'admin') {
        items.push({ label: 'Directory', href: route('users.index'), match: 'users' });
        items.push({ label: 'Settings', href: route('admin.settings.edit'), match: 'settings' });
    } else if (role === 'staff') {
        items.push({ label: 'Inbox', href: route('inbox'), match: 'inbox' });
        items.push({ label: 'Profile', href: route('profile.edit'), match: 'profile' });
    } else {
        items.push({ label: 'Profile', href: route('profile.edit'), match: 'profile' });
    }

    return items;
});

const shellCta = computed(() => {
    const role = authUser.value?.role;
    if (role === 'admin') {
        return { label: 'Generate report', href: route('users.index') };
    }
    if (role === 'staff') {
        return { label: 'Open inbox', href: route('inbox') };
    }
    return { label: 'Get support', href: route('help') };
});

const userInitials = computed(() => {
    const n = authUser.value?.name?.trim() || '?';
    const parts = n.split(/\s+/).filter(Boolean);
    if (parts.length >= 2) {
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    }
    return n.slice(0, 2).toUpperCase();
});
</script>

<template>
    <!-- Student shell (client role) -->
    <div v-if="isClient" class="student-shell">
        <header class="student-shell__topbar">
            <div class="flex items-center gap-8">
                <Link :href="route('dashboard')" class="student-shell__brand">
                    {{ appName }}
                </Link>
                <div class="student-shell__search-wrap">
                    <span class="material-symbols-outlined student-shell__search-icon">search</span>
                    <input class="student-shell__search-input" placeholder="Search architecture..." type="text" />
                </div>
            </div>
            <div class="student-shell__top-actions">
                <button type="button" class="student-shell__notif-btn" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="student-shell__notif-dot" aria-hidden="true" />
                </button>
                <div class="student-shell__profile">
                    <span class="student-shell__profile-label">Profile</span>
                    <Link :href="route('profile.edit')" class="student-shell__avatar" :title="authUser?.name">
                        <span class="sr-only">{{ authUser?.name }}</span>
                    </Link>
                </div>
            </div>
        </header>

        <aside class="student-shell__sidebar" aria-label="Student navigation">
            <div class="student-shell__sidebar-head">
                <h2 class="student-shell__sidebar-title">Architect Academy</h2>
                <p class="student-shell__sidebar-subtitle">Technical Curator</p>
            </div>

            <nav class="student-shell__nav">
                <Link
                    :href="route('dashboard')"
                    class="student-shell__nav-link"
                    :class="{ 'is-active': isActive('dashboard') }"
                >
                    <span class="material-symbols-outlined">school</span>
                    <span class="student-shell__nav-text">My Courses</span>
                </Link>
                <Link :href="route('profile.edit')" class="student-shell__nav-link" :class="{ 'is-active': isActive('profile.edit') }">
                    <span class="material-symbols-outlined">person</span>
                    <span class="student-shell__nav-text">Profile</span>
                </Link>
                <Link :href="route('help')" class="student-shell__nav-link" :class="{ 'is-active': isActive('help') }">
                    <span class="material-symbols-outlined">help</span>
                    <span class="student-shell__nav-text">Help Center</span>
                </Link>
            </nav>

            <div class="student-shell__sidebar-footer">
                <Link :href="route('courses.index')" class="student-shell__primary-btn">
                    <span class="material-symbols-outlined text-sm">add</span>
                    New Course
                </Link>
                <Link :href="route('help')" class="student-shell__help-link">
                    <span class="material-symbols-outlined">help</span>
                    <span class="student-shell__nav-text">Help Center</span>
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="student-shell__help-link">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="student-shell__nav-text">Logout</span>
                </Link>
            </div>
        </aside>

        <main class="student-shell__main">
            <div class="student-shell__container">
                <slot />
            </div>
        </main>
    </div>

    <div v-else class="admin-shell">
        <button
            v-show="sidebarOpen"
            type="button"
            class="admin-shell__overlay"
            aria-label="Close menu"
            @click="sidebarOpen = false"
        />

        <aside
            id="app-sidebar"
            class="admin-shell__sidebar"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="admin-shell__brand-wrap">
                <Link :href="route('dashboard')" class="block" @click="sidebarOpen = false">
                    <ColonexisLogo img-class="h-8 w-auto max-w-[160px] object-contain object-left dark:brightness-0 dark:invert" />
                </Link>
                <h1 class="admin-shell__brand-title">
                    {{ shellTitle }}
                </h1>
                <p class="admin-shell__brand-subtitle">
                    {{ roleLabel }}
                </p>
            </div>

            <nav class="admin-shell__nav" aria-label="Main navigation">
                <Link
                    :href="route('dashboard')"
                    :class="sidebarNavClass(isActive('dashboard'))"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">dashboard</span>
                    Dashboard
                </Link>
                <Link
                    :href="route('profile.edit')"
                    :class="sidebarNavClass(isActive('profile.edit'))"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">person</span>
                    Profile
                </Link>
                <Link
                    v-if="showInbox"
                    :href="route('inbox')"
                    :class="sidebarNavClass(isActive('inbox'))"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">mail</span>
                    Inbox
                </Link>
                <Link
                    v-if="showUsers"
                    :href="route('users.index')"
                    :class="sidebarNavClass(isActive('users.*'))"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">group</span>
                    Users
                </Link>

                <template v-if="showAdminCatalog">
                    <p class="admin-shell__nav-label">
                        Catalog admin
                    </p>
                    <Link
                        :href="route('admin.events.index')"
                        :class="sidebarNavClass(isActive('admin.events'))"
                        @click="sidebarOpen = false"
                    >
                        <span class="material-symbols-outlined text-[22px] leading-none">calendar_month</span>
                        Events
                    </Link>
                    <Link
                        :href="route('admin.courses.index')"
                        :class="sidebarNavClass(isActive('admin.courses'))"
                        @click="sidebarOpen = false"
                    >
                        <span class="material-symbols-outlined text-[22px] leading-none">menu_book</span>
                        Courses
                    </Link>
                    <Link
                        :href="route('admin.settings.edit')"
                        :class="sidebarNavClass(isActive('admin.settings'))"
                        @click="sidebarOpen = false"
                    >
                        <span class="material-symbols-outlined text-[22px] leading-none">settings</span>
                        Site features
                    </Link>
                </template>

                <p class="admin-shell__nav-label">
                    Support
                </p>
                <Link
                    :href="route('help')"
                    :class="sidebarNavClass(isActive('help'))"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">help</span>
                    Help &amp; Support
                </Link>
            </nav>

            <div class="admin-shell__footer">
                <Link
                    :href="shellCta.href"
                    class="admin-shell__cta"
                    @click="sidebarOpen = false"
                >
                    {{ shellCta.label }}
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="admin-shell__logout"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">logout</span>
                    Log out
                </Link>
            </div>
        </aside>

        <div class="admin-shell__content">
            <header
                class="admin-shell__mobile-header"
            >
                <button
                    type="button"
                    class="admin-shell__mobile-menu-btn"
                    aria-controls="app-sidebar"
                    :aria-expanded="sidebarOpen"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open menu</span>
                    <span class="material-symbols-outlined text-[26px] leading-none">menu</span>
                </button>
                <span class="admin-shell__mobile-title">
                    {{ title }}
                </span>
                <div v-if="$slots.mobileToolbar" class="shrink-0">
                    <slot name="mobileToolbar" />
                </div>
            </header>

            <header
                class="admin-shell__desktop-header"
            >
                <div class="flex items-center gap-8">
                    <span class="admin-shell__desktop-appname">
                        {{ appName }}
                    </span>
                    <nav class="hidden gap-6 md:flex" aria-label="Section">
                        <Link
                            v-for="item in topNavItems"
                            :key="item.match"
                            :href="item.href"
                            :class="topNavClass(isTopNavActive(item.match))"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <span class="material-symbols-outlined cursor-pointer text-slate-500 dark:text-slate-400">notifications</span>
                        <span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-ed-secondary" aria-hidden="true" />
                    </div>
                    <Link :href="route('help')" class="text-slate-500 dark:text-slate-400" aria-label="Help">
                        <span class="material-symbols-outlined cursor-pointer">help_outline</span>
                    </Link>
                    <Link
                        :href="route('profile.edit')"
                        class="admin-shell__avatar"
                        :title="authUser?.name"
                    >
                        {{ userInitials }}
                    </Link>
                </div>
            </header>

            <main class="flex-1">
                <div v-if="$slots.header" class="admin-shell__slot-header">
                    <div class="admin-shell__slot-header-inner">
                        <slot name="header" />
                    </div>
                </div>
                <div class="admin-shell__main-inner">
                    <div
                        v-if="flashSuccess"
                        class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/40 dark:text-green-100"
                        role="status"
                    >
                        {{ flashSuccess }}
                    </div>
                    <div
                        v-if="flashInfo"
                        class="mb-6 rounded-lg border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-900 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-100"
                        role="status"
                    >
                        {{ flashInfo }}
                    </div>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
