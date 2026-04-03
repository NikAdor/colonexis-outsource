<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import SiteHeader from '@/Components/SiteHeader.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const heroSection = ref(null);
const statsSection = ref(null);
const countedStats = ref(false);

const stats = ref([
    { value: 500, suffix: '+', label: 'Projects Delivered' },
    { value: 120, suffix: '+', label: 'Happy Clients' },
    { value: 15, suffix: '+', label: 'Years Experience' },
    { value: 50, suffix: '+', label: 'Team Members' },
]);

const animatedStats = ref(stats.value.map(() => ref(0)));

const services = [
    {
        title: 'Web Development',
        description: 'Custom web applications built with modern technologies',
        icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
    },
    {
        title: 'Mobile Apps',
        description: 'Native and cross-platform mobile solutions',
        icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    },
    {
        title: 'UI/UX Design',
        description: 'Beautiful interfaces with exceptional user experience',
        icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
    },
    {
        title: 'Cloud Solutions',
        description: 'Scalable infrastructure and deployment services',
        icon: 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
    },
];

const testimonials = [
    {
        quote: 'Working with this team was an absolute game-changer for our business. They delivered beyond our expectations.',
        author: 'Sarah Johnson',
        role: 'CEO, TechStart Inc.',
    },
    {
        quote: 'The attention to detail and commitment to quality is unmatched. Highly recommend their services.',
        author: 'Michael Chen',
        role: 'CTO, InnovateCo',
    },
    {
        quote: 'Professional, responsive, and incredibly talented. They transformed our vision into reality.',
        author: 'Emily Rodriguez',
        role: 'Founder, DesignHub',
    },
];

const portfolio = [
    { title: 'E-Commerce Platform', category: 'Web Development' },
    { title: 'Finance Dashboard', category: 'UI/UX Design' },
    { title: 'Social Media App', category: 'Mobile Development' },
    { title: 'Analytics Platform', category: 'Cloud Solutions' },
    { title: 'Healthcare Portal', category: 'Web Development' },
    { title: 'Travel Booking App', category: 'Mobile Development' },
    { title: 'Education Learning Hub', category: 'Web Development' },
    { title: 'Smart Inventory System', category: 'Cloud Solutions' },
    { title: 'Real Estate Listing Portal', category: 'UI/UX Design' },
    { title: 'Fitness Companion App', category: 'Mobile Development' },
    { title: 'Music Streaming Experience', category: 'Mobile Development' },
    { title: 'Event Ticketing Platform', category: 'Web Development' },
];

const servicesLeft = computed(() => services.slice(0, 2));
const servicesRight = computed(() => services.slice(2));

const aboutHighlights = [
    {
        title: 'Clarity first',
        description: 'We translate your goals into a focused plan, then build toward measurable outcomes.',
        icon: 'M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        title: 'Design that performs',
        description: 'Premium UI/UX with fast loading, accessible interactions, and a polished feel.',
        icon: 'M4 13h6l2-2 6 6H4v-4zm8-8h8v8h-8V5z',
    },
    {
        title: 'Built to scale',
        description: 'Clean architecture, thoughtful tooling, and reliable delivery across devices.',
        icon: 'M7 17l10-10M17 17V7H7',
    },
    {
        title: 'Long-term support',
        description: 'We stay available after launch with iterative improvements and updates.',
        icon: 'M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z',
    },
];

let statsObserver = null;

const animateValue = (statIndex, start, end, duration) => {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        animatedStats.value[statIndex].value = Math.floor(easeOutQuart * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
};

onMounted(() => {
    if (statsSection.value) {
        statsObserver = new IntersectionObserver(
            (entries) => {
                if (entries.some((e) => e.isIntersecting)) {
                    startCounting();
                    statsObserver?.disconnect();
                }
            },
            { threshold: 0.4 }
        );
        statsObserver.observe(statsSection.value);
    }
    nextTick(() => {
        setupWorksGallery();
    });
});

onUnmounted(() => {
    statsObserver?.disconnect();
    worksTrigger?.kill();
});

const startCounting = () => {
    if (countedStats.value) return;
    countedStats.value = true;

    stats.value.forEach((stat, index) => {
        animateValue(index, 0, stat.value, 2000);
    });
};

const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};

