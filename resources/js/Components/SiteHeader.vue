<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
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
    /** 'hero' = transparent over dark hero until scroll; 'page' = always solid bar aligned with inner content */
    variant: {
        type: String,
        default: 'page',
        validator: (v) => ['hero', 'page'].includes(v),
    },
    /** Homepage: in-page anchor scroll. Other pages: Inertia route links */
    useHomeAnchors: {
        type: Boolean,
        default: false,
    },
});

const scrolled = ref(props.variant === 'page');

const handleScroll = () => {
    if (props.variant === 'hero') {
        scrolled.value = window.scrollY > 50;
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navShellClass = computed(() => {
    if (props.variant === 'page') {
        return 'bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100/80';
    }
    return scrolled.value ? 'bg-white/95 backdrop-blur-md shadow-sm' : 'bg-transparent';
});

const textClass = computed(() => {
    if (props.variant === 'page') {
        return 'text-[#041228] hover:text-gray-600';
    }
    return scrolled.value ? 'text-[#041228] hover:text-gray-600' : 'text-white/90 hover:text-white';
});

const logoClass = computed(() => {
    if (props.variant === 'page') {
        return '';
    }
    return scrolled.value ? '' : 'brightness-0 invert';
});

const ctaPrimaryClass = computed(() => {
    if (props.variant === 'page') {
        return 'bg-[#19427D] text-white hover:bg-[#041228]';
    }
    return scrolled.value ? 'bg-[#19427D] text-white hover:bg-[#041228]' : 'bg-[#38BDF8] text-[#041228] hover:bg-white';
});

const navLinkClass = (routeName) => {
    const base = `text-sm font-medium transition-colors ${textClass.value}`;
    if (props.useHomeAnchors || !routeName) {
        return base;
    }
    try {
        if (typeof route === 'function' && route().current(routeName)) {
            return `${base} font-semibold text-[#19427D]`;
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
};
</script>

<template>
    <header>
        <nav
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
            :class="navShellClass"
        >
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between gap-4">
                    <Link href="/" class="inline-flex items-center shrink-0">
                        <img
                            src="/img/Colonexis-logo.png"
                            alt="Colonexis"
                            class="h-10 w-auto"
                            :class="logoClass"
                        />
                    </Link>

                    <div class="hidden md:flex items-center justify-center flex-1 gap-8">
                        <template v-if="useHomeAnchors">
                            <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('about')">
                                About
                            </button>
                            <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('services')">
                                Services
                            </button>
                            <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('works')">
                                Works
                            </button>
                            <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('testimonials')">
                                Testimonials
                            </button>
                            <button type="button" class="text-sm font-medium transition-colors" :class="textClass" @click="scrollToSection('contact')">
                                Contact
                            </button>
                        </template>
                        <template v-else>
                            <Link :href="route('about')" :class="navLinkClass('about')">About</Link>
                            <Link :href="route('services')" :class="navLinkClass('services')">Services</Link>
                            <Link :href="route('works')" :class="navLinkClass('works')">Works</Link>
                            <Link :href="route('testimonials')" :class="navLinkClass('testimonials')">Testimonials</Link>
                            <Link :href="route('contact')" :class="navLinkClass('contact')">Contact</Link>
                        </template>
                    </div>

                    <div class="flex items-center justify-end gap-4 shrink-0">
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="text-sm font-medium transition-colors"
                            :class="textClass"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-5 py-2.5 rounded-full text-sm font-medium transition-colors"
                            :class="ctaPrimaryClass"
                        >
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
