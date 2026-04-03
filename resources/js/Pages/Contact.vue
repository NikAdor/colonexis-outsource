<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import SiteHeader from '@/Components/SiteHeader.vue';

defineProps({
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: false },
});

const heroSection = ref(null);

const form = ref({
    name: '',
    email: '',
    phone: '',
    company: '',
    service: '',
    message: '',
});

const isSubmitting = ref(false);
const isSubmitted = ref(false);

const services = [
    'Web Development',
    'Mobile App Development',
    'UI/UX Design',
    'Cloud Solutions',
    'Brand Identity',
    'Digital Marketing',
    'Other',
];

const contactInfo = [
    {
        icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        title: 'Email',
        value: 'hello@colonexis.com',
        description: 'We reply within one business day',
    },
    {
        icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`,
        title: 'Phone',
        value: '+1 (555) 123-4567',
        description: 'Mon–Fri, 9am–6pm',
    },
    {
        icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        title: 'Studio',
        value: 'San Francisco, CA',
        description: 'By appointment',
    },
];

const handleSubmit = async () => {
    isSubmitting.value = true;
    await new Promise((resolve) => setTimeout(resolve, 1500));
    isSubmitting.value = false;
    isSubmitted.value = true;
};
</script>

<template>
    <Head title="Contact" />
    <div class="min-h-screen bg-white text-gray-900">
        <SiteHeader :can-login="canLogin" :can-register="canRegister" variant="page" />

        <section ref="heroSection" class="relative min-h-[56vh] flex items-center pt-24 overflow-hidden bg-[#020308]">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-[#020308]/95 via-[#020308]/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-[#020308]/85 via-transparent to-[#020308]/35" />
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-16 w-full">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium tracking-wide text-[#38BDF8]/90 uppercase">Contact</p>
                    <h1 class="mt-4 text-5xl md:text-6xl font-bold text-white leading-tight">Let’s talk</h1>
                    <p class="mt-6 text-xl text-white/70 max-w-2xl leading-relaxed">
                        Share your goals and timeline. This form is a demo—nothing is sent to a server.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div
                        v-for="(info, index) in contactInfo"
                        :key="info.title"
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.08"
                        class="flex-1 bg-[#F1F1F1] p-8 rounded-2xl text-center border border-white shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all"
                    >
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center mb-6 text-[#38BDF8] mx-auto" v-html="info.icon" />
                        <h3 class="text-xl font-semibold text-[#041228] mb-2">{{ info.title }}</h3>
                        <div class="text-[#38BDF8] font-medium mb-2">{{ info.value }}</div>
                        <div class="text-gray-500 text-sm">{{ info.description }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-[#F1F1F1]">
            <div class="max-w-4xl mx-auto px-6">
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 24 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="bg-white rounded-2xl shadow-sm border border-white/80 p-8 md:p-12"
                >
                    <h2 class="text-3xl font-bold text-[#041228] mb-8 text-center">Send a message</h2>

                    <div v-if="isSubmitted" class="text-center py-12">
                        <div class="w-20 h-20 bg-[#F1F1F1] rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-[#38BDF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#041228] mb-4">Thank you</h3>
                        <p class="text-gray-600 mb-8">Demo only—no message was transmitted.</p>
                        <button
                            type="button"
                            class="px-6 py-3 border border-[#041228] text-[#041228] rounded-full hover:bg-[#041228] hover:text-white transition-colors"
                            @click="isSubmitted = false"
                        >
                            Send another message
                        </button>
                    </div>

                    <form v-else class="space-y-6" @submit.prevent="handleSubmit">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Name *</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="Your name"
                                />
                            </div>
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Email *</label>
                                <input
                                    v-model="form.email"
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
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="+1 (555) 000-0000"
                                />
                            </div>
                            <div>
                                <label class="block text-[#041228] text-sm font-medium mb-2">Company</label>
                                <input
                                    v-model="form.company"
                                    type="text"
                                    class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                                    placeholder="Your company"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="block text-[#041228] text-sm font-medium mb-2">Service</label>
                            <select
                                v-model="form.service"
                                class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors"
                            >
                                <option value="" class="bg-white">Select a service</option>
                                <option v-for="service in services" :key="service" :value="service" class="bg-white">
                                    {{ service }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[#041228] text-sm font-medium mb-2">Message *</label>
                            <textarea
                                v-model="form.message"
                                rows="5"
                                required
                                class="w-full px-4 py-3 bg-[#F1F1F1] border-0 rounded-lg text-[#041228] placeholder-gray-400 focus:ring-2 focus:ring-[#38BDF8] outline-none transition-colors resize-none"
                                placeholder="Tell us about your project..."
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="w-full py-4 bg-[#38BDF8] text-[#041228] font-semibold rounded-lg hover:bg-[#19427D] hover:text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <svg v-if="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                />
                            </svg>
                            {{ isSubmitting ? 'Sending...' : 'Send message' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <SiteFooter />
    </div>
</template>
