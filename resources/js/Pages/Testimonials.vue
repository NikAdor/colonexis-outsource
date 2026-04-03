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

const testimonials = [
    {
        quote: 'They reframed our product narrative and shipped a site that finally matches the quality of our platform.',
        author: 'James Morrison',
        role: 'CEO, Northline Labs',
        image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face',
    },
    {
        quote: 'Clear communication, fast iterations, and a mobile experience our users actually enjoy.',
        author: 'Lisa Chang',
        role: 'Product Lead, Vital Apps',
        image: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&h=100&fit=crop&crop=face',
    },
    {
        quote: 'Beautiful UI without sacrificing performance—exactly what we needed for launch.',
        author: 'Michael Ross',
        role: 'Founder, GreenLeaf Co.',
        image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face',
    },
    {
        quote: 'On time, on budget, and the quality bar stayed high through the final week.',
        author: 'Sarah Thompson',
        role: 'Director, FinanceHub',
        image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop&crop=face',
    },
    {
        quote: 'They helped us turn analytics into decisions—dashboards teams actually use every day.',
        author: 'David Kim',
        role: 'CMO, Harbor Retail',
        image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face',
    },
    {
        quote: 'Thoughtful design systems and engineering that made future features much easier.',
        author: 'Amanda Foster',
        role: 'CEO, EduStart',
        image: 'https://images.unsplash.com/photo-1598550874175-4d0ef436c909?w=100&h=100&fit=crop&crop=face',
    },
];

const stats = ref([
    { value: 150, label: 'Projects completed', suffix: '+' },
    { value: 98, label: 'Client satisfaction', suffix: '%' },
    { value: 8, label: 'Years experience', suffix: '+' },
    { value: 24, label: 'Core team', suffix: '' },
]);

const animatedStats = ref(stats.value.map(() => ref(0)));

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
    <Head title="Testimonials" />
    <div class="min-h-screen bg-white text-gray-900">
        <SiteHeader :can-login="canLogin" :can-register="canRegister" variant="page" />

        <section ref="heroSection" class="relative min-h-[72vh] flex items-center pt-24 overflow-hidden bg-[#020308]">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/85 via-transparent to-[#020308]/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium tracking-wide text-[#38BDF8]/90 uppercase">Social proof</p>
                    <h1 class="mt-4 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">Testimonials</h1>
                    <p class="mt-6 text-xl text-white/70 max-w-2xl leading-relaxed">
                        Partners who value clarity, craft, and dependable delivery.
                    </p>
                </div>
            </div>
        </section>

        <section ref="statsSection" class="py-24 bg-[#F1F1F1]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-10 justify-between">
                    <div
                        v-for="(stat, index) in stats"
                        :key="stat.label"
                        v-motion
                        :initial="{ opacity: 0, y: 18 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.06"
                        class="text-center flex-1 min-w-[140px]"
                    >
                        <div class="text-5xl md:text-6xl font-bold text-[#38BDF8] mb-2">
                            {{ animatedStats[index]?.value }}{{ stat.suffix }}
                        </div>
                        <div class="text-gray-600 text-lg">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col gap-8">
                    <div
                        v-for="(testimonial, index) in testimonials"
                        :key="testimonial.author"
                        v-motion
                        :initial="{ opacity: 0, y: 22 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.06"
                        class="flex flex-col md:flex-row gap-6 md:gap-10 items-start p-8 rounded-2xl border border-gray-100 bg-[#F1F1F1]/40 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300"
                    >
                        <img
                            :src="testimonial.image"
                            :alt="testimonial.author"
                            class="w-16 h-16 rounded-full object-cover shrink-0 border border-white shadow-sm"
                        />
                        <div class="flex-1">
                            <div class="flex gap-1 mb-4">
                                <svg
                                    v-for="i in 5"
                                    :key="i"
                                    class="w-5 h-5 text-[#38BDF8]"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                    />
                                </svg>
                            </div>
                            <p class="text-gray-700 text-lg leading-relaxed">“{{ testimonial.quote }}”</p>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="font-semibold text-[#041228]">{{ testimonial.author }}</div>
                                <div class="text-sm text-gray-500">{{ testimonial.role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1]">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" class="text-4xl font-bold text-[#041228] mb-6">
                    Become the next story
                </h2>
                <p v-motion :initial="{ opacity: 0, y: 20 }" :visibleOnce="{ opacity: 1, y: 0 }" :delay="0.08" class="text-gray-600 text-lg mb-8">
                    Share your timeline and goals—we’ll respond with a practical plan.
                </p>
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-[#38BDF8] text-[#041228] font-semibold rounded-full hover:bg-[#19427D] hover:text-white transition-all hover:-translate-y-0.5 shadow-md"
                >
                    Start a conversation
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </section>

        <SiteFooter />
    </div>
</template>
