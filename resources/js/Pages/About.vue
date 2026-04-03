<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import SiteHeader from '@/Components/SiteHeader.vue';

defineProps({
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});

const heroSection = ref(null);
const statsSection = ref(null);
const countedStats = ref(false);

const stats = ref([
    { value: 8, suffix: '+', label: 'Years Experience' },
    { value: 150, suffix: '+', label: 'Projects Delivered' },
    { value: 80, suffix: '+', label: 'Happy Clients' },
    { value: 12, suffix: '', label: 'Awards Won' },
]);

const animatedStats = ref(stats.value.map(() => ref(0)));

const team = [
    { name: 'Alex Rivera', role: 'Creative Director', image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop&crop=face' },
    { name: 'Sarah Chen', role: 'Lead Developer', image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=500&fit=crop&crop=face' },
    { name: 'Marcus Johnson', role: 'UI/UX Designer', image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop&crop=face' },
    { name: 'Emily Park', role: 'Project Manager', image: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=500&fit=crop&crop=face' },
];

const coreValues = [
    {
        title: 'Be Consistent',
        description: 'Deliver on time with dependable systems and clean execution, every release.',
        icon: 'M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4z',
    },
    {
        title: 'Take Ownership',
        description: 'You own the outcome. We act like partners—proactive, accountable, and clear.',
        icon: 'M12 12V2m0 10c-4 0-7 3-7 7h14c0-4-3-7-7-7z',
    },
    {
        title: 'Focus on Details',
        description: 'Pay attention to what matters—small decisions that shape a premium experience.',
        icon: 'M4 7h16M7 4v16M10 11h4',
    },
    {
        title: 'Treat Like Family',
        description: 'People matter. We communicate early, iterate together, and respect your team.',
        icon: 'M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zM12 12c-2.33 0-4.5 1.15-6 3 1.5 1.85 3.67 3 6 3s4.5-1.15 6-3c-1.5-1.85-3.67-3-6-3z',
    },
    {
        title: 'Have Integrity',
        description: 'Do the right thing when no one is watching—build trust, earn it, keep it.',
        icon: 'M12 2l8 4v6c0 5-3 9-8 10-5-1-8-5-8-10V6l8-4z',
    },
    {
        title: 'Be Adaptable',
        description: 'Improve and adjust to make ourselves and things better, with momentum.',
        icon: 'M12 6v6l4 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
    },
    {
        title: 'Make an Impact',
        description: 'Look for opportunities to create measurable value for teams and communities.',
        icon: 'M12 2l9 5-9 5-9-5 9-5zm0 10v10',
    },
];

const storyPoints = [
    'Full-service digital solutions under one roof',
    'Agile methodology with transparent process',
    'Dedicated account manager for every client',
    'Data-driven strategies with measurable ROI',
];

const storyMiniStats = [
    { value: '10+', label: 'Years of Excellence' },
    { value: '50+', label: 'Expert Team Members' },
    { value: '30+', label: 'Countries Served' },
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

const startCounting = () => {
    if (countedStats.value) return;
    countedStats.value = true;
    stats.value.forEach((stat, index) => {
        animateValue(index, 0, stat.value, 2000);
    });
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
            { threshold: 0.35 }
        );
        statsObserver.observe(statsSection.value);
    }
});

onUnmounted(() => {
    statsObserver?.disconnect();
});
</script>

<template>
    <Head title="About" />
    <div class="min-h-screen bg-white text-gray-900">
        <SiteHeader :can-login="canLogin" :can-register="canRegister" variant="page" />

        <section
            ref="heroSection"
            class="relative min-h-[72vh] flex items-center pt-24 overflow-hidden bg-[#020308]"
        >
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/85 via-transparent to-[#020308]/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 24 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="max-w-3xl"
                >
                    <p class="text-sm font-medium tracking-wide text-[#38BDF8]/90 uppercase">About</p>
                    <h1 class="mt-4 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">
                        A studio built for clarity
                    </h1>
                    <p class="mt-6 text-xl text-white/70 max-w-2xl leading-relaxed">
                        We combine strategy, design, and engineering to ship digital products that feel refined and perform reliably.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-12 lg:gap-14 items-start">
                    <div
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        class="w-full lg:w-[46%] relative"
                    >
                        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-[#E8EEF8] bg-[#0D2B5C]">
                            <img
                                src="https://picsum.photos/seed/colonexis-about-story/900/1100"
                                alt="Team collaboration"
                                class="w-full h-[560px] object-cover opacity-80"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#041228]/85 via-[#041228]/25 to-[#041228]/15" />
                            <div class="absolute left-7 bottom-7">
                                <p class="text-4xl leading-tight font-bold text-white">
                                    Building Tomorrow's
                                </p>
                                <p class="text-4xl leading-tight font-bold text-[#38BDF8]">
                                    Digital World
                                </p>
                            </div>
                        </div>

                        <div class="absolute right-5 -top-6 bg-white rounded-2xl border border-[#E8EEF8] shadow-lg px-4 py-3">
                            <div class="text-[11px] uppercase tracking-wide text-[#19427D] font-semibold">#1 Agency</div>
                            <div class="text-xs text-gray-500">2024 Award</div>
                        </div>

                        <div class="absolute -right-6 bottom-10 bg-gradient-to-b from-[#2F64D7] to-[#38BDF8] text-white rounded-3xl shadow-xl px-6 py-5 text-center">
                            <div class="text-4xl font-bold leading-none">10</div>
                            <div class="text-sm mt-1 text-white/90">Years of Experience</div>
                        </div>
                    </div>

                    <div
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="0.1"
                        class="w-full lg:w-[54%] pt-2"
                    >
                        <div class="inline-flex items-center rounded-full border border-[#D6E4F8] bg-[#F7FAFF] px-4 py-1.5 text-xs font-semibold tracking-wide text-[#19427D] uppercase">
                            About Colonexis
                        </div>
                        <h2 class="mt-5 text-4xl md:text-5xl lg:text-[56px] leading-[1.05] font-bold text-[#041228]">
                            Your Central Hub for
                            <span class="text-[#2F64D7]"> Digital Growth</span>
                        </h2>
                        <p class="mt-6 text-gray-600 text-lg leading-relaxed max-w-3xl">
                            Colonexis is a full-service digital solutions provider helping businesses grow, automate, and scale through modern technology. We connect brands to the tools, strategies, and talent they need to thrive in today's competitive landscape.
                        </p>

                        <ul class="mt-7 space-y-3.5">
                            <li
                                v-for="point in storyPoints"
                                :key="point"
                                class="flex items-start gap-3 text-[#041228] text-base md:text-lg"
                            >
                                <span class="mt-1 inline-flex items-center justify-center w-5 h-5 rounded-full border border-[#7EA9F3] text-[#2F64D7] text-xs">✓</span>
                                <span>{{ point }}</span>
                            </li>
                        </ul>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div
                                v-for="item in storyMiniStats"
                                :key="item.label"
                                class="rounded-2xl bg-[#F8FAFD] border border-[#EEF2F7] px-5 py-5 text-center"
                            >
                                <div class="text-3xl font-bold text-[#2F64D7] leading-none">{{ item.value }}</div>
                                <div class="mt-2 text-sm text-gray-600">{{ item.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#041228] relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-[#020308] via-[#041228] to-[#020308]" />
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[900px] h-[520px] bg-[#38BDF8]/10 rounded-full blur-3xl" />

            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="text-4xl md:text-5xl font-bold text-white text-center mb-16"
                >
                    Core values
                </h2>

                <div class="relative max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <!-- Left stack (floating) -->
                        <div class="flex flex-col gap-6 md:translate-y-4">
                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.1s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[0].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[0].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[0].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.45s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[1].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[1].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[1].description }}</div>
                            </div>
                        </div>

                        <!-- Center stack -->
                        <div class="flex flex-col gap-6 md:translate-y-0">
                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.25s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[2].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[2].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[2].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.65s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[3].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[3].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[3].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.95s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[6].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[6].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[6].description }}</div>
                            </div>
                        </div>

                        <!-- Right stack -->
                        <div class="flex flex-col gap-6 md:translate-y-6">
                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.35s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[4].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[4].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[4].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="bg-white rounded-2xl p-8 shadow-xl transform-gpu animate-float"
                                style="animation-delay: 0.75s"
                            >
                                <div class="w-10 h-10 rounded-full bg-[#38BDF8]/15 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 text-[#38BDF8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[5].icon" />
                                    </svg>
                                </div>
                                <div class="text-[#041228] font-bold text-lg">{{ coreValues[5].title }}</div>
                                <div class="mt-2 text-sm text-gray-600 leading-relaxed">{{ coreValues[5].description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="text-4xl md:text-5xl font-bold text-[#041228] text-center mb-14"
                >
                    Meet the team
                </h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-10 justify-center">
                    <div
                        v-for="(member, index) in team"
                        :key="member.name"
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.08"
                        class="w-full sm:w-[calc(50%-1.25rem)] lg:w-[calc(25%-1.5rem)] max-w-sm mx-auto"
                    >
                        <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-[#F1F1F1] mb-6 border border-gray-100 shadow-sm">
                            <img
                                :src="member.image"
                                :alt="member.name"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            />
                        </div>
                        <h3 class="text-xl font-semibold text-[#041228]">{{ member.name }}</h3>
                        <p class="text-[#38BDF8]">{{ member.role }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1]">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="text-4xl font-bold text-[#041228] mb-6"
                >
                    Let’s work together
                </h2>
                <p
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    :delay="0.08"
                    class="text-gray-600 text-lg mb-8"
                >
                    Share your goals—we’ll help you shape the next release.
                </p>
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-[#38BDF8] text-[#041228] font-semibold rounded-full hover:bg-[#19427D] hover:text-white transition-all hover:-translate-y-0.5 shadow-md"
                >
                    Get in touch
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </section>

        <SiteFooter />
    </div>
</template>
