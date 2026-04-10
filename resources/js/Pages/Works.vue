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

const categories = ['All', 'Web Development', 'Mobile App', 'UI/UX Design', 'Brand Identity'];

const projects = [
    {
        title: 'FinTech Dashboard',
        category: 'Web Development',
        image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
        description: 'Financial analytics with real-time data visualization and role-based access.',
        client: 'Northline Finance',
    },
    {
        title: 'HealthTrack App',
        category: 'Mobile App',
        image: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=600&fit=crop',
        description: 'Health tracking with reminders, trends, and a calm, accessible UI.',
        client: 'Vital Labs',
    },
    {
        title: 'E-Commerce Platform',
        category: 'Web Development',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop',
        description: 'High-conversion storefront with inventory workflows and analytics.',
        client: 'Harbor Retail',
    },
    {
        title: 'TravelBuddy',
        category: 'Mobile App',
        image: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
        description: 'Trip planning with saved routes, budgets, and offline-friendly maps.',
        client: 'Wander Studio',
    },
    {
        title: 'Brand Redesign',
        category: 'Brand Identity',
        image: 'https://images.unsplash.com/photo-1634942537034-2531766767d1?w=800&h=600&fit=crop',
        description: 'Identity system, typography, and launch assets for a product-led brand.',
        client: 'Arc Foundry',
    },
    {
        title: 'SaaS Dashboard',
        category: 'UI/UX Design',
        image: 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800&h=600&fit=crop',
        description: 'Complex B2B workflows simplified into a clear, scalable dashboard.',
        client: 'CloudBase',
    },
];

const activeCategory = ref('All');

const filteredProjects = computed(() => {
    if (activeCategory.value === 'All') return projects;
    return projects.filter((p) => p.category === activeCategory.value);
});

const projectColA = computed(() => filteredProjects.value.filter((_, i) => i % 2 === 0));
const projectColB = computed(() => filteredProjects.value.filter((_, i) => i % 2 === 1));
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head title="Works" />
        <div class="min-h-screen bg-paper text-ink">
        <section ref="heroSection" class="relative flex min-h-[72vh] items-center overflow-hidden bg-hero-bg pt-6">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-hero-bg/95 via-hero-bg/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-hero-bg/85 via-transparent to-hero-bg/35" />
            <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-20">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-wide text-accent">Portfolio</p>
                    <h1 class="mt-4 text-5xl font-bold leading-tight text-hero-ink md:text-6xl lg:text-7xl">Selected work</h1>
                    <p class="mt-6 max-w-2xl text-xl leading-relaxed text-hero-muted">
                        Case-style snapshots—hover thumbnails for a quick read on scope and outcome.
                    </p>
                </div>
            </div>
        </section>

        <section class="border-b border-border bg-surface py-12">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-wrap justify-center gap-3">
                    <button
                        v-for="category in categories"
                        :key="category"
                        type="button"
                        @click="activeCategory = category"
                        class="rounded-full px-6 py-3 text-sm font-medium transition-all duration-300"
                        :class="
                            activeCategory === category
                                ? 'bg-hero-bg text-hero-ink shadow-md'
                                : 'bg-surface-raised text-ink hover:-translate-y-0.5 hover:bg-accent/15'
                        "
                    >
                        {{ category }}
                    </button>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden bg-surface-raised py-24">
            <div class="pointer-events-none absolute -bottom-24 left-0 h-96 w-96 rounded-full bg-hero-bg/5 blur-3xl" />
            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <div class="hidden lg:flex gap-6">
                    <div class="flex-1 flex flex-col gap-6">
                        <div
                            v-for="(project, index) in projectColA"
                            :key="project.title + project.client"
                            v-motion
                            :initial="{ opacity: 0, y: 22 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="index * 0.08"
                            class="group relative overflow-hidden rounded-2xl border border-border bg-surface shadow-sm transition-all duration-300 hover:shadow-xl"
                        >
                            <div class="portfolio-item relative aspect-[4/3] overflow-hidden cursor-pointer">
                                <img
                                    :src="project.image"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    class="portfolio-overlay absolute inset-0 bg-black/70 opacity-0 transition-opacity duration-300 flex items-center justify-center"
                                >
                                    <div class="text-center text-white p-6 max-w-md">
                                        <p class="text-sm text-white/75 mb-2">{{ project.description }}</p>
                                        <p class="text-xs text-white/50">Client: {{ project.client }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-1 text-sm font-medium text-accent">{{ project.category }}</div>
                                <h3 class="text-xl font-semibold text-ink">{{ project.title }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col gap-6 lg:mt-8">
                        <div
                            v-for="(project, index) in projectColB"
                            :key="project.title + project.client + '-b'"
                            v-motion
                            :initial="{ opacity: 0, y: 22 }"
                            :visibleOnce="{ opacity: 1, y: 0 }"
                            :delay="0.08 + index * 0.08"
                            class="group relative overflow-hidden rounded-2xl border border-border bg-surface shadow-sm transition-all duration-300 hover:shadow-xl"
                        >
                            <div class="portfolio-item relative aspect-[4/3] overflow-hidden cursor-pointer">
                                <img
                                    :src="project.image"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    class="portfolio-overlay absolute inset-0 bg-black/70 opacity-0 transition-opacity duration-300 flex items-center justify-center"
                                >
                                    <div class="text-center text-white p-6 max-w-md">
                                        <p class="text-sm text-white/75 mb-2">{{ project.description }}</p>
                                        <p class="text-xs text-white/50">Client: {{ project.client }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-1 text-sm font-medium text-accent">{{ project.category }}</div>
                                <h3 class="text-xl font-semibold text-ink">{{ project.title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:hidden flex flex-col gap-6">
                    <div
                        v-for="(project, index) in filteredProjects"
                        :key="project.title + index"
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.06"
                        class="overflow-hidden rounded-2xl border border-border bg-surface shadow-sm"
                    >
                        <div class="portfolio-item relative aspect-[4/3] overflow-hidden cursor-pointer">
                            <img :src="project.image" :alt="project.title" class="w-full h-full object-cover transition-transform duration-500" />
                            <div class="portfolio-overlay absolute inset-0 bg-black/70 opacity-0 transition-opacity duration-300 flex items-center justify-center">
                                <div class="text-center text-white p-6 text-sm">{{ project.description }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-1 text-sm font-medium text-accent">{{ project.category }}</div>
                            <h3 class="mb-2 text-xl font-semibold text-ink">{{ project.title }}</h3>
                            <p class="text-sm text-ink-muted">{{ project.description }}</p>
                            <p class="mt-3 text-sm text-ink-muted">Client: {{ project.client }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface py-24">
            <div class="mx-auto max-w-4xl px-6 text-center">
                <h2 v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" class="mb-6 text-4xl font-bold text-ink">
                    Have a project in mind?
                </h2>
                <p v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.08" class="mb-8 text-lg text-ink-muted">
                    Share a brief—we’ll suggest a sensible first milestone.
                </p>
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 rounded-full bg-accent px-8 py-4 font-semibold text-accent-fg shadow-md transition-all hover:-translate-y-0.5 hover:bg-accent-hover"
                >
                    Let’s talk
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </section>

        </div>
    </MarketingLayout>
</template>
