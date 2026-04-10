<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    events_enabled: Boolean,
    courses_enabled: Boolean,
    payment_method: {
        type: String,
        default: 'xendit',
    },
    xendit_key_configured: {
        type: Boolean,
        default: false,
    },
    paymongo_key_configured: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    events_enabled: props.events_enabled,
    courses_enabled: props.courses_enabled,
    payment_method: props.payment_method,
    xendit_secret_key: '',
    paymongo_secret_key: '',
});

const submit = () => {
    form.patch(route('admin.settings.update'));
};
</script>

<template>
    <Head title="Site features" />

    <DashboardLayout title="Site features">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-ink">
                Site features
            </h2>
            <p class="mt-1 text-sm text-ink-muted">
                Turn modules on or off for everyone. When disabled, sections stay hidden on the dashboard and course checkout is unavailable.
            </p>
        </template>

        <div class="max-w-xl overflow-hidden rounded-xl border border-border bg-surface shadow-sm">
            <form class="space-y-6 p-6 sm:p-8" @submit.prevent="submit">
                <div class="flex items-start justify-between gap-4 border-b border-border pb-6">
                    <div>
                        <h3 class="font-medium text-ink">Events</h3>
                        <p class="mt-1 text-sm text-ink-muted">
                            Show upcoming events on the dashboard when you add them.
                        </p>
                    </div>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            v-model="form.events_enabled"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-accent focus:ring-accent"
                        >
                        <span class="text-sm text-ink-muted">Enabled</span>
                    </label>
                </div>

                <div class="flex items-start justify-between gap-4 pb-2">
                    <div>
                        <h3 class="font-medium text-ink">Courses</h3>
                        <p class="mt-1 text-sm text-ink-muted">
                            Udemy-style catalog: free or paid courses. Users can enroll or purchase when this is on.
                        </p>
                    </div>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            v-model="form.courses_enabled"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-accent focus:ring-accent"
                        >
                        <span class="text-sm text-ink-muted">Enabled</span>
                    </label>
                </div>

                <div class="border-t border-border pt-6">
                    <label for="payment_method" class="font-medium text-ink">Payment method</label>
                    <p class="mt-1 text-sm text-ink-muted">
                        Choose the gateway used for paid course checkout.
                    </p>
                    <select
                        id="payment_method"
                        v-model="form.payment_method"
                        class="mt-3 block w-full rounded-lg border-border bg-surface-raised text-sm text-ink focus:border-accent focus:ring-accent"
                    >
                        <option value="xendit">Xendit</option>
                        <option value="paymongo">PayMongo</option>
                    </select>
                    <p v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600">
                        {{ form.errors.payment_method }}
                    </p>
                </div>

                <div class="border-t border-border pt-6">
                    <label for="xendit_secret_key" class="font-medium text-ink">Xendit API key</label>
                    <p class="mt-1 text-sm text-ink-muted">
                        Used when payment method is set to Xendit.
                        <span v-if="xendit_key_configured" class="font-medium text-green-700">Key configured.</span>
                    </p>
                    <input
                        id="xendit_secret_key"
                        v-model="form.xendit_secret_key"
                        type="password"
                        autocomplete="off"
                        placeholder="Enter new Xendit secret key"
                        class="mt-3 block w-full rounded-lg border-border bg-surface-raised text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:ring-accent"
                    >
                    <p v-if="form.errors.xendit_secret_key" class="mt-2 text-sm text-red-600">
                        {{ form.errors.xendit_secret_key }}
                    </p>
                </div>

                <div class="border-t border-border pt-6">
                    <label for="paymongo_secret_key" class="font-medium text-ink">PayMongo API key</label>
                    <p class="mt-1 text-sm text-ink-muted">
                        Used when payment method is set to PayMongo.
                        <span v-if="paymongo_key_configured" class="font-medium text-green-700">Key configured.</span>
                    </p>
                    <input
                        id="paymongo_secret_key"
                        v-model="form.paymongo_secret_key"
                        type="password"
                        autocomplete="off"
                        placeholder="Enter new PayMongo secret key"
                        class="mt-3 block w-full rounded-lg border-border bg-surface-raised text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:ring-accent"
                    >
                    <p v-if="form.errors.paymongo_secret_key" class="mt-2 text-sm text-red-600">
                        {{ form.errors.paymongo_secret_key }}
                    </p>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button
                        type="submit"
                        class="rounded-full bg-accent px-5 py-2 text-sm font-medium text-accent-fg transition-colors hover:bg-accent-hover disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
