<script setup>
import ContactButton from '@/Components/ContactButton.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Публичный layout: только лого для гостей и админа на публичных страницах; для контрибьютора — «Мои гайды», добавление, выход.
 */
const page = usePage();

/** Текущий пользователь — контрибьютор (расширенная шапка). */
const isContributor = computed(() => page.props.auth?.user?.role === 'contributor');
</script>

<template>
    <div class="min-h-dvh bg-gray-50 text-gray-900">
        <header class="sticky top-0 z-30 border-b border-gray-200 bg-white/95 backdrop-blur">
            <div
                class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 md:px-8"
            >
                <Link href="/" class="text-lg font-bold text-gray-900"> DevKnowledge </Link>
                <div v-if="isContributor" class="flex flex-wrap items-center gap-2 md:gap-3">
                    <Link
                        :href="route('my-guides')"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        My guides
                    </Link>
                    <Link
                        :href="route('guides.create')"
                        class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        + Add Guide
                    </Link>
                    <Link
                        :href="route('logout')"
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
        <ContactButton />
    </div>
</template>
