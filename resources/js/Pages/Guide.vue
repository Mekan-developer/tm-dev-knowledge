<script setup>
import PublicLayout from '@/Components/PublicLayout.vue';
import StepList from '@/Components/StepList.vue';
import { useCategoryColor } from '@/composables/useCategoryColor';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Публичная страница гайда; Edit/Delete для владельца или админа.
 */
const props = defineProps({
    guide: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const { badgeClass } = useCategoryColor();

/** Показывать блок управления на странице гайда. */
const showActions = computed(() => props.guide.can_manage);

/** Редактирование через админ-маршруты для роли admin. */
const isAdminUser = computed(() => page.props.auth?.user?.role === 'admin');

const editHref = computed(() => {
    if (!showActions.value) return '#';
    if (isAdminUser.value) {
        return route('admin.guides.edit', props.guide.id);
    }
    return route('guides.edit', props.guide.id);
});

/** Удаление гайда с учётом роли. */
function destroyGuide() {
    if (!confirm('Delete this guide?')) return;
    if (isAdminUser.value) {
        router.delete(route('admin.guides.destroy', props.guide.id));
        return;
    }
    router.delete(route('guides.destroy', props.guide.id));
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
</script>

<template>
    <PublicLayout>
        <Head :title="guide.title" />

        <article class="mx-auto max-w-6xl px-4 py-6 md:px-8">
            <div class="mb-4">
                <Link
                    :href="route('home')"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900"
                >
                    <span class="text-lg leading-none" aria-hidden="true">←</span>
                    Back
                </Link>
            </div>

            <div class="space-y-4">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">
                    {{ guide.title }}
                </h1>

                <div class="flex flex-wrap items-center gap-2.5">
                    <span
                        class="rounded-md px-2.5 py-1 text-[11px] font-semibold"
                        :class="badgeClass(guide.category_color)"
                    >
                        {{ guide.category === 'JS/TS' ? 'JS·TS' : guide.category }}
                    </span>
                    <span
                        v-for="tag in guide.tags"
                        :key="tag"
                        class="rounded bg-gray-100 px-2 py-0.5 text-[11px] text-gray-600"
                    >
                        {{ tag }}
                    </span>
                </div>

                <p class="text-sm text-gray-500">
                    by {{ guide.author_name }} · {{ formatDate(guide.updated_at) }}
                </p>

                <div v-if="showActions" class="flex flex-wrap gap-3">
                    <Link
                        :href="editHref"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                    >
                        Edit
                    </Link>
                    <button
                        type="button"
                        class="text-sm font-semibold text-red-600 hover:text-red-800"
                        @click="destroyGuide"
                    >
                        Delete
                    </button>
                </div>

                <p class="max-w-3xl text-[15px] leading-relaxed text-gray-600">
                    {{ guide.description }}
                </p>

                <StepList :steps="guide.steps" />
            </div>
        </article>
    </PublicLayout>
</template>
