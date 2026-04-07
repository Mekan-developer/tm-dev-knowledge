<script setup>
import AdminLayout from '@/Components/AdminLayout.vue';
import PublicLayout from '@/Components/PublicLayout.vue';
import TagInput from '@/Components/TagInput.vue';
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

const isAdminForm = computed(() => props.formContext === 'admin');

const cancelHref = computed(() => {
    const p = props.cancelTo.params ?? {};
    return route(props.cancelTo.name, p);
});

const form = useForm({
    title: props.guide?.title ?? '',
    category: props.guide?.category ?? props.categories[0] ?? 'Docker',
    description: props.guide?.description ?? '',
    tags: props.guide?.tags ? [...props.guide.tags] : [],
    steps: typeof props.guide?.steps === 'string' ? props.guide.steps : '',
});

watch(
    () => props.guide,
    (g) => {
        if (!g) return;
        form.title = g.title;
        form.category = g.category;
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
        <Head :title="isEdit ? 'Edit guide' : 'New guide'" />

        <div class="mx-auto max-w-6xl px-4 py-6 md:px-8">
            <form class="mx-auto max-w-2xl space-y-4" @submit.prevent="submit">
                <h1 class="text-xl font-bold text-gray-900">
                    {{ isEdit ? 'Edit guide' : 'New guide' }}
                </h1>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Category</label>
                    <select
                        v-model="form.category"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option v-for="c in categories" :key="c" :value="c">
                            {{ c === 'JS/TS' ? 'JS·TS' : c }}
                        </option>
                    </select>
                    <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">
                        {{ form.errors.category }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Tags</label>
                    <TagInput v-model="form.tags" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Steps (one per line)</label>
                    <textarea
                        v-model="form.steps"
                        rows="8"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 font-mono text-[13px] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.steps" class="mt-1 text-sm text-red-600">
                        {{ form.errors.steps }}
                    </p>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-2">
                    <Link
                        :href="cancelHref"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>

    <PublicLayout v-else>
        <Head :title="isEdit ? 'Edit guide' : 'New guide'" />

        <div class="mx-auto max-w-6xl px-4 py-6 md:px-8">
            <form class="mx-auto max-w-2xl space-y-4" @submit.prevent="submit">
                <h1 class="text-xl font-bold text-gray-900">
                    {{ isEdit ? 'Edit guide' : 'New guide' }}
                </h1>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Category</label>
                    <select
                        v-model="form.category"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option v-for="c in categories" :key="c" :value="c">
                            {{ c === 'JS/TS' ? 'JS·TS' : c }}
                        </option>
                    </select>
                    <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">
                        {{ form.errors.category }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Tags</label>
                    <TagInput v-model="form.tags" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-600">Steps (one per line)</label>
                    <textarea
                        v-model="form.steps"
                        rows="8"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-3 font-mono text-[13px] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.steps" class="mt-1 text-sm text-red-600">
                        {{ form.errors.steps }}
                    </p>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-2">
                    <Link
                        :href="cancelHref"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>
