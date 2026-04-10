<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Account settings" />

    <DashboardLayout title="Profile">
        <section class="mb-10 flex flex-col items-end justify-between gap-6 md:flex-row">
            <div class="max-w-2xl">
                <h2 class="mb-2 font-manrope text-4xl font-extrabold tracking-tighter text-ed-primary">
                    Account settings
                </h2>
                <p class="font-body text-lg leading-relaxed text-ed-on-surface-variant">
                    Manage your identity, credentials, and workspace access. Keep your profile current and your password
                    strong.
                </p>
            </div>
        </section>

        <div class="editorial-account mx-auto max-w-3xl space-y-6">
            <div class="rounded-sm border border-ed-outline-variant/10 bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="rounded-sm border border-ed-outline-variant/10 bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="rounded-sm border border-ed-outline-variant/10 bg-ed-surface-container-lowest p-6 shadow-sm sm:p-8">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.material-symbols-outlined {
    font-family: 'Material Symbols Outlined', sans-serif;
    font-weight: normal;
    font-style: normal;
    line-height: 1;
    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;
    vertical-align: middle;
}

.editorial-account :deep(h2) {
    font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
    font-size: 1.125rem;
    line-height: 1.75rem;
    font-weight: 700;
    color: #000000;
}

.editorial-account :deep(header p) {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    line-height: 1.625;
    color: #46464e;
}

.editorial-account :deep(label span) {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #46464e;
}

.editorial-account :deep(input[type='text']),
.editorial-account :deep(input[type='email']),
.editorial-account :deep(input[type='password']) {
    margin-top: 0.25rem;
    width: 100%;
    border-radius: 0.375rem;
    border-style: none;
    background-color: #f3f3f3;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #1a1c1c;
    box-shadow: none;
    outline: none;
    transition:
        box-shadow 0.15s ease,
        background-color 0.15s ease;
}

.editorial-account :deep(input[type='text']:focus),
.editorial-account :deep(input[type='email']:focus),
.editorial-account :deep(input[type='password']:focus) {
    box-shadow: 0 0 0 2px rgb(51 95 155 / 0.3);
}

.editorial-account :deep(.border-gray-300) {
    border-width: 0;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-transparent.bg-gray-800) {
    border-radius: 0.375rem;
    border-width: 0;
    padding: 0.5rem 1rem;
    font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #ffffff;
    box-shadow:
        0 10px 15px -3px rgb(20 25 57 / 0.1),
        0 4px 6px -4px rgb(20 25 57 / 0.1);
    transition: opacity 0.15s ease;
    background: linear-gradient(45deg, #000000 0%, #141939 100%);
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-transparent.bg-gray-800:hover) {
    opacity: 0.9;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-transparent.bg-gray-800:focus-visible) {
    outline: 2px solid rgb(51 95 155 / 0.45);
    outline-offset: 2px;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-transparent.bg-red-600) {
    border-radius: 0.375rem;
    border-width: 0;
    background-color: #dc2626;
    padding: 0.5rem 1rem;
    font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #ffffff;
    transition: background-color 0.15s ease;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-transparent.bg-red-600:hover) {
    background-color: #ef4444;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-gray-300.bg-white) {
    border-radius: 0.375rem;
    border: 1px solid rgb(199 197 207 / 0.35);
    background-color: #ffffff;
    padding: 0.5rem 1rem;
    font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #1a1c1c;
    box-shadow: none;
    transition: background-color 0.15s ease;
}

.editorial-account :deep(button.inline-flex.items-center.rounded-md.border.border-gray-300.bg-white:hover) {
    background-color: #e8e8e8;
}

.editorial-account :deep(p.text-sm.text-gray-600),
.editorial-account :deep(p.text-sm.text-gray-800) {
    color: #46464e;
}

.editorial-account :deep(a.rounded-md.text-sm) {
    color: #335f9b;
    text-decoration: underline;
    text-decoration-color: rgb(51 95 155 / 0.4);
}

.editorial-account :deep(a.rounded-md.text-sm:hover) {
    color: #000000;
}

.editorial-account :deep(.text-green-600) {
    color: #15803d;
}
</style>
