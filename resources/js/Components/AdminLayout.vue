<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

/**
 * Layout админки: лого, все гайды, пользователи, новый гайд, выход; опционально PWA.
 */
const deferredPrompt = ref(null);

onMounted(() => {
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt.value = e;
    });
});

/** Установка PWA. */
async function installPwa() {
    if (!deferredPrompt.value) return;
    deferredPrompt.value.prompt();
    await deferredPrompt.value.userChoice;
    deferredPrompt.value = null;
}
</script>

<template>
    <div class="min-h-dvh bg-gray-50 text-gray-900">
        <header class="sticky top-0 z-30 border-b border-gray-200 bg-white/95 backdrop-blur">
            <div
                class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 md:px-8"
            >
                <Link :href="route('admin.dashboard')" class="text-lg font-bold text-gray-900">
                    DevKnowledge
                </Link>
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    <button
                        v-if="deferredPrompt"
                        type="button"
                        class="inline-flex rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                        @click="installPwa"
                    >
                        Install app
                    </button>
                    <Link
                        :href="route('admin.dashboard')"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        All guides
                    </Link>
                    <Link
                        :href="route('admin.users.index')"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        Users
                    </Link>
                    <Link
                        :href="route('admin.guides.create')"
                        class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        + New Guide
                    </Link>
                    <Link
                        :href="route('admin.logout')"
                        method="post"
                        as="button"
                        class="text-sm font-semibold text-gray-500 hover:text-gray-800"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>
