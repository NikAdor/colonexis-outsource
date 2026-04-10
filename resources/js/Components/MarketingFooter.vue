<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const year = computed(() => new Date().getFullYear());
</script>

<template>
    <footer class="welcome-page__footer">
        <div class="welcome-page__footer-grid">
            <div>
                <span class="welcome-page__footer-brand-title">{{ appName }}</span>
                <p class="welcome-page__footer-about">
                    The premier destination for professional-grade technical education and digital architecture
                    services.
                </p>
                <div class="welcome-page__footer-social">
                    <a href="#" class="welcome-page__footer-social-link" aria-label="Web" @click.prevent>
                        <span class="material-symbols-outlined">public</span>
                    </a>
                    <a href="#" class="welcome-page__footer-social-link" aria-label="Email" @click.prevent>
                        <span class="material-symbols-outlined">alternate_email</span>
                    </a>
                </div>
            </div>
            <div>
                <h5 class="welcome-page__footer-col-title">Learning Paths</h5>
                <ul class="welcome-page__footer-list">
                    <li>
                        <Link :href="route('courses.index')" class="welcome-page__footer-link">
                            System Architecture
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('courses.index')" class="welcome-page__footer-link">
                            Leadership for Eng Managers
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('courses.index')" class="welcome-page__footer-link">
                            Advanced Algorithms
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('courses.index')" class="welcome-page__footer-link">
                            Cloud Infrastructure
                        </Link>
                    </li>
                </ul>
            </div>
            <div>
                <h5 class="welcome-page__footer-col-title">Resources</h5>
                <ul class="welcome-page__footer-list">
                    <li>
                        <Link :href="route('works')" class="welcome-page__footer-link">Case Studies</Link>
                    </li>
                    <li>
                        <Link :href="route('about')" class="welcome-page__footer-link">Technical Blog</Link>
                    </li>
                    <li>
                        <Link :href="route('services')" class="welcome-page__footer-link">White Papers</Link>
                    </li>
                    <li>
                        <Link :href="route('testimonials')" class="welcome-page__footer-link">Podcast</Link>
                    </li>
                </ul>
            </div>
            <div>
                <h5 class="welcome-page__footer-col-title">Support</h5>
                <ul class="welcome-page__footer-list">
                    <li>
                        <Link :href="route('contact')" class="welcome-page__footer-link">Student Support</Link>
                    </li>
                    <li>
                        <Link :href="route('services')" class="welcome-page__footer-link">Enterprise Training</Link>
                    </li>
                    <li>
                        <Link :href="route('contact')" class="welcome-page__footer-link">Contact Us</Link>
                    </li>
                    <li>
                        <a href="#" class="welcome-page__footer-link" @click.prevent>Careers</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="welcome-page__footer-bottom">
            <p>© {{ year }} {{ appName }}. All rights reserved.</p>
            <div class="welcome-page__footer-legal">
                <a href="#" @click.prevent>Privacy Policy</a>
                <a href="#" @click.prevent>Terms of Service</a>
                <span>Region: Global (US)</span>
            </div>
        </div>
    </footer>
</template>
