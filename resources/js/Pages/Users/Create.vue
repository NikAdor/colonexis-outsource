<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
});

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head title="Add User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add User
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#19427D] focus:ring-[#19427D]"
                                    required
                                />
                                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#19427D] focus:ring-[#19427D]"
                                    required
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#19427D] focus:ring-[#19427D]"
                                    required
                                />
                                <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#19427D] focus:ring-[#19427D]"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <select
                                    v-model="form.role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#19427D] focus:ring-[#19427D]"
                                >
                                    <option value="user">User</option>
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-full bg-[#19427D] px-4 py-2 text-sm font-medium text-white hover:bg-[#041228] transition-colors disabled:opacity-50"
                                >
                                    Create User
                                </button>
                                <Link
                                    :href="route('users.index')"
                                    class="text-sm text-gray-600 hover:text-gray-900"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
