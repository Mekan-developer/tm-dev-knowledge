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
    if (!confirm('Delete this guide?')) return;
    if (props.adminMode || isAdminUser.value) {
        router.delete(route('admin.guides.destroy', props.guide.id));
        return;
    }
    router.delete(route('guides.destroy', props.guide.id));
}
</script>

<template>
    <div
        class="flex flex-col gap-2.5 rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm transition hover:border-gray-300 hover:shadow-md md:p-4"
    >
        <div class="flex items-start justify-between gap-2">
            <Link
                :href="route('guides.show', guide.id)"
                class="min-w-0 flex-1 text-left"
            >
                <h2 class="text-[15px] font-semibold leading-snug text-gray-900 line-clamp-2">
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
            <p class="text-[13px] leading-snug text-gray-500 line-clamp-2">
                {{ guide.description }}
            </p>
        </Link>
        <div class="flex flex-wrap gap-1.5">
            <span
                v-for="tag in guide.tags"
                :key="tag"
                class="rounded bg-gray-100 px-2 py-0.5 text-[11px] text-gray-600"
            >
                {{ tag }}
            </span>
        </div>
        <p class="text-[11px] text-gray-400">
            by {{ guide.author_name }}
        </p>
        <div v-if="showActions" class="flex gap-2 border-t border-gray-100 pt-2">
            <Link
                :href="editHref"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800"
            >
                Edit
            </Link>
            <button
                type="button"
                class="text-xs font-semibold text-red-600 hover:text-red-800"
                @click="destroyGuide"
            >
                Delete
            </button>
        </div>
    </div>
</template>
