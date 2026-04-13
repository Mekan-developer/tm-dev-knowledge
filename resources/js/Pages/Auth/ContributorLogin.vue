<script setup>
import ThemeToggle from '@/Components/ThemeToggle.vue';
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
    <Head title="Goşantçy girişi" />

    <div
        class="relative flex min-h-dvh flex-col items-center justify-center bg-gray-100 px-4 py-12 dark:bg-gray-950"
    >
        <div class="absolute right-4 top-4">
            <ThemeToggle />
        </div>
        <div
            class="w-full max-w-sm rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-900"
        >
            <h1 class="mb-6 text-center text-xl font-bold text-gray-900 dark:text-white">DevKnowledge</h1>
            <p class="mb-4 text-center text-sm text-gray-500 dark:text-gray-400">Goşantçy hasaby bilen giriş</p>
            <form class="space-y-4" @submit.prevent="submit">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300" for="c-email"
                        >E-poçta</label
                    >
                    <input
                        id="c-email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        required
                        autocomplete="username"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300" for="c-password"
                        >Açar söz</label
                    >
                    <input
                        id="c-password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        required
                        autocomplete="current-password"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <input
                        v-model="form.remember"
                        type="checkbox"
                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                    />
                    Ýatda sakla
                </label>
                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Giriş
                </button>
            </form>
        </div>
    </div>
</template>
