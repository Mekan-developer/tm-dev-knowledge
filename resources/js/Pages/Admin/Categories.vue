<script setup>
import AdminLayout from '@/Components/AdminLayout.vue';
import { useCategoryColor } from '@/composables/useCategoryColor';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
});

const colors = ['blue', 'amber', 'green', 'orange', 'purple', 'sky', 'yellow', 'red', 'gray'];
const { badgeClass } = useCategoryColor();
const editId = ref(null);

const createForm = useForm({
    name: '',
    color: 'gray',
    sort_order: 0,
});

const editForm = useForm({
    name: '',
    color: 'gray',
    sort_order: 0,
});

const sortedCategories = computed(() => [...props.categories].sort((a, b) => a.sort_order - b.sort_order));

/** Создает новую категорию из формы в верхней части страницы. */
function submitCreate() {
    createForm.post(route('admin.categories.store'), {
        preserveScroll: true,
        onSuccess: () => createForm.reset('name'),
    });
}

/** Включает inline-режим редактирования строки категории. */
function startEdit(category) {
    editId.value = category.id;
    editForm.name = category.name;
    editForm.color = category.color;
    editForm.sort_order = category.sort_order;
}

/** Сохраняет изменения выбранной категории. */
function saveEdit(categoryId) {
    editForm.put(route('admin.categories.update', categoryId), {
        preserveScroll: true,
        onSuccess: () => {
            editId.value = null;
            editForm.reset();
        },
    });
}

/** Отменяет редактирование текущей категории. */
function cancelEdit() {
    editId.value = null;
    editForm.reset();
}

/** Удаляет категорию, если в ней нет гайдов. */
function destroyCategory(category) {
    if (category.guides_count > 0) return;
    if (!confirm('Удалить категорию?')) return;
    useForm({}).delete(route('admin.categories.destroy', category.id), { preserveScroll: true });
}

/** Смещает категорию вверх/вниз и отправляет новый порядок на сервер. */
function moveCategory(categoryId, direction) {
    const items = sortedCategories.value.map((item) => item.id);
    const index = items.findIndex((id) => id === categoryId);
    const targetIndex = index + direction;
    if (index < 0 || targetIndex < 0 || targetIndex >= items.length) return;
    [items[index], items[targetIndex]] = [items[targetIndex], items[index]];
    useForm({ ids: items }).post(route('admin.categories.reorder'), { preserveScroll: true });
}
</script>

<template>
    <AdminLayout>
        <Head title="Admin — Categories" />

        <div class="mx-auto max-w-6xl space-y-6 px-4 py-6 md:px-8">
            <section class="rounded-xl border border-gray-200 bg-white p-4">
                <h1 class="text-lg font-bold text-gray-900">Категории гайдов</h1>
                <p class="mt-1 text-sm text-gray-500">Добавьте новую категорию и выберите ее цвет.</p>

                <form class="mt-4 grid gap-3 md:grid-cols-4" @submit.prevent="submitCreate">
                    <input
                        v-model="createForm.name"
                        type="text"
                        placeholder="Например: Laravel"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm"
                    />

                    <div class="md:col-span-2 flex flex-wrap gap-2">
                        <button
                            v-for="color in colors"
                            :key="`create-${color}`"
                            type="button"
                            :title="color"
                            :class="[badgeClass(color), createForm.color === color ? 'ring-2 ring-black/20' : '', 'h-8 w-8 rounded-full']"
                            @click="createForm.color = color"
                        />
                    </div>

                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">
                        Add
                    </button>
                </form>
            </section>

            <section class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-3 py-2">Badge</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Slug</th>
                            <th class="px-3 py-2">Color</th>
                            <th class="px-3 py-2">Guides count</th>
                            <th class="px-3 py-2">Sort</th>
                            <th class="px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in sortedCategories" :key="category.id" class="border-t border-gray-100">
                            <td class="px-3 py-2">
                                <span class="rounded-full px-2 py-0.5 text-xs" :class="badgeClass(category.color)">
                                    {{ category.name }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                <template v-if="editId === category.id">
                                    <input v-model="editForm.name" class="w-full rounded border border-gray-300 px-2 py-1" />
                                </template>
                                <template v-else>{{ category.name }}</template>
                            </td>
                            <td class="px-3 py-2 text-gray-500">{{ category.slug }}</td>
                            <td class="px-3 py-2">
                                <template v-if="editId === category.id">
                                    <div class="flex flex-wrap gap-1">
                                        <button
                                            v-for="color in colors"
                                            :key="`edit-${category.id}-${color}`"
                                            type="button"
                                            :class="[badgeClass(color), editForm.color === color ? 'ring-2 ring-black/20' : '', 'h-6 w-6 rounded-full']"
                                            @click="editForm.color = color"
                                        />
                                    </div>
                                </template>
                                <template v-else>{{ category.color }}</template>
                            </td>
                            <td class="px-3 py-2">{{ category.guides_count }}</td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1">
                                    <button type="button" class="rounded border px-2 py-1" :disabled="index === 0" @click="moveCategory(category.id, -1)">↑</button>
                                    <button
                                        type="button"
                                        class="rounded border px-2 py-1"
                                        :disabled="index === sortedCategories.length - 1"
                                        @click="moveCategory(category.id, 1)"
                                    >
                                        ↓
                                    </button>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <template v-if="editId === category.id">
                                        <button type="button" class="text-blue-600" @click="saveEdit(category.id)">Save</button>
                                        <button type="button" class="text-gray-600" @click="cancelEdit">Cancel</button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="text-blue-600" @click="startEdit(category)">Edit</button>
                                        <button
                                            type="button"
                                            class="text-red-600 disabled:cursor-not-allowed disabled:text-gray-300"
                                            :disabled="category.guides_count > 0"
                                            :title="category.guides_count > 0 ? 'Нельзя удалить категорию с гайдами' : 'Удалить категорию'"
                                            @click="destroyCategory(category)"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </AdminLayout>
</template>
