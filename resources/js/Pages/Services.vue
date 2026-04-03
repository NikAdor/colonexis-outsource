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
    <Head title="Services" />
    <div class="min-h-screen bg-white text-gray-900">
        <SiteHeader :can-login="canLogin" :can-register="canRegister" variant="page" />

        <section ref="heroSection" class="relative min-h-[72vh] flex items-center pt-24 overflow-hidden bg-[#020308]">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/85 via-transparent to-[#020308]/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium tracking-wide text-[#38BDF8]/90 uppercase">Services</p>
                    <h1 class="mt-4 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">What we deliver</h1>
                    <p class="mt-6 text-xl text-white/70 max-w-2xl leading-relaxed">
                        End-to-end digital capabilities—from first sketch to production systems—with a premium, minimalist touch.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1] relative overflow-hidden">
            <div class="absolute -top-16 right-0 w-96 h-96 bg-[#38BDF8]/10 rounded-full blur-3xl pointer-events-none" />
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
                            class="bg-white p-8 rounded-2xl border border-white/60 shadow-sm hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300"
                        >
                            <div class="w-16 h-16 bg-[#F1F1F1] rounded-xl flex items-center justify-center mb-6 text-[#38BDF8]" v-html="service.icon" />
                            <h3 class="text-xl font-semibold text-[#041228] mb-4">{{ service.title }}</h3>
                            <p class="text-gray-600 leading-relaxed mb-6">{{ service.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="feature in service.features" :key="feature" class="text-gray-500 text-sm flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#38BDF8]" />
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
                            class="bg-white p-8 rounded-2xl border border-white/60 shadow-sm hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300"
                        >
                            <div class="w-16 h-16 bg-[#F1F1F1] rounded-xl flex items-center justify-center mb-6 text-[#38BDF8]" v-html="service.icon" />
                            <h3 class="text-xl font-semibold text-[#041228] mb-4">{{ service.title }}</h3>
                            <p class="text-gray-600 leading-relaxed mb-6">{{ service.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="feature in service.features" :key="feature" class="text-gray-500 text-sm flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#38BDF8]" />
                                    {{ feature }}
                                </li>
                            </ul>
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
                    How we work
                </h2>
                <div class="flex flex-col md:flex-row md:flex-wrap gap-8">
                    <div
                        v-for="(item, index) in process"
                        :key="item.step"
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.06"
                        class="flex-1 min-w-[240px] border border-gray-100 rounded-2xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div class="text-[#38BDF8]/35 text-6xl font-bold mb-2">{{ item.step }}</div>
                        <h3 class="text-xl font-semibold text-[#041228] mb-3">{{ item.title }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ item.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-16 items-center">
                    <div v-motion :initial="{ opacity: 0, y: 22 }" :visibleOnce="{ opacity: 1, y: 0 }" class="flex-1">
                        <h2 class="text-4xl font-bold text-[#041228] mb-6">Why teams choose us</h2>
                        <p class="text-gray-600 text-lg leading-relaxed mb-8">
                            We ship with clarity: tight communication, predictable timelines, and engineering you can extend later without regret.
                        </p>
                        <div class="space-y-4">
                            <div v-for="label in ['Senior-led delivery', 'Performance & accessibility', 'Post-launch partnership']" :key="label" class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-gray-100 shadow-sm">
                                    <svg class="w-5 h-5 text-[#38BDF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-[#041228] font-medium">{{ label }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-motion :initial="{ opacity: 0, y: 22 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.1" class="flex-1 w-full">
                        <div class="aspect-video rounded-3xl overflow-hidden bg-[#F1F1F1] border border-gray-100 shadow-xl">
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

        <section class="py-24 bg-white">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" class="text-4xl font-bold text-[#041228] mb-6">
                    Ready to start?
                </h2>
                <p v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.08" class="text-gray-600 text-lg mb-8">
                    Tell us what you’re building—we’ll propose a focused plan.
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
