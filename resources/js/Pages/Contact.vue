<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import CosmicFlowBackground from '@/Components/CosmicFlowBackground.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';

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
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <Head title="Contact" />
        <div class="min-h-screen bg-paper text-ink">
        <section ref="heroSection" class="relative flex min-h-[56vh] items-center overflow-hidden bg-hero-bg pt-6">
            <CosmicFlowBackground :container="heroSection" class="z-0" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-r from-hero-bg/95 via-hero-bg/60 to-transparent" />
            <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-hero-bg/85 via-transparent to-hero-bg/35" />
            <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-16">
                <div v-motion :initial="{ opacity: 0, y: 24 }" :visibleOnce="{ opacity: 1, y: 0 }" class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-wide text-accent">Contact</p>
                    <h1 class="mt-4 text-5xl font-bold leading-tight text-hero-ink md:text-6xl">Let’s talk</h1>
                    <p class="mt-6 max-w-2xl text-xl leading-relaxed text-hero-muted">
                        Share your goals and timeline. This form is a demo—nothing is sent to a server.
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-surface py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-col gap-8 lg:flex-row">
                    <div
                        v-for="(info, index) in contactInfo"
                        :key="info.title"
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :visibleOnce="{ opacity: 1, y: 0 }"
                        :delay="index * 0.08"
                        class="flex-1 rounded-2xl border border-border bg-surface-raised p-8 text-center shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-xl border border-border bg-surface text-accent" v-html="info.icon" />
                        <h3 class="mb-2 text-xl font-semibold text-ink">{{ info.title }}</h3>
                        <div class="mb-2 font-medium text-accent">{{ info.value }}</div>
                        <div class="text-sm text-ink-muted">{{ info.description }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-surface-raised py-24">
            <div class="mx-auto max-w-4xl px-6">
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 24 }"
                    :visibleOnce="{ opacity: 1, y: 0 }"
                    class="rounded-2xl border border-border bg-surface p-8 shadow-sm md:p-12"
                >
                    <h2 class="mb-8 text-center text-3xl font-bold text-ink">Send a message</h2>

                    <div v-if="isSubmitted" class="py-12 text-center">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-surface-raised">
                            <svg class="h-10 w-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-ink">Thank you</h3>
                        <p class="mb-8 text-ink-muted">Demo only—no message was transmitted.</p>
                        <button
                            type="button"
                            class="rounded-full border border-ink px-6 py-3 text-ink transition-colors hover:bg-ink hover:text-paper"
                            @click="isSubmitted = false"
                        >
                            Send another message
                        </button>
                    </div>

                    <form v-else class="space-y-6" @submit.prevent="handleSubmit">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-ink">Name *</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="ds-input rounded-lg"
                                    placeholder="Your name"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-ink">Email *</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="ds-input rounded-lg"
                                    placeholder="your@email.com"
                                />
                            </div>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-ink">Phone</label>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    class="ds-input rounded-lg"
                                    placeholder="+1 (555) 000-0000"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-ink">Company</label>
                                <input
                                    v-model="form.company"
                                    type="text"
                                    class="ds-input rounded-lg"
                                    placeholder="Your company"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-ink">Service</label>
                            <select v-model="form.service" class="ds-input rounded-lg">
                                <option value="" class="bg-surface">Select a service</option>
                                <option v-for="service in services" :key="service" :value="service" class="bg-surface">
                                    {{ service }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-ink">Message *</label>
                            <textarea
                                v-model="form.message"
                                rows="5"
                                required
                                class="ds-input resize-none rounded-lg"
                                placeholder="Tell us about your project..."
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-accent py-4 font-semibold text-accent-fg transition-all hover:bg-accent-hover disabled:cursor-not-allowed disabled:opacity-50"
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

        </div>
    </MarketingLayout>
</template>
