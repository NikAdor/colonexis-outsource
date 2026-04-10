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
    return active
        ? 'flex items-center gap-3 py-3 pl-8 pr-4 bg-white text-ed-curator-accent shadow-sm dark:bg-ed-curator-accent dark:text-white rounded-r-full font-manrope font-semibold text-sm transition-transform duration-200'
        : 'flex items-center gap-3 py-3 px-8 text-slate-600 hover:bg-slate-200/50 dark:text-slate-400 dark:hover:bg-white/5 font-manrope font-semibold text-sm transition-transform duration-200 hover:translate-x-1';
}

function topNavClass(active) {
    return active
        ? 'border-b-2 border-ed-curator-accent pb-1 font-bold text-ed-curator-accent transition-all duration-300 dark:border-white dark:text-white'
        : 'font-medium text-slate-500 transition-all duration-300 hover:text-ed-curator-accent dark:text-slate-400 dark:hover:text-white';
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
    <div class="min-h-screen bg-ed-surface font-inter text-ed-on-surface antialiased">
        <button
            v-show="sidebarOpen"
            type="button"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
            aria-label="Close menu"
            @click="sidebarOpen = false"
        />

        <aside
            id="app-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col gap-8 bg-ed-surface-container-low py-6 transition-transform duration-200 ease-out dark:bg-ed-primary-container lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="px-8">
                <Link :href="route('dashboard')" class="block" @click="sidebarOpen = false">
                    <ColonexisLogo img-class="h-8 w-auto max-w-[160px] object-contain object-left dark:brightness-0 dark:invert" />
                </Link>
                <h1 class="mt-4 text-lg font-black tracking-widest text-ed-curator-accent dark:text-ed-surface-container-low">
                    {{ shellTitle }}
                </h1>
                <p class="text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant/60">
                    {{ roleLabel }}
                </p>
            </div>

            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto pr-4" aria-label="Main navigation">
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
                    <p class="mb-1 mt-6 px-8 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant/50">
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

                <p class="mb-1 mt-6 px-8 text-[10px] font-bold uppercase tracking-widest text-ed-on-surface-variant/50">
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

            <div class="mt-auto flex flex-col gap-4 px-8">
                <Link
                    :href="shellCta.href"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-ed-primary-container py-3 text-xs font-bold text-white transition-opacity active:opacity-80 dark:bg-ed-curator-accent"
                    @click="sidebarOpen = false"
                >
                    {{ shellCta.label }}
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center gap-3 py-2 text-left font-manrope text-sm font-semibold text-slate-600 dark:text-slate-400"
                    @click="sidebarOpen = false"
                >
                    <span class="material-symbols-outlined text-[22px] leading-none">logout</span>
                    Log out
                </Link>
            </div>
        </aside>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:ml-64">
            <header
                class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-ed-outline-variant/15 bg-ed-surface/90 px-4 backdrop-blur-xl dark:border-white/10 dark:bg-ed-curator-accent/90 lg:hidden"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-ed-on-surface-variant transition-colors hover:bg-ed-surface-container dark:text-slate-300 dark:hover:bg-white/10"
                    aria-controls="app-sidebar"
                    :aria-expanded="sidebarOpen"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open menu</span>
                    <span class="material-symbols-outlined text-[26px] leading-none">menu</span>
                </button>
                <span class="min-w-0 flex-1 truncate font-manrope text-lg font-bold tracking-tight text-ed-curator-accent dark:text-ed-surface">
                    {{ title }}
                </span>
                <div v-if="$slots.mobileToolbar" class="shrink-0">
                    <slot name="mobileToolbar" />
                </div>
            </header>

            <header
                class="sticky top-0 z-40 hidden h-20 w-full items-center justify-between border-b border-ed-outline-variant/10 bg-ed-surface/80 px-8 font-manrope backdrop-blur-xl dark:border-white/10 dark:bg-ed-curator-accent/85 lg:flex"
            >
                <div class="flex items-center gap-8">
                    <span class="text-xl font-bold tracking-tighter text-ed-curator-accent dark:text-ed-surface">
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
                        class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-ed-outline-variant/20 bg-ed-surface-container-high text-sm font-bold text-ed-curator-accent dark:border-white/20 dark:bg-white/10 dark:text-white"
                        :title="authUser?.name"
                    >
                        {{ userInitials }}
                    </Link>
                </div>
            </header>

            <main class="flex-1">
                <div v-if="$slots.header" class="hidden border-b border-ed-outline-variant/10 bg-ed-surface dark:border-white/10 lg:block">
                    <div class="px-8 py-6">
                        <slot name="header" />
                    </div>
                </div>
                <div class="mx-auto w-full max-w-[1600px] px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
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
