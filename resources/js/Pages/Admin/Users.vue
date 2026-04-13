<script setup>
import AdminLayout from '@/Components/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Список контрибьюторов и модальное создание аккаунта.
 */
defineProps({
    contributors: {
        type: Array,
        required: true,
    },
});

const showModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

/** Открыть форму добавления контрибьютора. */
function openModal() {
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

/** Закрыть модальное окно. */
function closeModal() {
    showModal.value = false;
}

/** Создать контрибьютора (POST /admin/users). */
function submitContributor() {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
}

function formatDate(iso) {
    if (!iso) return '';
    try {
        return new Date(iso).toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    } catch {
        return '';
    }
}

/** Удаление контрибьютора с подтверждением. */
function removeContributor(id) {
    if (!confirm('Bu goşantçyny aýyrmak isleýärsiňizmi? Gollanmalary «Pozulan ulanyjy» awtory bilen galar.')) {
        return;
    }
    router.delete(route('admin.users.destroy', id), { preserveScroll: true });
}
</script>

<template>
    <AdminLayout>
        <Head title="Administrator — Ulanyjylar" />

        <div class="mx-auto max-w-6xl space-y-6 px-4 py-6 md:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Goşantçylar</h1>
                <button
                    type="button"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    @click="openModal"
                >
                    Goşantçy goş
                </button>
            </div>

            <div
                class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/80">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">At</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">E-poçta</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Gollanmalar</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Döredilen</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Hereketler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="c in contributors" :key="c.id" class="hover:bg-gray-50/80 dark:hover:bg-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ c.name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ c.email }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ c.guides_count }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ formatDate(c.created_at) }}</td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    type="button"
                                    class="text-sm font-semibold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                    @click="removeContributor(c.id)"
                                >
                                    Aýyr
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!contributors.length">
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Häzirlikçä goşantçy ýok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="add-contrib-title"
            @click.self="closeModal"
        >
            <div
                class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-900"
            >
                <h2 id="add-contrib-title" class="text-lg font-bold text-gray-900 dark:text-white">
                    Täze goşantçy
                </h2>
                <form class="mt-4 space-y-3" @submit.prevent="submitContributor">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">At</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">E-poçta</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required
                            autocomplete="off"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300">Açar söz</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required
                            autocomplete="new-password"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                            >Açar söz tassyklamasy</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required
                            autocomplete="new-password"
                        />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button
                            type="button"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            @click="closeModal"
                        >
                            Ýatyr
                        </button>
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Döret
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
