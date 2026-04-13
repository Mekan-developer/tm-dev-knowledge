<script setup>
import { Head, Link } from '@inertiajs/vue3';

/**
 * Ýörite görkezilmedik Laravel hoş geldiş sahypasy — gysgaça Türkmençe we gollanmalar baglanyşygy.
 */
defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});
</script>

<template>
    <Head title="Hoş geldiňiz" />
    <div
        class="flex min-h-dvh flex-col items-center justify-center bg-gray-50 px-6 py-12 text-gray-900 dark:bg-gray-950 dark:text-gray-100"
    >
        <h1 class="text-2xl font-bold dark:text-white">DevKnowledge</h1>
        <p class="mt-3 max-w-md text-center text-sm text-gray-600 dark:text-gray-300">
            Öwreniş we işläp düzüjilik boýunça gysgaça gollanmalar ýygymy.
        </p>
        <Link
            href="/"
            class="mt-8 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Gollanmalar
        </Link>
        <p v-if="canLogin" class="mt-8 text-xs text-gray-400 dark:text-gray-500">
            Laravel {{ laravelVersion }} · PHP {{ phpVersion }}
        </p>
    </div>
</template>