const contactForm = ref({
    name: '',
    email: '',
    phone: '',
    company: '',
    service: '',
    message: '',
});

const isSubmitting = ref(false);
const isSubmitted = ref(false);

const contactServices = computed(() => services.map((s) => s.title));

const handleContactSubmit = async () => {
    isSubmitting.value = true;
    await new Promise((resolve) => setTimeout(resolve, 1400));
    isSubmitting.value = false;
    isSubmitted.value = true;
};

const worksPin = ref(null);
const worksViewport = ref(null);
const worksTrack = ref(null);
const worksActiveIndex = ref(0);

let worksTrigger = null;
let worksMaxTranslate = 0;
let worksSetX = null;

// Keep the pinned cards "locked" for the first part of the scrub,
// then start the horizontal gallery motion.
const worksTranslateLockProgress = 0.12;

const recalcWorks = () => {
    if (!worksViewport.value || !worksTrack.value) return 0;
    const viewportW = worksViewport.value.clientWidth;
    const trackW = worksTrack.value.scrollWidth;
    worksMaxTranslate = Math.max(0, trackW - viewportW);
    return worksMaxTranslate;
};

const setupWorksGallery = () => {
    if (!worksPin.value || !worksViewport.value || !worksTrack.value) return;

    gsap.registerPlugin(ScrollTrigger);

    worksTrigger?.kill();
    worksTrigger = null;

    recalcWorks();
    worksSetX = worksSetX ?? gsap.quickSetter(worksTrack.value, 'x', 'px');
    gsap.set(worksTrack.value, { x: 0, force3D: true });

    worksTrigger = ScrollTrigger.create({
        trigger: worksPin.value,
        start: 'top top',
        end: () => `+=${Math.max(1, recalcWorks())}`,
        scrub: true,
        pin: worksPin.value,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        onRefresh: () => {
            recalcWorks();
            worksSetX?.(0);
        },
        onUpdate: (self) => {
            const p = self.progress;
            const t =
                p <= worksTranslateLockProgress
                    ? 0
                    : Math.min(1, (p - worksTranslateLockProgress) / (1 - worksTranslateLockProgress));

            worksSetX?.(-worksMaxTranslate * t);
            worksActiveIndex.value = Math.round(t * Math.max(0, portfolio.length - 1));
        },
    });
};

const onWorksImageLoad = () => {
    // When images finish, widths can change -> recompute translation distance.
    worksTrigger?.refresh();
};
</script>

