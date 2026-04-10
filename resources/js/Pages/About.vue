<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';

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
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head title="About" />
        <div class="min-h-screen bg-paper text-ink">
        <section ref="heroSection" class="relative flex min-h-[72vh] items-center overflow-hidden bg-hero-bg pt-6">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-hero-bg/95 via-hero-bg/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-hero-bg/85 via-transparent to-hero-bg/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 24 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="max-w-3xl"
                >
                    <p class="text-sm font-medium uppercase tracking-wide text-accent">About</p>
                    <h1 class="mt-4 text-5xl font-bold leading-tight text-hero-ink md:text-6xl lg:text-7xl">
                        A studio built for clarity
                    </h1>
                    <p class="mt-6 max-w-2xl text-xl leading-relaxed text-hero-muted">
                        We combine strategy, design, and engineering to ship digital products that feel refined and perform reliably.
                    </p>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden bg-surface py-24">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-12 lg:gap-14 items-start">
                    <div
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        class="w-full lg:w-[46%] relative"
                    >
                        <div class="relative overflow-hidden rounded-3xl border border-border bg-hero-bg shadow-xl">
                            <img
                                src="https://picsum.photos/seed/colonexis-about-story/900/1100"
                                alt="Team collaboration"
                                class="h-[560px] w-full object-cover opacity-80"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-hero-bg/90 via-hero-bg/30 to-hero-bg/10" />
                            <div class="absolute bottom-7 left-7">
                                <p class="text-4xl font-bold leading-tight text-hero-ink">
                                    Building Tomorrow's
                                </p>
                                <p class="text-4xl font-bold leading-tight text-accent">
                                    Digital World
                                </p>
                            </div>
                        </div>

                        <div class="absolute -top-6 right-5 rounded-2xl border border-border bg-surface px-4 py-3 shadow-lg">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-accent">#1 Agency</div>
                            <div class="text-xs text-ink-muted">2024 Award</div>
                        </div>

                        <div class="absolute -right-6 bottom-10 rounded-3xl bg-gradient-to-b from-accent to-accent-hover px-6 py-5 text-center text-accent-fg shadow-xl">
                            <div class="text-4xl font-bold leading-none">10</div>
                            <div class="mt-1 text-sm text-accent-fg/90">Years of Experience</div>
                        </div>
                    </div>

                    <div
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="0.1"
                        class="w-full lg:w-[54%] pt-2"
                    >
                        <div class="inline-flex items-center rounded-full border border-border bg-surface-raised px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-accent">
                            About Colonexis
                        </div>
                        <h2 class="mt-5 text-4xl font-bold leading-[1.05] text-ink md:text-5xl lg:text-[56px]">
                            Your Central Hub for
                            <span class="text-accent"> Digital Growth</span>
                        </h2>
                        <p class="mt-6 max-w-3xl text-lg leading-relaxed text-ink-muted">
                            Colonexis is a full-service digital solutions provider helping businesses grow, automate, and scale through modern technology. We connect brands to the tools, strategies, and talent they need to thrive in today's competitive landscape.
                        </p>

                        <ul class="mt-7 space-y-3.5">
                            <li
                                v-for="point in storyPoints"
                                :key="point"
                                class="flex items-start gap-3 text-base text-ink md:text-lg"
                            >
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full border border-border-strong text-xs text-accent">✓</span>
                                <span>{{ point }}</span>
                            </li>
                        </ul>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div
                                v-for="item in storyMiniStats"
                                :key="item.label"
                                class="rounded-2xl border border-border bg-surface-raised px-5 py-5 text-center"
                            >
                                <div class="text-3xl font-bold leading-none text-accent">{{ item.value }}</div>
                                <div class="mt-2 text-sm text-ink-muted">{{ item.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden bg-hero-bg py-24">
            <div class="absolute inset-0 bg-gradient-to-b from-hero-bg via-hero-bg to-hero-bg" />
            <div class="absolute -top-24 left-1/2 h-[520px] w-[900px] -translate-x-1/2 rounded-full bg-accent/10 blur-3xl" />

            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="mb-16 text-center text-4xl font-bold text-hero-ink md:text-5xl"
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
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.1s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[0].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[0].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[0].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.45s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[1].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[1].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[1].description }}</div>
                            </div>
                        </div>

                        <!-- Center stack -->
                        <div class="flex flex-col gap-6 md:translate-y-0">
                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.25s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[2].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[2].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[2].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.65s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[3].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[3].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[3].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.95s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[6].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[6].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[6].description }}</div>
                            </div>
                        </div>

                        <!-- Right stack -->
                        <div class="flex flex-col gap-6 md:translate-y-6">
                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.35s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[4].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[4].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[4].description }}</div>
                            </div>

                            <div
                                v-motion
                                :initial="{ opacity: 0, y: 18 }"
                                :visibleOnce="{ opacity: 1, y: 0 }"
                                class="animate-float transform-gpu rounded-2xl bg-surface p-8 shadow-xl"
                                style="animation-delay: 0.75s"
                            >
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent/15">
                                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path :d="coreValues[5].icon" />
                                    </svg>
                                </div>
                                <div class="text-lg font-bold text-ink">{{ coreValues[5].title }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-ink-muted">{{ coreValues[5].description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface py-24">
            <div class="mx-auto max-w-7xl px-6">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="mb-14 text-center text-4xl font-bold text-ink md:text-5xl"
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
                        <div class="mb-6 aspect-[4/5] overflow-hidden rounded-2xl border border-border bg-surface-raised shadow-sm">
                            <img
                                :src="member.image"
                                :alt="member.name"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            />
                        </div>
                        <h3 class="text-xl font-semibold text-ink">{{ member.name }}</h3>
                        <p class="text-accent">{{ member.role }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface-raised py-24">
            <div class="mx-auto max-w-4xl px-6 text-center">
                <h2
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="mb-6 text-4xl font-bold text-ink"
                >
                    Let’s work together
                </h2>
                <p
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    :delay="0.08"
                    class="mb-8 text-lg text-ink-muted"
                >
                    Share your goals—we’ll help you shape the next release.
                </p>
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 rounded-full bg-accent px-8 py-4 font-semibold text-accent-fg shadow-md transition-all hover:-translate-y-0.5 hover:bg-accent-hover"
                >
                    Get in touch
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </section>

        </div>
    </MarketingLayout>
</template>
