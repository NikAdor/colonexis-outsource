<script setup>
import InputError from '@/Components/InputError.vue';
import MarketingLayout from '@/Components/MarketingLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

defineProps({
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: false },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const termsAccepted = ref(false);
const termsError = ref('');

const grainUrl =
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAf6Jyn5xQ9OrCEDWKBfGpZ5vx-uKk7KWXP5Y2SoLR2KexcqSaTlvOfn_858nDNLe1jOaLnAeYTIR0Vgq3MYbcloyDzkiNRm4bvayWzjqIRy6x1OUOMyy5nQ4JP0LSkSO6QN8ArQBv0Ge9MIVpDgNk-d9o0qTX4dWZjBP1x5b_o2c9ZIDB6zKE8J4dFPfcARr1FMZ4dYUokt-4ZtPKSdiQzbv47B_1NB0ecRMsLsHTrcwF4kY3uGzKlqkDtZmpTHc5Ec0Rmy2USsWU';

const githubIconUrl =
    'https://lh3.googleusercontent.com/aida-public/AB6AXuCkmQ3Vo5OeudnDYOnY33LvZoNBf_7al87oT9GsXfcX0nG-V0L_E0MTk2XaJKOqF6GyKm9Pjb0DENq1fKtKxHBfRPGgfiejU6W3ugAXUC_AGX9dnaofCw7hPJoJskpxHwomfg4d3tUGxcCoHxIh3zKkgBD8YrZPS6YLR9FeK45LhgwEk5I1tFV8iNjR5xvUlYUFI-ZJA1ooHa7XupB057S2cxUVTJc7NIe3ZvXYldrQ3wxijW2GYmAfQX3PcavlpqC6YRKASuEo_1w';

const googleIconUrl =
    'https://lh3.googleusercontent.com/aida-public/AB6AXuB1l3rvXbflZ1J0VDSyJ5UvDjA4qF9i8aOuXWULUF8r3YxqTmuWIo0MK0gTZ_4MPJsuecLMsfbNak9k8Y0oyUR55jCIZegKrBpY-aWEZ9mA3WVOnkkeTmhTMqE1pfSdkuvRXdMMfVNUkJFaFZYtr5Y-96lNtDN6tPajmrMuY-4B6MDU66E63aufDOvPx1N6vN5BJL_792gXHcN1gEgVx2emAeR9Q-08Cnz6EqqNg-rXrCd5UllDbrnoZxQQOaw52VuOoARzxv3eCa8';

const submit = () => {
    termsError.value = '';
    if (!termsAccepted.value) {
        termsError.value = 'Please accept the terms and privacy policy to continue.';
        return;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <MarketingLayout :can-login="canLogin" :can-register="canRegister">
        <div class="register-page">
        <Head :title="`Sign Up | ${appName}`" />

        <main class="register-page__main">
            <aside class="register-page__aside" aria-label="Sign up messaging">
                <div class="register-page__aside-top">
                    <p class="register-page__aside-brand">{{ appName }}</p>
                    <div class="register-page__aside-copy">
                        <h1 class="register-page__aside-title">
                            Architect your professional future.
                        </h1>
                        <p class="register-page__aside-lead">
                            Join an elite collective of technical minds. Our curated learning paths
                            are designed for deep work and intellectual mastery.
                        </p>
                    </div>
                </div>

                <div class="register-page__stats">
                    <div class="register-page__stat">
                        <div class="register-page__stat-value">12k+</div>
                        <div class="register-page__stat-label">Active Curators</div>
                    </div>
                    <div class="register-page__stat">
                        <div class="register-page__stat-value">450+</div>
                        <div class="register-page__stat-label">Technical Deep-Dives</div>
                    </div>
                </div>

                <div class="register-page__aside-glows" aria-hidden="true">
                    <div class="register-page__glow register-page__glow--a" />
                    <div class="register-page__glow register-page__glow--b" />
                </div>
                <div
                    class="register-page__aside-grain"
                    :style="{ backgroundImage: `url('${grainUrl}')` }"
                    aria-hidden="true"
                />
            </aside>

            <section class="register-page__section">
                <div class="register-page__card">
                    <p class="register-page__mobile-brand">{{ appName }}</p>

                    <header class="register-page__header">
                        <h2 class="register-page__title">Create Account</h2>
                        <p class="register-page__subtitle">Begin your curation journey today.</p>
                    </header>

                    <form class="register-page__form" @submit.prevent="submit">
                        <div class="register-page__field">
                            <label class="register-page__label" for="name">Full Name</label>
                            <input
                                id="name"
                                v-model="form.name"
                                class="register-page__input"
                                type="text"
                                name="name"
                                placeholder="Enter your full name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="register-page__error" :message="form.errors.name" />
                        </div>

                        <div class="register-page__field">
                            <label class="register-page__label" for="email">Email Address</label>
                            <input
                                id="email"
                                v-model="form.email"
                                class="register-page__input"
                                type="email"
                                name="email"
                                placeholder="curator@tech.com"
                                required
                                autocomplete="username"
                            />
                            <InputError class="register-page__error" :message="form.errors.email" />
                        </div>

                        <div class="register-page__password-row">
                            <div class="register-page__field">
                                <label class="register-page__label" for="password">Password</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    class="register-page__input"
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError
                                    class="register-page__error"
                                    :message="form.errors.password"
                                />
                            </div>
                            <div class="register-page__field">
                                <label
                                    class="register-page__label"
                                    for="password_confirmation"
                                >
                                    Confirm
                                </label>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    class="register-page__input"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError
                                    class="register-page__error"
                                    :message="form.errors.password_confirmation"
                                />
                            </div>
                        </div>

                        <div class="register-page__terms">
                            <input
                                id="terms"
                                v-model="termsAccepted"
                                class="register-page__checkbox"
                                type="checkbox"
                                name="terms"
                            />
                            <label class="register-page__terms-label" for="terms">
                                I agree to the
                                <a
                                    class="register-page__inline-link"
                                    href="#"
                                    @click.prevent
                                >Terms and Conditions</a>
                                and
                                <a
                                    class="register-page__inline-link"
                                    href="#"
                                    @click.prevent
                                >Privacy Policy</a>
                                .
                            </label>
                        </div>
                        <p v-if="termsError" class="register-page__terms-error">
                            {{ termsError }}
                        </p>

                        <div class="register-page__submit-wrap">
                            <button
                                class="register-page__submit"
                                type="submit"
                                :disabled="form.processing"
                            >
                                Create Account
                            </button>
                        </div>
                    </form>

                    <div class="register-page__post-form">
                        <p class="register-page__signin-text">
                            Already have an account?
                            <Link class="register-page__signin-link" :href="route('login')">
                                Sign In
                            </Link>
                        </p>
                        <div class="register-page__social-row">
                            <button
                                type="button"
                                class="register-page__social-btn"
                                disabled
                                title="GitHub sign-up is not configured"
                            >
                                <img
                                    class="register-page__social-img"
                                    :src="githubIconUrl"
                                    alt=""
                                    width="24"
                                    height="24"
                                />
                            </button>
                            <button
                                type="button"
                                class="register-page__social-btn"
                                disabled
                                title="Google sign-up is not configured"
                            >
                                <img
                                    class="register-page__social-img register-page__social-img--google"
                                    :src="googleIconUrl"
                                    alt=""
                                    width="20"
                                    height="20"
                                />
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        </div>
    </MarketingLayout>
</template>

<style lang="scss" src="../../../scss/register.scss"></style>