<template>
    <Head title="Colonexis" />
    <div class="min-h-screen bg-white text-gray-900">

        <SiteHeader
            :can-login="canLogin"
            :can-register="canRegister"
            variant="hero"
        />

        <section
            ref="heroSection"
            class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-[#020308]"
        >
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div
                class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/55 to-transparent"
            />
            <div
                class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/80 via-transparent to-[#020308]/40"
            />

            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    <div
                        v-motion
                        :initial="{ opacity: 0, x: -50 }"
                        :visibleOnce="{ opacity: 1, x: 0 }"
                        :delay="0.05"
                        class="flex-1"
                    >
                        <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold leading-none tracking-tight text-white">
                            Let's build
                            <br />
                            something
                            <br />
                            <span class="text-white/35">great</span>
                        </h1>
                        <p class="mt-8 text-lg text-white/70 max-w-md">
                            We craft digital experiences that inspire, engage, and deliver results. From concept to launch, we're your partner in innovation.
                        </p>
                        <div class="mt-10 flex flex-wrap items-center gap-4" v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.15">
                            <button
                                @click="scrollToSection('contact')"
                                class="bg-[#38BDF8] text-[#041228] px-8 py-4 rounded-full font-medium transition-all hover:bg-white hover:-translate-y-0.5 hover:shadow-lg"
                            >
                                Start a Project
                            </button>
                            <button
                                @click="scrollToSection('works')"
                                class="border border-white/25 text-white px-8 py-4 rounded-full font-medium transition-all hover:border-[#38BDF8] hover:bg-[#38BDF8]/15 hover:-translate-y-0.5"
                            >
                                View Our Work
                            </button>
                        </div>
                    </div>
                    <div
                        v-motion
                        :initial="{ opacity: 0, x: 60 }"
                        :visibleOnce="{ opacity: 1, x: 0 }"
                        :delay="0.2"
                        class="relative hidden lg:block flex-1 min-h-[420px]"
                    >
                        <div class="absolute -top-6 left-10 rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md shadow-2xl animate-float">
                            <div class="text-4xl font-bold text-white">2026</div>
                            <div class="text-sm text-white/55 mt-1">Fresh launches</div>
                        </div>
                        <div class="absolute -bottom-6 -right-2 rounded-3xl bg-[#38BDF8]/15 border border-[#38BDF8]/30 p-5 backdrop-blur-md shadow-xl animate-float">
                            <div class="text-white font-semibold">Premium delivery</div>
                            <div class="text-white/60 text-sm mt-1">Design + engineering</div>
                        </div>

                        <img
                            src="https://picsum.photos/seed/colonexis-hero/1200/900"
                            alt="Design studio"
                            class="w-full h-full object-cover rounded-3xl border border-white/10 shadow-2xl animate-float"
                        />
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="py-24 bg-white relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-16 -right-24 w-80 h-80 bg-[#38BDF8]/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-[28rem] h-[28rem] bg-[#041228]/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1">
                        <h2
                            v-motion
                            :initial="{ opacity: 0, y: 20 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.05"
                            class="text-4xl md:text-5xl font-bold text-[#041228]"
                        >
                            About Us
                        </h2>

                        <p
                            v-motion
                            :initial="{ opacity: 0, y: 20 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.1"
                            class="mt-5 text-gray-600 text-lg leading-relaxed max-w-xl"
                        >
                            We craft premium digital experiences with a minimalist approach: clear strategy, elegant design, and reliable engineering.
                        </p>

                        <div class="mt-10 space-y-4">
                            <div
                                v-for="(item, index) in aboutHighlights"
                                :key="item.title"
                                v-motion
                                :initial="{ opacity: 0, y: 20 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                :delay="0.12 + index * 0.08"
                                class="flex items-start gap-3 rounded-2xl border border-gray-100 bg-white p-4 hover:-translate-y-0.5 transition-all duration-300 shadow-sm"
                            >
                                <div class="w-11 h-11 rounded-xl bg-[#38BDF8]/15 flex items-center justify-center text-[#38BDF8] flex-shrink-0">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path :d="item.icon" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-[#041228]">{{ item.title }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ item.description }}</div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-motion
                            :initial="{ opacity: 0, y: 20 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.45"
                            class="mt-10 flex flex-wrap items-center gap-4"
                        >
                            <button
                                @click="scrollToSection('services')"
                                class="bg-[#38BDF8] text-[#041228] px-8 py-4 rounded-full font-medium transition-all hover:bg-[#19427D] hover:text-white hover:-translate-y-0.5 hover:shadow-lg"
                            >
                                Explore Services
                            </button>
                            <button
                                @click="scrollToSection('works')"
                                class="border border-[#041228]/10 text-[#041228] px-8 py-4 rounded-full font-medium transition-all hover:border-[#38BDF8] hover:bg-[#38BDF8]/10 hover:-translate-y-0.5"
                            >
                                View Works
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 relative w-full">
                        <div class="absolute -top-6 right-6 rounded-3xl bg-[#041228] text-white p-6 shadow-2xl backdrop-blur-md animate-float">
                            <div class="text-3xl font-bold text-[#38BDF8]">98%</div>
                            <div class="text-sm text-white/70 mt-1">Delivery confidence</div>
                        </div>

                        <div class="absolute -bottom-10 left-0 rounded-3xl border border-gray-100 bg-white/80 p-6 backdrop-blur-md shadow-sm animate-float">
                            <div class="text-sm font-semibold text-[#041228]">User experience</div>
                            <div class="text-sm text-gray-600 mt-1">Refined interactions</div>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#38BDF8]"></span>
                                <span class="w-2 h-2 rounded-full bg-[#38BDF8]/60"></span>
                                <span class="w-2 h-2 rounded-full bg-[#38BDF8]/30"></span>
                            </div>
                        </div>

                        <div
                            v-motion
                            :initial="{ opacity: 0, y: 20 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.15"
                            class="relative w-full h-[520px] rounded-3xl overflow-hidden border border-gray-100 shadow-xl bg-[#F1F1F1]"
                        >
                            <img
                                src="https://picsum.photos/seed/colonexis-about-workspace/1200/900"
                                alt="Design workspace"
                                class="w-full h-full object-cover animate-float"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#041228]/60 via-transparent to-transparent"></div>
                            <div class="absolute left-6 bottom-6">
                                <div class="text-white/80 text-sm">Design Systems</div>
                                <div class="text-white text-2xl font-bold mt-1">Clean, modern UI</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="py-24 bg-[#F1F1F1] relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-10 left-0 w-72 h-72 bg-[#38BDF8]/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#041228]/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="0.05"
                        class="text-4xl md:text-5xl font-bold text-[#041228]"
                    >
                        What We Do
                    </h2>
                    <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                        Premium minimalist delivery—strategy, design, and engineering built for long-term results.
                    </p>
                </div>

                <div class="relative">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="flex-1 flex flex-col gap-6">
                            <div
                                v-for="(service, index) in servicesLeft"
                                :key="service.title"
                                v-motion
                                :initial="{ opacity: 0, y: 22 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                :delay="index * 0.08"
                                class="service-card bg-white rounded-2xl p-8 hover:-translate-y-0.5 transition-all duration-300 hover:shadow-xl border border-white/60"
                            >
                                <div class="service-icon w-20 h-20 bg-[#38BDF8]/20 rounded-full flex items-center justify-center transition-transform duration-300">
                                    <svg class="w-8 h-8 text-[#38BDF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="service.icon" />
                                    </svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold text-[#041228]">{{ service.title }}</h3>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ service.description }}</p>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col gap-6 lg:mt-6">
                            <div
                                v-for="(service, index) in servicesRight"
                                :key="service.title"
                                v-motion
                                :initial="{ opacity: 0, y: 22 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                :delay="0.08 + index * 0.08"
                                class="service-card bg-white rounded-2xl p-8 hover:-translate-y-0.5 transition-all duration-300 hover:shadow-xl border border-white/60"
                            >
                                <div class="service-icon w-20 h-20 bg-[#38BDF8]/20 rounded-full flex items-center justify-center transition-transform duration-300">
                                    <svg class="w-8 h-8 text-[#38BDF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="service.icon" />
                                    </svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold text-[#041228]">{{ service.title }}</h3>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ service.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="0.05"
                        class="text-4xl md:text-5xl font-bold text-[#041228]"
                    >
                        Testimonials
                    </h2>
                    <p class="mt-4 text-gray-600">What our partners say about working with us</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                    <div
                        v-for="(testimonial, index) in testimonials"
                        :key="testimonial.author"
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.1"
                        class="group flex-1 bg-[#F1F1F1]/50 rounded-2xl p-8 hover:-translate-y-0.5 transition-all duration-300 hover:shadow-xl border border-white/60"
                    >
                        <svg class="w-10 h-10 text-[#38BDF8]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                        <p class="mt-6 text-gray-700 leading-relaxed text-lg">“{{ testimonial.quote }}”</p>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="font-semibold text-[#041228]">{{ testimonial.author }}</div>
                            <div class="text-sm text-gray-500">{{ testimonial.role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section
            ref="statsSection"
            class="py-24 bg-[#041228] text-white"
        >
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:flex-wrap gap-8">
                    <div
                        v-for="(stat, index) in stats"
                        :key="index"
                        v-motion
                        :initial="{ opacity: 0, y: 18 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.08"
                        class="text-center flex-1 min-w-[160px]"
                    >
                        <div class="text-5xl md:text-6xl font-bold text-[#38BDF8]">
                            {{ animatedStats[index]?.value }}{{ stat.suffix }}
                        </div>
                        <div class="mt-3 text-gray-300">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section
            id="works"
            ref="worksPin"
            class="bg-[#F1F1F1] relative overflow-hidden"
        >
            <div class="mx-auto px-6 pt-24 pb-10">
                <div class="text-center mb-14">
                    <h2
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="0.05"
                        class="text-4xl md:text-5xl font-bold text-[#041228]"
                    >
                        Selected Work
                    </h2>
                    <p class="mt-4 text-gray-600">
                        Scroll to glide through snapshots (horizontal motion, refined microinteractions).
                    </p>
                </div>

                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <span class="text-[#38BDF8] font-semibold">{{ worksActiveIndex + 1 }}</span>
                    <span>/</span>
                    <span>{{ portfolio.length }}</span>
                </div>
                <div
                    ref="worksViewport"
                    class="mt-10 overflow-hidden h-[min(58vh,520px)]"
                >
                    <div
                        ref="worksTrack"
                        class="flex gap-6 h-full items-stretch will-change-transform [backface-visibility:hidden]"
                    >
                        <article
                            v-for="(item, index) in portfolio"
                            :key="item.title"
                            class="group portfolio-item relative shrink-0 rounded-3xl overflow-hidden cursor-pointer bg-white border border-white/70 shadow-sm transition-[box-shadow] duration-300 hover:shadow-lg"
                            :style="{ width: 'min(460px, 78vw)' }"
                        >
                            <img
                                :src="`https://picsum.photos/seed/work-${index}/1100/850`"
                                :alt="item.title"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
                                @load="onWorksImageLoad"
                            />

                            <div
                                class="portfolio-overlay absolute inset-0 bg-black/70 opacity-0 transition-opacity duration-300 flex items-center justify-center"
                            >
                                <div class="text-center text-white p-6">
                                    <div class="text-sm font-medium text-gray-300 mb-2">
                                        {{ item.category }}
                                    </div>
                                    <h3 class="text-xl font-semibold">
                                        {{ item.title }}
                                    </h3>
                                </div>
                            </div>

                            <div class="absolute left-5 top-5">
                                <div class="text-white/80 text-sm font-semibold">
                                    {{ String(index + 1).padStart(2, '0') }}
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-24 bg-[#F1F1F1]">
            <div class="max-w-4xl mx-auto px-6 text-center relative">
                <div class="absolute -top-10 left-0 w-72 h-72 bg-[#38BDF8]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-10 right-0 w-96 h-96 bg-[#041228]/5 rounded-full blur-3xl pointer-events-none"></div>

                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    :delay="0.05"
                    class="relative text-4xl md:text-5xl font-bold text-[#041228]"
                >
                    Contact Us
                </h2>
                <p
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    :delay="0.1"
                    class="relative mt-6 text-gray-600 text-lg"
                >
                    Tell us what you’re building. We’ll respond with next steps and a clear plan. (Demo form: no data is sent.)
                </p>

                <div
                    v-motion
                    :initial="{ opacity: 0, y: 22 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    :delay="0.18"
                    class="relative mt-12 bg-white rounded-2xl shadow-sm p-8 md:p-12 border border-white/70"
                >
                    <div v-if="isSubmitted" class="text-center py-8 md:py-12">
                        <div class="w-16 h-16 bg-[#F1F1F1] rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-[#38BDF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-[#041228] mb-3">Thank you!</h3>
                        <p class="text-gray-600 mb-6">
                            Your message is ready for the team. This is a demo submission, so nothing was sent.
                        </p>
                        <button
                            @click="isSubmitted = false"
                            class="px-6 py-3 border border-[#041228] text-[#041228] rounded-full hover:bg-[#041228] hover:text-white transition-colors"
                        >
                            Send Another Message
                        </button>
                    </div>

                    <form v-else @submit.prevent="handleContactSubmit" class="space-y-6 text-left">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Name *</label>
                                <input
                                    v-model="contactForm.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="Your name"
                                />
                            </div>
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Email *</label>
                                <input
                                    v-model="contactForm.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="your@email.com"
                                />
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Phone</label>
                                <input
                                    v-model="contactForm.phone"
                                    type="tel"
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="+1 (555) 000-0000"
                                />
                            </div>
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Company</label>
                                <input
                                    v-model="contactForm.company"
                                    type="text"
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="Your company"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#041228] text-sm font-medium mb-2">Service Interested In</label>
                            <select
                                v-model="contactForm.service"
                                class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                            >
                                <option value="">Select a service</option>
                                <option v-for="service in contactServices" :key="service" :value="service" class="bg-white">
                                    {{ service }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[#041228] text-sm font-medium mb-2">Message *</label>
                            <textarea
                                v-model="contactForm.message"
                                rows="5"
                                required
                                class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors resize-none"
                                placeholder="Tell us about your project..."
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="w-full py-4 bg-[#38BDF8] text-[#041228] font-semibold rounded-lg hover:bg-[#19427D] hover:text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <svg v-if="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isSubmitting ? 'Sending...' : 'Send Message' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <SiteFooter :use-home-anchors="true" />
    </div>
</template>
