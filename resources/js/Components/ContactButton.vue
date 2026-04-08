<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref, watch } from 'vue';

/**
 * Плавающая кнопка и модальное окно публичной формы обратной связи.
 */
const open = ref(false);
let closeTimer = null;

const page = usePage();
const success = computed(() => Boolean(page.props.flash?.contact_success));

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    website: '',
});

/**
 * Отправляет форму без перехода по страницам.
 */
function submit() {
    form.post(route('contact.send'), {
        preserveState: true,
        preserveScroll: true,
    });
}

/**
 * Закрывает модалку и очищает таймеры/ошибки.
 */
function closeModal() {
    open.value = false;
    form.clearErrors();
}

watch(success, (isSuccess) => {
    if (!isSuccess) return;
    if (closeTimer) {
        clearTimeout(closeTimer);
    }
    closeTimer = setTimeout(() => {
        form.reset();
        closeModal();
    }, 3000);
});

onBeforeUnmount(() => {
    if (closeTimer) {
        clearTimeout(closeTimer);
    }
});
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50">
        <button
            type="button"
            class="group relative flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg transition hover:bg-blue-700"
            @click="open = true"
        >
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path
                    d="M8 10h8M8 14h5m-7 6 2.4-2.4c.38-.38.95-.6 1.54-.6H19a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h1z"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            <span
                class="pointer-events-none absolute right-14 whitespace-nowrap rounded bg-gray-900 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
            >
                Contact me
            </span>
        </button>
    </div>

    <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="closeModal"
    >
        <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Contact me</h2>
                <button type="button" class="text-gray-500 hover:text-gray-700" @click="closeModal">Close</button>
            </div>

            <p
                v-if="success"
                class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700"
            >
                Your message has been sent! I will reply to your email soon.
            </p>
            <p
                v-if="form.errors.contact"
                class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"
            >
                {{ form.errors.contact }}
            </p>

            <form class="space-y-3" @submit.prevent="submit">
                <input
                    v-model="form.website"
                    type="text"
                    name="website"
                    autocomplete="off"
                    tabindex="-1"
                    style="display: none"
                />

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
                    <input v-model="form.subject" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    <p v-if="form.errors.subject" class="mt-1 text-xs text-red-600">{{ form.errors.subject }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Message</label>
                    <textarea v-model="form.message" rows="5" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    <p v-if="form.errors.message" class="mt-1 text-xs text-red-600">{{ form.errors.message }}</p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700" @click="closeModal">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
