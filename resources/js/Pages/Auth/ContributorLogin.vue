<script setup>
import { Head, useForm } from '@inertiajs/vue3';

/**
 * Страница входа контрибьютора (/login), без ссылки в публичной шапке.
 */
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Contributor login" />

    <div
        class="flex min-h-dvh flex-col items-center justify-center bg-gray-100 px-4 py-12"
    >
        <div class="w-full max-w-sm rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
            <h1 class="mb-6 text-center text-xl font-bold text-gray-900">DevKnowledge</h1>
            <p class="mb-4 text-center text-sm text-gray-500">Contributor sign in</p>
            <form class="space-y-4" @submit.prevent="submit">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600" for="c-email"
                        >Email</label
                    >
                    <input
                        id="c-email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required
                        autocomplete="username"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600" for="c-password"
                        >Password</label
                    >
                    <input
                        id="c-password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required
                        autocomplete="current-password"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input v-model="form.remember" type="checkbox" class="rounded border-gray-300" />
                    Remember me
                </label>
                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</template>
