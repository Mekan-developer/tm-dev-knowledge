<script setup>
import { ref } from 'vue';

/**
 * Шаги с нумерацией, inline-кодом и копированием.
 */
const props = defineProps({
    steps: {
        type: Array,
        required: true,
    },
});

const copyLabel = ref({});

/** Разбор текста шага на текст и `код`. */
function segmentsFor(step) {
    return String(step)
        .split(/(`[^`]+`)/g)
        .filter(Boolean)
        .map((part) =>
            part.startsWith('`') && part.endsWith('`')
                ? { kind: 'code', value: part.slice(1, -1) }
                : { kind: 'text', value: part },
        );
}

/** Текст для буфера обмена без обратных кавычек. */
function plainStepText(step) {
    return String(step).replace(/`([^`]+)`/g, '$1');
}

/** Копирование с индикацией «Copied!». */
async function copyStep(i, step) {
    const text = plainStepText(step);
    try {
        await navigator.clipboard.writeText(text);
    } catch {
        return;
    }
    copyLabel.value = { ...copyLabel.value, [i]: 'Copied!' };
    setTimeout(() => {
        const next = { ...copyLabel.value };
        delete next[i];
        copyLabel.value = next;
    }, 2000);
}
</script>

<template>
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Steps</h2>
        <div v-for="(step, i) in steps" :key="i" class="flex gap-3">
            <div
                class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white"
            >
                {{ i + 1 }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm leading-relaxed text-gray-700">
                    <template v-for="(seg, j) in segmentsFor(step)" :key="j">
                        <code
                            v-if="seg.kind === 'code'"
                            class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[13px] text-gray-900"
                        >{{ seg.value }}</code>
                        <span v-else>{{ seg.value }}</span>
                    </template>
                </p>
            </div>
            <button
                type="button"
                class="h-fit shrink-0 rounded-md px-2.5 py-1.5 text-xs font-semibold transition"
                :class="
                    copyLabel[i] === 'Copied!'
                        ? 'bg-emerald-100 text-emerald-800'
                        : 'bg-blue-50 text-blue-700 hover:bg-blue-100'
                "
                @click="copyStep(i, step)"
            >
                {{ copyLabel[i] ?? 'Copy' }}
            </button>
        </div>
    </div>
</template>
