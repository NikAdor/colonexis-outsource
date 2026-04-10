<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    courseTitle: { type: String, required: true },
    progressPct: { type: Number, default: 0 },
});

const page = usePage();
const appName = computed(() => page.props?.appName ?? 'Colonexis');
</script>

<template>
    <div class="learning-shell">
        <!-- TopNavBar -->
        <nav class="learning-shell__topbar">
            <div class="learning-shell__top-left">
                <Link :href="route('dashboard')" class="learning-shell__brand">
                    {{ appName }}
                </Link>
                <div class="learning-shell__toplinks">
                    <Link :href="route('courses.index')" class="learning-shell__toplink">
                        My Library
                    </Link>
                    <Link :href="route('dashboard')" class="learning-shell__toplink is-active">
                        Dashboard
                    </Link>
                    <Link :href="route('help')" class="learning-shell__toplink">
                        Resources
                    </Link>
                </div>
            </div>

            <div class="learning-shell__top-progress">
                <div class="learning-shell__top-progress-head">
                    <span class="truncate">{{ courseTitle }}</span>
                    <span>{{ progressPct }}% Complete</span>
                </div>
                <div class="learning-shell__top-progress-track">
                    <div class="learning-shell__top-progress-fill" :style="{ width: `${progressPct}%` }" />
                </div>
            </div>

            <div class="learning-shell__top-actions">
                <button type="button" class="learning-shell__iconbtn" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <Link :href="route('profile.edit')" class="learning-shell__iconbtn" aria-label="Settings">
                    <span class="material-symbols-outlined">settings</span>
                </Link>
                <div class="learning-shell__avatar" aria-hidden="true" />
            </div>
        </nav>

        <div class="learning-shell__body">
            <aside class="learning-shell__sidebar">
                <slot name="sidebar" />
            </aside>

            <main class="learning-shell__main">
                <slot />
            </main>
        </div>

        <div class="learning-shell__mobile-nav">
            <button class="learning-shell__mobile-item is-active" type="button">
                <span class="material-symbols-outlined">home</span>
                <span>Home</span>
            </button>
            <button class="learning-shell__mobile-item" type="button">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Curriculum</span>
            </button>
            <button class="learning-shell__mobile-item" type="button">
                <span class="material-symbols-outlined">forum</span>
                <span>Q&amp;A</span>
            </button>
            <Link :href="route('profile.edit')" class="learning-shell__mobile-item">
                <span class="material-symbols-outlined">person</span>
                <span>Profile</span>
            </Link>
        </div>
    </div>
</template>

