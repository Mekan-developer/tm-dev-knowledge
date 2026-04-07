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
        class="flex min-h-[46px] flex-wrap items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2"
    >
        <button
            v-for="(tag, i) in modelValue"
            :key="`${tag}-${i}`"
            type="button"
            class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-800 hover:bg-indigo-100"
            @click="removeAt(i)"
        >
            {{ tag }} ×
        </button>
        <input
            v-model="draft"
            type="text"
            class="min-w-[8rem] flex-1 border-0 bg-transparent text-sm text-gray-900 outline-none placeholder:text-gray-400"
            placeholder="Add tag + Enter"
            @keydown.enter.prevent="commitTag"
        />
    </div>
</template>
