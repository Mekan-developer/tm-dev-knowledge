<script setup>
import AdminLayout from '@/Components/AdminLayout.vue';
import PublicLayout from '@/Components/PublicLayout.vue';
import TagInput from '@/Components/TagInput.vue';
import { useCategoryColor } from '@/composables/useCategoryColor';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

/**
 * Форма создания/редактирования гайда (админ или контрибьютор).
 */
const props = defineProps({
    guide: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        required: true,
    },
    formContext: {
        type: String,
        required: true,
    },
    cancelTo: {
        type: Object,
        required: true,
    },
});

const isEdit = computed(() => props.guide !== null);
const { badgeClass } = useCategoryColor();

const isAdminForm = computed(() => props.formContext === 'admin');

const cancelHref = computed(() => {
    const p = props.cancelTo.params ?? {};
    return route(props.cancelTo.name, p);
});

const form = useForm({
    title: props.guide?.title ?? '',
    guide_category_id: props.guide?.guide_category_id ?? props.categories[0]?.id ?? null,
    description: props.guide?.description ?? '',
    tags: props.guide?.tags ? [...props.guide.tags] : [],
    steps: typeof props.guide?.steps === 'string' ? props.guide.steps : '',
});

watch(
    () => props.guide,
    (g) => {
        if (!g) return;
        form.title = g.title;
        form.guide_category_id = g.guide_category_id;
        form.description = g.description;
        form.tags = [...g.tags];
        form.steps = typeof g.steps === 'string' ? g.steps : '';
    },
);

/** Отправка формы на админские или публичные маршруты гайдов. */
function submit() {
    if (isAdminForm.value) {
        if (isEdit.value) {
            form.put(route('admin.guides.update', props.guide.id));
            return;
        }
        form.post(route('admin.guides.store'));
        return;
    }
    if (isEdit.value) {
        form.put(route('guides.update', props.guide.id));
        return;
    }
    form.post(route('guides.store'));
}
</script>

<template>
    <AdminLayout v-if="isAdminForm">
        <Head :title="isEdit ? 'Gollanmany üýtgetmek' : 'Täze gollanma'" />

        <div class="mx-auto max-w-6xl px-4 py-6 md:px-8">
            <form class="mx-auto max-w-2xl space-y-4" @submit.prevent="submit">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ isEdit ? 'Gollanmany üýtgetmek' : 'Täze gollanma' }}
                </h1>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Ady</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Kategoriýa</label>
                    <select
                        v-model="form.guide_category_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >
                        <option v-for="c in categories" :key="c.id" :value="c.id">
                            {{ c.name === 'JS/TS' ? 'JS·TS' : c.name }}
                        </option>
                    </select>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span
                            v-for="c in categories"
                            :key="`admin-${c.id}`"
                            class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]"
                            :class="badgeClass(c.color)"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-current" />
                            {{ c.name === 'JS/TS' ? 'JS·TS' : c.name }}
                        </span>
                    </div>
                    <p v-if="form.errors.guide_category_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.guide_category_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Düşündiriş</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Bellikler</label>
                    <TagInput v-model="form.tags" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Ädimler (her setirde bir ädim)</label>
                    <textarea
                        v-model="form.steps"
                        rows="8"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 font-mono text-[13px] text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.steps" class="mt-1 text-sm text-red-600">
                        {{ form.errors.steps }}
                    </p>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-2">
                    <Link
                        :href="cancelHref"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Ýatyr
                    </Link>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Ýatla
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>

    <PublicLayout v-else>
        <Head :title="isEdit ? 'Gollanmany üýtgetmek' : 'Täze gollanma'" />

        <div class="mx-auto max-w-6xl px-4 py-6 md:px-8">
            <form class="mx-auto max-w-2xl space-y-4" @submit.prevent="submit">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ isEdit ? 'Gollanmany üýtgetmek' : 'Täze gollanma' }}
                </h1>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Ady</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Kategoriýa</label>
                    <select
                        v-model="form.guide_category_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >
                        <option v-for="c in categories" :key="c.id" :value="c.id">
                            {{ c.name === 'JS/TS' ? 'JS·TS' : c.name }}
                        </option>
                    </select>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span
                            v-for="c in categories"
                            :key="`public-${c.id}`"
                            class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]"
                            :class="badgeClass(c.color)"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-current" />
                            {{ c.name === 'JS/TS' ? 'JS·TS' : c.name }}
                        </span>
                    </div>
                    <p v-if="form.errors.guide_category_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.guide_category_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Düşündiriş</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Bellikler</label>
                    <TagInput v-model="form.tags" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Ädimler (her setirde bir ädim)</label>
                    <textarea
                        v-model="form.steps"
                        rows="8"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 font-mono text-[13px] text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <p v-if="form.errors.steps" class="mt-1 text-sm text-red-600">
                        {{ form.errors.steps }}
                    </p>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-2">
                    <Link
                        :href="cancelHref"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Ýatyr
                    </Link>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Ýatla
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>
