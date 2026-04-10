<script setup>
import InputError from '@/Components/InputError.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    canLogin: { type: Boolean, default: false },
    canRegister: { type: Boolean, default: true },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const brandBgUrl =
    "url('https://lh3.googleusercontent.com/aida-public/AB6AXuCfd3AxkbdUvybTQVQ19cH8i-yGcEFCCiRpepg5R2T-uJWd4L7WIggNc5Zo4pxySFrw3NuXIE0fggdBq5n_z5RDJVoDJ3o_XwxBiJp_vLoA_Sy8GljGWcXSJycXcASRe6huD-qhDUrIO3SxVBBaf0JJhnhjFYZFMVDMMc4MiRDLlVxmpRrkWlyUHoSvE5XUxIHQC7nzNsVR9c22RQ2fsIPFyVpsGO3GsBvrNpwcregOaiqC6MCkcMPbvZeQ-8S58ePsZCoZalsbGBY')";

const avatarUrls = [
    'https://lh3.googleusercontent.com/aida-public/AB6AXuD2tTnxX4etuGMRe1kIMDwiw21iKRJ3JAoEYtEGHRSC6DhFYuqYvNM1q232FSdXFbVoNbb9PPVeDB6Nmx_FLdv0oc6Jd6pACTd8VJzpAqptCERdZB2KO3cv5JMkLOlkTMtmFa0eiV6HN-x950ESf00fDWg98XHvPvtY583Lfr0cs_4JLnuO9ObUdYD9PYAIERdgpOJT52ypuczpdbxfjvSfRdU4zMRK7b3ks-sA0eVEvwM_kMIxrs_lhAkYYeyTrtKGlcD4u8-BpQE',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuCPjGDhm84sWhgs5-PNDrUj7ZYOyj-sbjl43sVUFTnAoJdGNLE_GX5kHWyd_AgXaWlhE7tl7E-QEdhwi21hEXjUnaSsHC1_onwZdkdXYXs7tXOKsUm00vBiH6TKZCgQg_LkJ44VjJKMkX_QXBqyXoCE6vel2z2aYUs7-sTfNY95aJA7NB_lxJgxmKBevljUfVHbV2hceQR_C5CmRLwqn5Tyxp2-Y5dZqxk5WXyPH0eVnQjaH7fgKg54YERUbKEQMJhqP3ZQRy5Dcc',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuDn9UdqF8Ep5VbvRjGbQw1CPo97LPLUTeJprWD6fqH2lovJ5NHMru6W8_ek7U8z9sqN9uqBUO29C0yFxWgY-VH8y9-JZANJU-wa8FacpNaiyYHY_6ym2wDRgbjpAkluEjwVWZWYlckj61SJtLvj33LzopxW0iBMTfh-H7E1rtBLM4dEHMVG126HEZumenPYKZ--mTcYFm1xPWISD8V11PIYN5MOGjf6JyTVbhKCkfbtXyPbJgLFhfZAcnI23qSaB0jZJTPLe-KnYjA',
];
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <div class="login-page">
        <Head :title="`Sign In | ${appName}`" />

        <main class="login-page__pattern">
            <div class="login-page__card">
                <div class="login-page__brand">
                    <div
                        class="login-page__brand-bg"
                        :style="{ backgroundImage: brandBgUrl }"
                    />
                    <div class="login-page__brand-z">
                        <h1 class="login-page__brand-logo">{{ appName }}</h1>
                    </div>
                    <div class="login-page__brand-z login-page__brand-stack">
                        <h2 class="login-page__brand-title">
                            The digital studio for high-end technical education.
                        </h2>
                        <p class="login-page__brand-lead">
                            Access curated courses designed for the editorial architect in tech.
                        </p>
                    </div>
                    <div class="login-page__brand-z">
                        <ul class="login-page__avatars">
                            <li v-for="(src, i) in avatarUrls" :key="i">
                                <img class="login-page__avatar" :src="src" alt="" />
                            </li>
                        </ul>
                        <p class="login-page__avatar-caption">
                            Join over 12,000+ technical curators worldwide.
                        </p>
                    </div>
                </div>

                <div class="login-page__form-panel">
                    <div class="login-page__form-inner">
                        <div v-if="status" class="login-page__status">
                            {{ status }}
                        </div>

                        <header class="login-page__header">
                            <h3 class="login-page__title">Welcome Back</h3>
                            <p class="login-page__subtitle">
                                Continue your path to technical mastery.
                            </p>
                        </header>

                        <div class="login-page__social-grid">
                            <button
                                type="button"
                                class="login-page__social-btn"
                                disabled
                                title="Google sign-in is not configured"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                        fill="#4285F4"
                                    />
                                    <path
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                        fill="#34A853"
                                    />
                                    <path
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"
                                        fill="#FBBC05"
                                    />
                                    <path
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                        fill="#EA4335"
                                    />
                                </svg>
                                Google
                            </button>
                            <button
                                type="button"
                                class="login-page__social-btn"
                                disabled
                                title="GitHub sign-in is not configured"
                            >
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"
                                    />
                                </svg>
                                GitHub
                            </button>
                        </div>

                        <div class="login-page__divider">
                            <hr class="login-page__divider-line" />
                            <span class="login-page__divider-label">Or with email</span>
                        </div>

                        <form class="login-page__form" @submit.prevent="submit">
                            <div class="login-page__field">
                                <label class="login-page__label" for="email">Email Address</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    class="login-page__input"
                                    type="email"
                                    name="email"
                                    placeholder="name@company.com"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <InputError class="login-page__error" :message="form.errors.email" />
                            </div>

                            <div class="login-page__field">
                                <div class="login-page__label-row">
                                    <label class="login-page__label" for="password">Password</label>
                                    <Link
                                        v-if="canResetPassword"
                                        class="login-page__link"
                                        :href="route('password.request')"
                                    >
                                        Forgot Password?
                                    </Link>
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    class="login-page__input"
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                />
                                <InputError class="login-page__error" :message="form.errors.password" />
                            </div>

                            <div class="login-page__remember">
                                <input
                                    id="remember"
                                    v-model="form.remember"
                                    class="login-page__checkbox"
                                    type="checkbox"
                                    name="remember"
                                />
                                <label class="login-page__remember-label" for="remember">
                                    Remember me for 30 days
                                </label>
                            </div>

                            <button
                                class="login-page__submit"
                                type="submit"
                                :disabled="form.processing"
                            >
                                Sign In
                                <span
                                    class="material-symbols-outlined login-page__submit-icon"
                                    aria-hidden="true"
                                >
                                    arrow_forward
                                </span>
                            </button>
                        </form>

                        <footer class="login-page__footer">
                            <p class="login-page__footer-text">
                                Don't have an account?
                                <Link class="login-page__footer-link" :href="route('register')">
                                    Sign Up
                                </Link>
                            </p>
                        </footer>
                    </div>
                </div>
            </div>
        </main>

        <Link class="login-page__help" :href="route('help')" aria-label="Help">
            <span class="material-symbols-outlined login-page__help-icon">help_outline</span>
        </Link>
        </div>
    </MarketingLayout>
</template>

<style lang="scss" src="../../../scss/login.scss"></style>
