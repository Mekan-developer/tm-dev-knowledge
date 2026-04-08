<script setup>
import { useCategoryColor } from '@/composables/useCategoryColor';

/**
 * Горизонтальные фильтры категорий без видимого скроллбара.
 */
defineProps({
    categories: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);
const { badgeClass } = useCategoryColor();
</script>

<template>
    <div class="hide-scrollbar -mx-1 overflow-x-auto px-1 pb-1">
        <div class="flex w-max max-w-full gap-2">
            <button
                :key="'all'"
                type="button"
                class="shrink-0 rounded-full px-3 py-2 text-xs font-medium transition"
                :class="
                    modelValue === null
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                "
                @click="emit('update:modelValue', null)"
            >
                All
            </button>

            <button
                v-for="c in categories"
                :key="c.id"
                type="button"
                class="shrink-0 rounded-full px-3 py-2 text-xs font-medium transition"
                :class="
                    modelValue === c.id
                        ? 'bg-blue-600 text-white'
                        : badgeClass(c.color)
                "
                @click="emit('update:modelValue', c.id)"
            >
                {{ c.name === 'JS/TS' ? 'JS·TS' : c.name }}
            </button>
        </div>
    </div>
</template>

<style scoped>
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
