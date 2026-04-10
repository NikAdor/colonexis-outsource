<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    useHomeAnchors: {
        type: Boolean,
        default: false,
    },
});

const email = ref('');
const isSubscribing = ref(false);
const isSubscribed = ref(false);

const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
};

const goToContact = () => {
    if (props.useHomeAnchors) {
        scrollToSection('contact');
        return;
    }
    window.location.href = '/contact';
};

const handleNewsletterSubmit = async () => {
    if (!email.value) return;
    isSubscribing.value = true;
    await new Promise((resolve) => setTimeout(resolve, 900));
    isSubscribing.value = false;
    isSubscribed.value = true;
};
</script>

<template>
    <footer class="bg-hero-bg text-hero-ink">
        <div class="border-b border-hero-muted/20 bg-surface-raised/20 py-10">
            <div class="mx-auto max-w-7xl px-5 sm:px-6">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="font-display text-2xl font-semibold tracking-tight md:text-3xl">Ready when your roadmap is.</p>
                        <p class="mt-2 max-w-xl text-sm text-hero-muted">Share constraints and dates — we respond with a scoped plan, not a brochure.</p>
                    </div>
                    <button type="button" class="ds-btn ds-btn--solid-dark shrink-0" @click="goToContact">Book a demo</button>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-5 sm:px-6">
            <div class="grid grid-cols-1 gap-10 pb-8 pt-14 md:grid-cols-4">
                <div class="md:pr-4">
                    <Link href="/" class="inline-flex items-center">
                        <img src="/img/Colonexis-logo.png" alt="Colonexis" class="h-11 w-auto brightness-0 invert" width="176" height="44" />
                    </Link>
                    <p class="mt-4 text-sm leading-relaxed text-hero-muted">
                        Interfaces, design systems, and Laravel/Vue delivery for teams that care about maintainability.
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-hero-muted/25 bg-hero-muted/5 transition-colors hover:border-accent hover:bg-accent/10"
                            aria-label="Colonexis on X"
                        >
                            <svg class="h-5 w-5 text-hero-ink" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                                />
                            </svg>
                        </a>
                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-hero-muted/25 bg-hero-muted/5 transition-colors hover:border-accent hover:bg-accent/10"
                            aria-label="Colonexis on GitHub"
                        >
                            <svg class="h-5 w-5 text-hero-ink" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                                />
                            </svg>
                        </a>
                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-hero-muted/25 bg-hero-muted/5 transition-colors hover:border-accent hover:bg-accent/10"
                            aria-label="Colonexis on LinkedIn"
                        >
                            <svg class="h-5 w-5 text-hero-ink" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-sm font-semibold text-hero-ink">Services</h2>
                    <ul class="space-y-3 text-sm text-hero-muted">
                        <li>
                            <button
                                v-if="useHomeAnchors"
                                type="button"
                                class="text-left transition-colors hover:text-hero-ink"
                                @click="scrollToSection('programs')"
                            >
                                Web development
                            </button>
                            <Link v-else :href="route('services')" class="transition-colors hover:text-hero-ink">Web development</Link>
                        </li>
                        <li>
                            <button
                                v-if="useHomeAnchors"
                                type="button"
                                class="text-left transition-colors hover:text-hero-ink"
                                @click="scrollToSection('programs')"
                            >
                                Design systems
                            </button>
                            <Link v-else :href="route('services')" class="transition-colors hover:text-hero-ink">Design systems</Link>
                        </li>
                        <li>
                            <button
                                v-if="useHomeAnchors"
                                type="button"
                                class="text-left transition-colors hover:text-hero-ink"
                                @click="scrollToSection('programs')"
                            >
                                Performance
                            </button>
                            <Link v-else :href="route('services')" class="transition-colors hover:text-hero-ink">Performance</Link>
                        </li>
                        <li>
                            <button
                                v-if="useHomeAnchors"
                                type="button"
                                class="text-left transition-colors hover:text-hero-ink"
                                @click="scrollToSection('programs')"
                            >
                                UI / UX
                            </button>
                            <Link v-else :href="route('services')" class="transition-colors hover:text-hero-ink">UI / UX</Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="mb-4 text-sm font-semibold text-hero-ink">Company</h2>
                    <ul class="space-y-3 text-sm text-hero-muted">
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('about')">
                                About
                            </button>
                            <Link v-else :href="route('about')" class="transition-colors hover:text-hero-ink">About</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('stories')">
                                Work
                            </button>
                            <Link v-else :href="route('works')" class="transition-colors hover:text-hero-ink">Work</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('about')">
                                Careers
                            </button>
                            <Link v-else :href="route('about')" class="transition-colors hover:text-hero-ink">Careers</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('contact')">
                                Press
                            </button>
                            <Link v-else :href="route('contact')" class="transition-colors hover:text-hero-ink">Press</Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="mb-4 text-sm font-semibold text-hero-ink">Support</h2>
                    <ul class="space-y-3 text-sm text-hero-muted">
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('contact')">
                                Contact
                            </button>
                            <Link v-else :href="route('contact')" class="transition-colors hover:text-hero-ink">Contact</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('contact')">
                                FAQ
                            </button>
                            <Link v-else :href="route('contact')" class="transition-colors hover:text-hero-ink">FAQ</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('contact')">
                                Privacy
                            </button>
                            <Link v-else :href="route('contact')" class="transition-colors hover:text-hero-ink">Privacy</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="text-left transition-colors hover:text-hero-ink" @click="scrollToSection('contact')">
                                Terms
                            </button>
                            <Link v-else :href="route('contact')" class="transition-colors hover:text-hero-ink">Terms</Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                <div class="rounded-2xl border border-hero-muted/20 bg-hero-muted/5 p-6 md:p-8">
                    <form class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between" @submit.prevent="handleNewsletterSubmit">
                        <div>
                            <p class="text-lg font-semibold text-hero-ink">Occasional notes</p>
                            <p class="mt-1 text-sm text-hero-muted">Interface craft, performance budgets, and accessibility checklists.</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <label class="sr-only" for="footer-newsletter-email">Email</label>
                            <input
                                id="footer-newsletter-email"
                                v-model="email"
                                type="email"
                                autocomplete="email"
                                placeholder="Email address"
                                class="min-h-[44px] flex-1 rounded-full border border-hero-muted/25 bg-hero-bg/40 px-4 py-3 text-hero-ink placeholder:text-hero-muted/70 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-accent"
                            />
                            <button
                                type="submit"
                                :disabled="isSubscribing"
                                class="ds-btn ds-btn--solid-dark min-h-[44px] rounded-full px-6 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {{ isSubscribed ? 'Subscribed' : isSubscribing ? 'Sending…' : 'Subscribe' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-3 border-t border-hero-muted/15 pt-6 text-sm text-hero-muted md:flex-row md:items-center md:justify-between">
                <p>© 2026 Colonexis. All rights reserved.</p>
                <div class="flex flex-wrap items-center justify-center gap-4 md:justify-end">
                    <a href="#" class="transition-colors hover:text-hero-ink">Privacy</a>
                    <a href="#" class="transition-colors hover:text-hero-ink">Terms</a>
                    <a href="#" class="transition-colors hover:text-hero-ink">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</template>
