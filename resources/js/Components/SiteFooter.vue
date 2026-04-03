<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
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
    if (useHomeAnchors) {
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
    <footer class="bg-[#020308] text-white">
        <!-- Top CTA bar -->
        <div class="bg-gradient-to-r from-[#19427D] to-[#38BDF8] py-10">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <div class="text-2xl md:text-3xl font-bold">Ready to Scale Your Business?</div>
                        <div class="mt-2 text-white/80">Let's build something extraordinary together.</div>
                    </div>
                    <button
                        type="button"
                        @click="goToContact"
                        class="bg-white text-[#041228] px-7 py-3 rounded-full font-semibold hover:bg-[#E6F2FF] transition-colors shadow-sm"
                    >
                        Start a Project
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pt-14 pb-8">
                <!-- Brand -->
                <div class="md:pr-4">
                    <Link href="/" class="inline-flex items-center">
                        <img src="/img/Colonexis-logo.png" alt="Colonexis" class="h-12" />
                    </Link>
                    <p class="mt-4 text-gray-300 text-sm leading-relaxed">
                        Your central digital hub — connecting businesses to smart automation, modern technology, and scalable growth strategies.
                    </p>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="#" class="w-10 h-10 bg-[#38BDF8]/15 hover:bg-[#38BDF8]/25 border border-[#38BDF8]/20 rounded-full flex items-center justify-center transition-colors" aria-label="Social">
                            <svg class="w-5 h-5 text-[#38BDF8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#38BDF8]/15 hover:bg-[#38BDF8]/25 border border-[#38BDF8]/20 rounded-full flex items-center justify-center transition-colors" aria-label="Social">
                            <svg class="w-5 h-5 text-[#38BDF8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#38BDF8]/15 hover:bg-[#38BDF8]/25 border border-[#38BDF8]/20 rounded-full flex items-center justify-center transition-colors" aria-label="Social">
                            <svg class="w-5 h-5 text-[#38BDF8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-sm font-semibold text-white/90 mb-4">Services</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('services')">Web Development</button>
                            <Link v-else :href="route('services')" class="hover:text-white transition-colors">Web Development</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('services')">Digital Marketing</button>
                            <Link v-else :href="route('services')" class="hover:text-white transition-colors">Digital Marketing</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('services')">AI Automation</button>
                            <Link v-else :href="route('services')" class="hover:text-white transition-colors">AI Automation</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('services')">UI/UX Design</button>
                            <Link v-else :href="route('services')" class="hover:text-white transition-colors">UI/UX Design</Link>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-sm font-semibold text-white/90 mb-4">Company</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('about')">About Us</button>
                            <Link v-else :href="route('about')" class="hover:text-white transition-colors">About Us</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('works')">Our Work</button>
                            <Link v-else :href="route('works')" class="hover:text-white transition-colors">Our Work</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('about')">Careers</button>
                            <Link v-else :href="route('about')" class="hover:text-white transition-colors">Careers</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">Press Kit</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">Press Kit</Link>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-sm font-semibold text-white/90 mb-4">Support</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">Contact Us</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">Contact Us</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">FAQ</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">FAQ</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">Privacy Policy</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">Privacy Policy</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">Terms of Service</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">Terms of Service</Link>
                        </li>
                        <li>
                            <button v-if="useHomeAnchors" type="button" class="hover:text-white transition-colors text-left" @click="scrollToSection('contact')">Sitemap</button>
                            <Link v-else :href="route('contact')" class="hover:text-white transition-colors">Sitemap</Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter (like screenshot) -->
            <div class="mt-8">
                <div class="bg-[#041228]/60 border border-white/10 rounded-2xl p-6 md:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="text-lg font-semibold">Stay in the loop</div>
                            <div class="text-sm text-gray-300 mt-1">
                                Get the latest insights on digital growth, AI, and strategy.
                            </div>
                        </div>
                        <form class="flex flex-col sm:flex-row gap-3 sm:items-center" @submit.prevent="handleNewsletterSubmit">
                            <input
                                v-model="email"
                                type="email"
                                placeholder="Enter your email"
                                class="flex-1 px-4 py-3 rounded-full bg-white/5 border border-white/10 text-white placeholder:text-white/60 outline-none focus:ring-2 focus:ring-[#38BDF8]"
                            />
                            <button
                                type="submit"
                                :disabled="isSubscribing"
                                class="bg-[#38BDF8] text-[#041228] px-6 py-3 rounded-full font-semibold hover:bg-[#19427D] hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                {{ isSubscribed ? 'Subscribed' : isSubscribing ? 'Subscribing...' : 'Subscribe' }}
                                <svg v-if="!isSubscribed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7 7-7-7-7" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom legal row -->
            <div class="mt-10 pt-6 border-t border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-sm text-gray-300">
                <div>© 2026 Colonexis. All rights reserved. Built with precision.</div>
                <div class="flex flex-wrap items-center gap-4 justify-center md:justify-end">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
</template>
