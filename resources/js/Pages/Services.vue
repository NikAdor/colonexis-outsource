<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';

defineProps({
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});

const heroSection = ref(null);

const services = [
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>`,
        title: 'Web Development',
        description: 'Custom websites and web applications built for performance, accessibility, and maintainability.',
        features: ['Custom Web Apps', 'E-commerce', 'CMS & APIs', 'Integration'],
    },
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>`,
        title: 'Mobile Apps',
        description: 'Native and cross-platform apps with polished UX and reliable releases.',
        features: ['iOS & Android', 'React Native', 'Offline-ready UX', 'Store releases'],
    },
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>`,
        title: 'UI/UX Design',
        description: 'Research-led design systems, prototypes, and interfaces that feel effortless.',
        features: ['Research', 'Wireframes', 'Prototypes', 'Visual design'],
    },
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>`,
        title: 'Cloud Solutions',
        description: 'Scalable infrastructure, CI/CD, and observability for modern products.',
        features: ['Cloud migration', 'AWS / Azure / GCP', 'DevOps', 'Monitoring'],
    },
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        title: 'Brand Identity',
        description: 'Cohesive visual language that translates across web, product, and print.',
        features: ['Logo & marks', 'Guidelines', 'Art direction', 'Launch assets'],
    },
    {
        icon: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>`,
        title: 'Growth & Analytics',
        description: 'Measurement, experimentation, and content that compounds over time.',
        features: ['SEO foundations', 'Analytics', 'Content', 'Reporting'],
    },
];

const servicesColA = computed(() => services.filter((_, i) => i % 2 === 0));
const servicesColB = computed(() => services.filter((_, i) => i % 2 === 1));

const process = [
    { step: '01', title: 'Discovery', description: 'Goals, constraints, users, and success metrics—aligned up front.' },
    { step: '02', title: 'Strategy', description: 'A practical roadmap with milestones you can plan around.' },
    { step: '03', title: 'Design', description: 'High-fidelity UI, motion, and prototypes that de-risk build.' },
    { step: '04', title: 'Build', description: 'Clean engineering, reviews, and performance budgets.' },
    { step: '05', title: 'Launch', description: 'Release planning, QA, and a calm go-live checklist.' },
    { step: '06', title: 'Evolve', description: 'Iteration, monitoring, and improvements after launch.' },
];
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head title="Services" />
        <div class="min-h-screen bg-paper text-ink">
        <section ref="heroSection" class="relative flex min-h-[72vh] items-center overflow-hidden bg-hero-bg pt-6">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-hero-bg/95 via-hero-bg/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-hero-bg/85 via-transparent to-hero-bg/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-wide text-accent">Services</p>
                    <h1 class="mt-4 text-5xl font-bold leading-tight text-hero-ink md:text-6xl lg:text-7xl">What we deliver</h1>
                    <p class="mt-6 max-w-2xl text-xl leading-relaxed text-hero-muted">
                        End-to-end digital capabilities—from first sketch to production systems—with a premium, minimalist touch.
                    </p>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden bg-surface-raised py-24">
            <div class="pointer-events-none absolute -top-16 right-0 h-96 w-96 rounded-full bg-accent/10 blur-3xl" />
            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1 flex flex-col gap-6">
                        <div
                            v-for="(service, index) in servicesColA"
                            :key="service.title"
                            v-motion
                            :initial="{ opacity: 0, y: 22 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="index * 0.08"
                            class="rounded-2xl border border-border bg-surface p-8 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                        >
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-surface-raised text-accent" v-html="service.icon" />
                            <h3 class="mb-4 text-xl font-semibold text-ink">{{ service.title }}</h3>
                            <p class="mb-6 leading-relaxed text-ink-muted">{{ service.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="feature in service.features" :key="feature" class="flex items-center gap-2 text-sm text-ink-muted">
                                    <span class="h-1.5 w-1.5 rounded-full bg-accent" />
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col gap-6 lg:mt-8">
                        <div
                            v-for="(service, index) in servicesColB"
                            :key="service.title"
                            v-motion
                            :initial="{ opacity: 0, y: 22 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.08 + index * 0.08"
                            class="rounded-2xl border border-border bg-surface p-8 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                        >
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-surface-raised text-accent" v-html="service.icon" />
                            <h3 class="mb-4 text-xl font-semibold text-ink">{{ service.title }}</h3>
                            <p class="mb-6 leading-relaxed text-ink-muted">{{ service.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="feature in service.features" :key="feature" class="flex items-center gap-2 text-sm text-ink-muted">
                                    <span class="h-1.5 w-1.5 rounded-full bg-accent" />
                                    {{ feature }}
                                </li>
                            </ul>
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
                    How we work
                </h2>
                <div class="flex flex-col gap-8 md:flex-row md:flex-wrap">
                    <div
                        v-for="(item, index) in process"
                        :key="item.step"
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.06"
                        class="min-w-[240px] flex-1 rounded-2xl border border-border bg-surface p-6 shadow-sm transition-shadow hover:shadow-md"
                    >
                        <div class="mb-2 text-6xl font-bold text-accent/35">{{ item.step }}</div>
                        <h3 class="mb-3 text-xl font-semibold text-ink">{{ item.title }}</h3>
                        <p class="leading-relaxed text-ink-muted">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface-raised py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-col items-center gap-16 lg:flex-row">
                    <div v-motion :initial="{ opacity: 0, y: 22 }" :visibleOnce="{ opacity: 1, y: 0 }" class="flex-1">
                        <h2 class="mb-6 text-4xl font-bold text-ink">Why teams choose us</h2>
                        <p class="mb-8 text-lg leading-relaxed text-ink-muted">
                            We ship with clarity: tight communication, predictable timelines, and engineering you can extend later without regret.
                        </p>
                        <div class="space-y-4">
                            <div v-for="label in ['Senior-led delivery', 'Performance & accessibility', 'Post-launch partnership']" :key="label" class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full border border-border bg-surface shadow-sm">
                                    <svg class="h-5 w-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="font-medium text-ink">{{ label }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-motion :initial="{ opacity: 0, y: 22 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.1" class="w-full flex-1">
                        <div class="aspect-video overflow-hidden rounded-3xl border border-border bg-surface-raised shadow-xl">
                            <img
                                src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=900&h=600&fit=crop"
                                alt="Collaboration"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface py-24">
            <div class="mx-auto max-w-4xl px-6 text-center">
                <h2 v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" class="mb-6 text-4xl font-bold text-ink">
                    Ready to start?
                </h2>
                <p v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.08" class="mb-8 text-lg text-ink-muted">
                    Tell us what you’re building—we’ll propose a focused plan.
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
