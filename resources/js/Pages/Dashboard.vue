<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    auth: Object,
    users: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold text-[#041228]">Welcome, {{ auth.user.name }}!</h3>
                        <p class="mt-2 text-gray-600">Role: <span class="font-semibold capitalize">{{ auth.user.role }}</span></p>
                    </div>
                </div>

                <div v-if="auth.user.role === 'admin'" class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-[#041228]">Users Management</h3>
                            <Link
                                :href="route('users.create')"
                                class="rounded-full bg-[#19427D] px-4 py-2 text-sm font-medium text-white hover:bg-[#041228] transition-colors"
                            >
                                Add User
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Role</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">{{ user.name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ user.email }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold capitalize"
                                                :class="{
                                                    'bg-[#38BDF8]/20 text-[#19427D]': user.role === 'admin',
                                                    'bg-[#38BDF8]/20 text-[#19427D]': user.role === 'staff',
                                                    'bg-[#F1F1F1] text-gray-700': user.role === 'user',
                                                }"
                                            >
                                                {{ user.role }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <Link
                                                    :href="route('users.edit', user.id)"
                                                    class="text-sm text-[#19427D] hover:text-[#041228]"
                                                >
                                                    Edit
                                                </Link>
                                                <Link
                                                    :href="route('users.destroy', user.id)"
                                                    method="delete"
                                                    as="button"
                                                    class="text-sm text-red-600 hover:text-red-800"
                                                    :only="['users']"
                                                >
                                                    Delete
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="users.links" class="mt-6">
                            <nav class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                                </div>
                                <div class="flex gap-2">
                                    <Link
                                        v-for="(link, index) in users.links"
                                        :key="index"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="rounded px-3 py-1 text-sm"
                                        :class="link.active ? 'bg-[#19427D] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        :disabled="!link.url"
                                    />
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>

                <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-lg">Welcome to the Colonexis platform. Contact an administrator for more access.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
