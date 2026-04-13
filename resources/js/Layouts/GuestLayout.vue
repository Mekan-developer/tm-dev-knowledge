<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-950"
    >
        <div class="absolute right-4 top-4 sm:right-6 sm:top-6">
            <ThemeToggle />
        </div>
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500 dark:text-gray-400" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:border dark:border-gray-700 dark:bg-gray-900 sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
