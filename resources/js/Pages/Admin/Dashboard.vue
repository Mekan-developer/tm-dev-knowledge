<script setup>
import AdminLayout from '@/Components/AdminLayout.vue';
import CategoryFilter from '@/Components/CategoryFilter.vue';
import GuideCard from '@/Components/GuideCard.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

/**
 * Админ-дашборд: те же фильтры и сетка, с действиями на карточках.
 */
const props = defineProps({
    guides: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.q ?? '');
const guideCategoryId = ref(props.filters.guide_category_id ?? null);

let searchTimer;

watch(search, () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(
            route('admin.dashboard'),
            { q: search.value, guide_category_id: guideCategoryId.value },
            { preserveState: true, replace: true },
        );
    }, 300);
});

watch(guideCategoryId, () => {
    router.get(
        route('admin.dashboard'),
        { q: search.value, guide_category_id: guideCategoryId.value },
        { preserveState: true, replace: true },
    );
});
</script>

<template>
    <AdminLayout>
        <Head title="Admin — Guides" />

        <div class="mx-auto max-w-6xl space-y-4 px-4 py-6 md:px-8">
            <input
                v-model="search"
                type="search"
                class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-3 text-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                placeholder="Search by topic or technology..."
                autocomplete="off"
            />

            <CategoryFilter v-model="guideCategoryId" :categories="categories" />

            <div
                v-if="guides.data?.length"
                class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4 lg:grid-cols-3"
            >
                <GuideCard
                    v-for="g in guides.data"
                    :key="g.id"
                    :guide="g"
                    admin-mode
                />
            </div>

            <div
                v-else
                class="flex flex-col items-center rounded-xl border border-dashed border-gray-200 bg-white px-6 py-14 text-center"
            >
                <p class="text-gray-600">No guides match your filters.</p>
                <Link
                    :href="route('admin.guides.create')"
                    class="mt-4 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    + New Guide
                </Link>
            </div>

            <div
                v-if="guides.links?.length > 3"
                class="flex flex-wrap justify-center gap-1 pt-4"
            >
                <template v-for="(link, i) in guides.links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="min-w-9 rounded-md px-3 py-1.5 text-sm"
                        :class="
                            link.active
                                ? 'bg-blue-600 font-semibold text-white'
                                : 'bg-white text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50'
                        "
                        preserve-state
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="min-w-9 cursor-default rounded-md px-3 py-1.5 text-sm text-gray-400"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
