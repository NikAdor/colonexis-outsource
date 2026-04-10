<script setup>
import ColonexisLogo from '@/Components/ColonexisLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: true },
});

const page = usePage();

const path = computed(() => page.url.split('?')[0] || '/');

function navClass(prefix) {
    const p = path.value;
    if (prefix === 'courses') return p.startsWith('/courses');
    if (prefix === 'services') return p.startsWith('/services');
    if (prefix === 'works') return p.startsWith('/works');
    return false;
}
</script>

<template>
    <nav class="welcome-page__nav" aria-label="Primary">
        <div class="welcome-page__nav-left">
            <ColonexisLogo
                to="/"
                wrapper-class="welcome-page__logo"
                img-class="h-8 w-auto max-w-[200px] object-contain object-left sm:h-9"
            />
            <div class="welcome-page__nav-links">
                <Link
                    :href="route('courses.index')"
                    class="welcome-page__nav-link"
                    :class="{ 'is-active': navClass('courses') }"
                >
                    Courses
                </Link>
                <a href="/#instructors" class="welcome-page__nav-link">Instructors</a>
                <Link
                    :href="route('services')"
                    class="welcome-page__nav-link"
                    :class="{ 'is-active': navClass('services') }"
                >
                    Services
                </Link>
                <Link
                    :href="route('works')"
                    class="welcome-page__nav-link"
                    :class="{ 'is-active': navClass('works') }"
                >
                    Resources
                </Link>
            </div>
        </div>
        <div class="welcome-page__nav-actions">
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="welcome-page__btn-nav welcome-page__btn-nav--primary"
            >
                Login
            </Link>
            <Link
                v-if="canRegister"
                :href="route('register')"
                class="welcome-page__btn-nav welcome-page__btn-nav--accent"
            >
                Sign Up
            </Link>
        </div>
    </nav>
</template>
