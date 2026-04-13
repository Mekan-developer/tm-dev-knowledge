<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useCategoryColor } from '@/composables/useCategoryColor';

/**
 * Карточка гайда: действия Edit/Delete для админ-режима или при can_manage на публичной части.
 */
const props = defineProps({
    guide: {
        type: Object,
        required: true,
    },
    /** Режим админ-дашборда: маршруты /admin/guides/*. */
    adminMode: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const { badgeClass } = useCategoryColor();

const showActions = computed(() => props.adminMode || props.guide.can_manage);

/** Текущий пользователь — админ (маршруты админки). */
const isAdminUser = computed(() => page.props.auth?.user?.role === 'admin');

const editHref = computed(() => {
    if (!showActions.value) return '#';
    if (props.adminMode || isAdminUser.value) {
        return route('admin.guides.edit', props.guide.id);
    }
    return route('guides.edit', props.guide.id);
});

/** Удаление гайда с учётом контекста admin / contributor. */
function destroyGuide() {
    if (!confirm('Bu gollanmany pozmak isleýärsiňizmi?')) return;
    if (props.adminMode || isAdminUser.value) {
        router.delete(route('admin.guides.destroy', props.guide.id));
        return;
    }
    router.delete(route('guides.destroy', props.guide.id));
}
</script>

<template>
    <div
        class="flex flex-col gap-2.5 rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm transition hover:border-gray-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-500 md:p-4"
    >
        <div class="flex items-start justify-between gap-2">
            <Link
                :href="route('guides.show', guide.id)"
                class="min-w-0 flex-1 text-left"
            >
                <h2 class="text-[15px] font-semibold leading-snug text-gray-900 line-clamp-2 dark:text-white">
                    {{ guide.title }}
                </h2>
            </Link>
            <span
                class="shrink-0 rounded-md px-2 py-0.5 text-[10px] font-semibold"
                :class="badgeClass(guide.category_color)"
            >
                {{ guide.category === 'JS/TS' ? 'JS·TS' : guide.category }}
            </span>
        </div>
        <Link :href="route('guides.show', guide.id)" class="text-left">
            <p class="text-[13px] leading-snug text-gray-500 line-clamp-2 dark:text-gray-400">
                {{ guide.description }}
            </p>
        </Link>
        <div class="flex flex-wrap gap-1.5">
            <span
                v-for="tag in guide.tags"
                :key="tag"
                class="rounded bg-gray-100 px-2 py-0.5 text-[11px] text-gray-600 dark:bg-gray-800 dark:text-gray-300"
            >
                {{ tag }}
            </span>
        </div>
        <p class="text-[11px] text-gray-400 dark:text-gray-500">
            {{ guide.author_name }} tarapyndan
        </p>
        <div v-if="showActions" class="flex gap-2 border-t border-gray-100 pt-2 dark:border-gray-700">
            <Link
                :href="editHref"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
            >
                Üýtget
            </Link>
            <button
                type="button"
                class="text-xs font-semibold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                @click="destroyGuide"
            >
                Poz
            </button>
        </div>
    </div>
</template>
