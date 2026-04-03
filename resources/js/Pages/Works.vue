<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import SiteHeader from '@/Components/SiteHeader.vue';

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
    <Head title="Works" />
    <div class="min-h-screen bg-white text-gray-900">
        <SiteHeader :can-login="canLogin" :can-register="canRegister" variant="page" />

        <section ref="heroSection" class="relative min-h-[72vh] flex items-center pt-24 overflow-hidden bg-[#020308]">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/85 via-transparent to-[#020308]/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium tracking-wide text-[#38BDF8]/90 uppercase">Portfolio</p>
                    <h1 class="mt-4 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">Selected work</h1>
                    <p class="mt-6 text-xl text-white/70 max-w-2xl leading-relaxed">
                        Case-style snapshots—hover thumbnails for a quick read on scope and outcome.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-wrap justify-center gap-3">
                    <button
                        v-for="category in categories"
                        :key="category"
                        type="button"
                        @click="activeCategory = category"
                        class="px-6 py-3 rounded-full text-sm font-medium transition-all duration-300"
                        :class="
                            activeCategory === category
                                ? 'bg-[#041228] text-white shadow-md'
                                : 'bg-[#F1F1F1] text-[#041228] hover:bg-[#38BDF8]/20 hover:-translate-y-0.5'
                        "
                    >
                        {{ category }}
                    </button>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1] relative overflow-hidden">
            <div class="absolute -bottom-24 left-0 w-96 h-96 bg-[#041228]/5 rounded-full blur-3xl pointer-events-none" />
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
                            class="group relative overflow-hidden rounded-2xl bg-white border border-white/60 shadow-sm hover:shadow-xl transition-all duration-300"
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
                                <div class="text-[#38BDF8] text-sm font-medium mb-1">{{ project.category }}</div>
                                <h3 class="text-xl font-semibold text-[#041228]">{{ project.title }}</h3>
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
                            class="group relative overflow-hidden rounded-2xl bg-white border border-white/60 shadow-sm hover:shadow-xl transition-all duration-300"
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
                                <div class="text-[#38BDF8] text-sm font-medium mb-1">{{ project.category }}</div>
                                <h3 class="text-xl font-semibold text-[#041228]">{{ project.title }}</h3>
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
                        class="rounded-2xl bg-white border border-white/60 shadow-sm overflow-hidden"
                    >
                        <div class="portfolio-item relative aspect-[4/3] overflow-hidden cursor-pointer">
                            <img :src="project.image" :alt="project.title" class="w-full h-full object-cover transition-transform duration-500" />
                            <div class="portfolio-overlay absolute inset-0 bg-black/70 opacity-0 transition-opacity duration-300 flex items-center justify-center">
                                <div class="text-center text-white p-6 text-sm">{{ project.description }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-[#38BDF8] text-sm font-medium mb-1">{{ project.category }}</div>
                            <h3 class="text-xl font-semibold text-[#041228] mb-2">{{ project.title }}</h3>
                            <p class="text-gray-600 text-sm">{{ project.description }}</p>
                            <p class="text-gray-400 text-sm mt-3">Client: {{ project.client }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" class="text-4xl font-bold text-[#041228] mb-6">
                    Have a project in mind?
                </h2>
                <p v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.08" class="text-gray-600 text-lg mb-8">
                    Share a brief—we’ll suggest a sensible first milestone.
                </p>
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-[#38BDF8] text-[#041228] font-semibold rounded-full hover:bg-[#19427D] hover:text-white transition-all hover:-translate-y-0.5 shadow-md"
                >
                    Let’s talk
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </section>

        <SiteFooter />
    </div>
</template>
