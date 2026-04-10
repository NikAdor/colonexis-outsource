<script setup>
import ColonexisLogo from '@/Components/ColonexisLogo.vue';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    variant: {
        type: String,
        default: 'page',
        validator: (v) => ['hero', 'page'].includes(v),
    },
    useHomeAnchors: {
        type: Boolean,
        default: false,
    },
});

const scrolled = ref(props.variant === 'page');
const mobileOpen = ref(false);
const theme = ref('light');

const applyTheme = (mode) => {
    document.documentElement.setAttribute('data-theme', mode);
    try {
        localStorage.setItem('colonexis-theme', mode);
    } catch {
        /* private mode */
    }
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
};

watch(theme, (v) => applyTheme(v));

onMounted(() => {
    let initial = 'light';
    try {
        const stored = localStorage.getItem('colonexis-theme');
        if (stored === 'dark' || stored === 'light') {
            initial = stored;
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            initial = 'dark';
        }
    } catch {
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            initial = 'dark';
        }
    }
    theme.value = initial;
    applyTheme(initial);

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const handleScroll = () => {
    if (props.variant === 'hero') {
        scrolled.value = window.scrollY > 48;
    }
};

const navShellClass = computed(() => {
    if (props.variant === 'page' || scrolled.value) {
        return 'border-b border-border bg-paper/95 shadow-sm backdrop-blur-md';
    }
    return 'border-b border-transparent bg-transparent';
});

const textClass = computed(() => {
    if (props.variant === 'page' || scrolled.value) {
        return 'text-ink hover:text-ink-muted';
    }
    return 'text-hero-ink hover:text-hero-ink';
});

const logoClass = computed(() => {
    if (props.variant === 'page' || scrolled.value) {
        return '';
    }
    return 'brightness-0 invert';
});

const ctaPrimaryClass = computed(() => {
    if (props.variant === 'page' || scrolled.value) {
        return 'bg-accent text-accent-fg hover:bg-accent-hover';
    }
    return 'bg-accent text-accent-fg hover:bg-accent-hover';
});

const navLinkClass = (routeName) => {
    const base = `text-sm font-medium transition-colors ${textClass.value}`;
    if (props.useHomeAnchors || !routeName) {
        return base;
    }
    try {
        if (typeof route === 'function' && route().current(routeName)) {
            return `${base} font-semibold text-accent`;
        }
    } catch {
        /* Ziggy route() unavailable */
    }
    return base;
};

const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
    mobileOpen.value = false;
};

const themeLabel = computed(() => (theme.value === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'));
</script>

<template>
    <header>
        <a href="#main-content" class="ds-skip">Skip to main content</a>
        <nav class="fixed left-0 right-0 top-0 z-50 transition-colors duration-300" :class="navShellClass" aria-label="Primary">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3 sm:px-6 sm:py-4">
                <div class="inline-flex shrink-0" @click="mobileOpen = false">
                    <ColonexisLogo
                        to="/"
                        :img-class="`h-9 w-auto max-w-[200px] object-contain object-left sm:h-10 ${logoClass}`.trim()"
                    />
                </div>

                <div class="hidden items-center justify-center gap-8 md:flex">
                    <template v-if="useHomeAnchors">
                        <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('programs')">
                            Programs
                        </button>
                        <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('about')">
                            Who we serve
                        </button>
                        <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('trust')">
                            Trust
                        </button>
                        <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('stories')">
                            Stories
                        </button>
                        <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('contact')">
                            Contact
                        </button>
                    </template>
                    <template v-else>
                        <Link :href="route('about')" :class="navLinkClass('about')">About</Link>
                        <Link :href="route('services')" :class="navLinkClass('services')">Services</Link>
                        <Link :href="route('courses.index')" :class="navLinkClass('courses.index')">Courses</Link>
                        <Link :href="route('works')" :class="navLinkClass('works')">Works</Link>
                        <Link :href="route('testimonials')" :class="navLinkClass('testimonials')">Testimonials</Link>
                        <Link :href="route('contact')" :class="navLinkClass('contact')">Contact</Link>
                    </template>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        type="button"
                        class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-md border border-border bg-surface px-3 text-sm font-medium text-ink transition-colors hover:bg-surface-raised md:hidden"
                        :aria-expanded="mobileOpen"
                        aria-controls="site-mobile-nav"
                        @click="mobileOpen = !mobileOpen"
                    >
                        <span class="sr-only">{{ mobileOpen ? 'Close menu' : 'Open menu' }}</span>
                        <svg v-if="!mobileOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="hidden min-h-[44px] min-w-[44px] items-center justify-center rounded-md border border-border bg-surface px-3 text-xs font-medium uppercase tracking-wider text-ink transition-colors hover:bg-surface-raised sm:inline-flex"
                        :aria-pressed="theme === 'dark'"
                        :aria-label="themeLabel"
                        @click="toggleTheme"
                    >
                        <span aria-hidden="true">{{ theme === 'dark' ? 'Light' : 'Dark' }}</span>
                    </button>

                    <div class="hidden items-center gap-3 md:flex">
                        <Link v-if="canLogin" :href="route('login')" class="text-sm font-medium transition-colors" :class="textClass">
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md px-4 py-2.5 text-sm font-semibold transition-colors"
                            :class="ctaPrimaryClass"
                        >
                            Get started
                        </Link>
                    </div>
                </div>
            </div>

            <div
                id="site-mobile-nav"
                class="border-t border-border bg-paper md:hidden"
                :hidden="!mobileOpen"
            >
                <div class="mx-auto flex max-w-7xl flex-col gap-1 px-5 py-4 sm:px-6">
                    <template v-if="useHomeAnchors">
                        <button type="button" class="rounded-md px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised" @click="scrollToSection('programs')">
                            Programs
                        </button>
                        <button type="button" class="rounded-md px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised" @click="scrollToSection('about')">
                            Who we serve
                        </button>
                        <button type="button" class="rounded-md px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised" @click="scrollToSection('trust')">
                            Trust
                        </button>
                        <button type="button" class="rounded-md px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised" @click="scrollToSection('stories')">
                            Stories
                        </button>
                        <button type="button" class="rounded-md px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised" @click="scrollToSection('contact')">
                            Contact
                        </button>
                    </template>
                    <template v-else>
                        <Link :href="route('about')" class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised" @click="mobileOpen = false">About</Link>
                        <Link :href="route('services')" class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised" @click="mobileOpen = false">Services</Link>
                        <Link
                            :href="route('courses.index')"
                            class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised"
                            @click="mobileOpen = false"
                        >
                            Courses
                        </Link>
                        <Link :href="route('works')" class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised" @click="mobileOpen = false">Works</Link>
                        <Link :href="route('testimonials')" class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised" @click="mobileOpen = false">Testimonials</Link>
                        <Link :href="route('contact')" class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised" @click="mobileOpen = false">Contact</Link>
                    </template>
                    <div class="mt-2 flex flex-col gap-2 border-t border-border pt-4">
                        <button
                            type="button"
                            class="rounded-md border border-border px-3 py-3 text-left text-sm font-medium text-ink hover:bg-surface-raised"
                            @click="toggleTheme"
                        >
                            {{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}
                        </button>
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-surface-raised"
                            @click="mobileOpen = false"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md px-3 py-3 text-center text-sm font-semibold text-accent-fg"
                            :class="ctaPrimaryClass"
                            @click="mobileOpen = false"
                        >
                            Get started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
