<script setup>
import { ref } from 'vue';

/**
 * Теги: Enter добавляет, клик удаляет.
 */
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);

const draft = ref('');

/** Добавить тег из поля ввода. */
function commitTag() {
    const t = draft.value.trim();
    if (!t || props.modelValue.includes(t)) {
        draft.value = '';
        return;
    }
    emit('update:modelValue', [...props.modelValue, t]);
    draft.value = '';
}

/** Удалить тег по индексу. */
function removeAt(i) {
    emit(
        'update:modelValue',
        props.modelValue.filter((_, idx) => idx !== i),
    );
}
</script>

<template>
    <div
        class="flex min-h-[46px] flex-wrap items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-600 dark:bg-gray-800"
    >
        <button
            v-for="(tag, i) in modelValue"
            :key="`${tag}-${i}`"
            type="button"
            class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-800 hover:bg-indigo-100 dark:bg-indigo-950 dark:text-indigo-200 dark:hover:bg-indigo-900"
            @click="removeAt(i)"
        >
            {{ tag }} ×
        </button>
        <input
            v-model="draft"
            type="text"
            class="min-w-[8rem] flex-1 border-0 bg-transparent text-sm text-gray-900 outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-500"
            placeholder="Bellik goş + Enter"
            @keydown.enter.prevent="commitTag"
        />
    </div>
</template>
