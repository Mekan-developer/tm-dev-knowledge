<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';

/**
 * FAB + модалка контактов: адаптивно (на lg — две колонки), минимальный UI,
 * UX — блокировка скролла, Escape, фокус в первое поле, safe-area.
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

/** Закрытие по Escape (регистрируется при открытии). */
function onDocumentKeydown(e) {
    if (e.key === 'Escape' && open.value) {
        e.preventDefault();
        closeModal();
    }
}

/** Отправка формы без смены страницы. */
function submit() {
    form.post(route('contact.send'), {
        preserveState: true,
        preserveScroll: true,
    });
}

/** Закрывает модалку и сбрасывает ошибки. */
function closeModal() {
    open.value = false;
    form.clearErrors();
}

watch(open, (isOpen) => {
    if (typeof document === 'undefined') return;
    if (isOpen) {
        document.documentElement.style.overflow = 'hidden';
        document.addEventListener('keydown', onDocumentKeydown);
        nextTick(() => {
            if (!success.value) {
                document.getElementById('contact-name')?.focus();
            }
        });
    } else {
        document.documentElement.style.overflow = '';
        document.removeEventListener('keydown', onDocumentKeydown);
    }
});

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
    if (typeof document !== 'undefined') {
        document.documentElement.style.overflow = '';
        document.removeEventListener('keydown', onDocumentKeydown);
    }
});
</script>

<template>
    <!-- FAB: safe-area, минимальная тень -->
    <div
        class="fixed z-50"
        style="right: max(1rem, env(safe-area-inset-right, 0px)); bottom: max(1rem, env(safe-area-inset-bottom, 0px))"
    >
        <button
            type="button"
            class="flex h-12 w-12 min-h-[48px] min-w-[48px] touch-manipulation items-center justify-center rounded-full bg-teal-600 text-white shadow-md transition hover:bg-teal-700 active:bg-teal-800 sm:h-14 sm:w-14"
            aria-haspopup="dialog"
            :aria-expanded="open"
            aria-controls="contact-dialog"
            @click="open = true"
        >
            <svg class="h-6 w-6 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path
                    d="M8 10h8M8 14h5m-7 6 2.4-2.4c.38-.38.95-.6 1.54-.6H19a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h1z"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            <span class="sr-only">Habarlaşyk formasyny aç</span>
        </button>
    </div>

    <Teleport to="body">
        <div
            v-if="open"
            class="fixed inset-0 z-[60] flex flex-col justify-end sm:items-center sm:justify-center sm:p-4"
            role="presentation"
        >
            <div
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"
                aria-hidden="true"
                @click="closeModal"
            />

            <div
                id="contact-dialog"
                class="relative flex max-h-[min(100dvh,100vh)] w-full max-w-lg flex-col overflow-hidden rounded-t-2xl border border-slate-200 bg-white shadow-lg sm:max-h-[min(92dvh,92vh)] sm:max-w-5xl sm:rounded-2xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="contact-modal-title"
                aria-describedby="contact-modal-desc"
            >
                <header
                    class="flex h-12 shrink-0 items-center justify-between bg-slate-800 px-4 sm:h-14 sm:px-6"
                >
                    <span class="text-sm font-semibold text-white">DevKnowledge</span>
                    <button
                        type="button"
                        class="flex min-h-[44px] min-w-[44px] touch-manipulation items-center justify-center rounded-lg text-white/90 hover:bg-white/10 hover:text-white"
                        aria-label="Penjireni ýap"
                        @click="closeModal"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </header>

                <div
                    class="grid min-h-0 flex-1 overflow-hidden lg:grid-cols-[1fr_min(40%,420px)]"
                >
                    <!-- Форма: скролл только здесь на узких экранах -->
                    <div
                        class="order-2 min-h-0 overflow-y-auto overscroll-contain bg-white px-4 py-6 sm:px-8 sm:py-8 lg:order-1"
                        style="padding-bottom: max(1.5rem, env(safe-area-inset-bottom, 0px))"
                    >
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1 text-xs font-medium text-teal-800"
                        >
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-teal-500" aria-hidden="true" />
                            Biz onlaýn
                        </div>

                        <h2
                            id="contact-modal-title"
                            class="mt-4 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                        >
                            Biz bilen habarlaşyň
                        </h2>
                        <p id="contact-modal-desc" class="mt-1.5 text-sm leading-relaxed text-slate-500">
                            Adatça bir iş gününde jogap berýaris.
                        </p>

                        <p
                            v-if="success"
                            class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-900"
                            role="status"
                        >
                            Hatyňyz iberildi! Ýakynda e-poçtaňyza jogap bereris.
                        </p>
                        <p
                            v-else-if="form.errors.contact"
                            class="mt-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-900"
                            role="alert"
                        >
                            {{ form.errors.contact }}
                        </p>

                        <form class="mt-6 space-y-3 sm:space-y-4" @submit.prevent="submit">
                            <input
                                v-model="form.website"
                                type="text"
                                name="website"
                                autocomplete="off"
                                tabindex="-1"
                                class="hidden"
                            />

                            <div>
                                <label class="sr-only" for="contact-name">At</label>
                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
                                        aria-hidden="true"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                    </span>
                                    <input
                                        id="contact-name"
                                        v-model="form.name"
                                        type="text"
                                        autocomplete="name"
                                        class="min-h-[44px] w-full rounded-full border border-slate-200 bg-white py-2.5 pl-11 pr-3 text-base text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 sm:text-sm"
                                        placeholder="Adyňyz"
                                    />
                                </div>
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label class="sr-only" for="contact-email">E-poçta</label>
                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
                                        aria-hidden="true"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </span>
                                    <input
                                        id="contact-email"
                                        v-model="form.email"
                                        type="email"
                                        inputmode="email"
                                        autocomplete="email"
                                        class="min-h-[44px] w-full rounded-full border border-slate-200 bg-white py-2.5 pl-11 pr-3 text-base text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 sm:text-sm"
                                        placeholder="mysal@domen.com"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                            </div>

                            <div>
                                <label class="sr-only" for="contact-subject">Mowzuk</label>
                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
                                        aria-hidden="true"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                            />
                                        </svg>
                                    </span>
                                    <input
                                        id="contact-subject"
                                        v-model="form.subject"
                                        type="text"
                                        class="min-h-[44px] w-full rounded-full border border-slate-200 bg-white py-2.5 pl-11 pr-3 text-base text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 sm:text-sm"
                                        placeholder="Size nädip kömek edip bileris?"
                                    />
                                </div>
                                <p v-if="form.errors.subject" class="mt-1 text-xs text-red-600">{{ form.errors.subject }}</p>
                            </div>

                            <div>
                                <label class="sr-only" for="contact-message">Hat</label>
                                <textarea
                                    id="contact-message"
                                    v-model="form.message"
                                    rows="4"
                                    class="min-h-[7.5rem] w-full resize-y rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 sm:text-sm"
                                    placeholder="Hatyňyzy ýazyň…"
                                />
                                <p v-if="form.errors.message" class="mt-1 text-xs text-red-600">{{ form.errors.message }}</p>
                            </div>

                            <div class="flex flex-col gap-2 pt-1 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between sm:gap-3">
                                <button
                                    type="button"
                                    class="order-2 min-h-[44px] touch-manipulation text-left text-sm text-slate-500 underline-offset-2 hover:text-slate-800 hover:underline sm:order-1"
                                    @click="closeModal"
                                >
                                    Ýatyr
                                </button>
                                <button
                                    type="submit"
                                    class="order-1 min-h-[48px] w-full touch-manipulation rounded-full bg-teal-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-50 sm:order-2 sm:ml-auto sm:w-auto sm:min-w-[11rem]"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Iberilýär…' : 'Hat ibermek' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Декор только lg+: меньше шума на телефоне -->
                    <div
                        class="order-1 hidden min-h-0 flex-col items-center justify-center bg-slate-50 p-8 lg:flex"
                    >
                        <div
                            class="flex max-w-xs flex-col items-center rounded-2xl border border-slate-200 bg-white px-8 py-9"
                        >
                            <div class="flex items-end justify-center gap-3" aria-hidden="true">
                                <div class="h-12 w-12 rounded-full bg-amber-300" />
                                <div class="mb-0.5 h-10 w-10 rounded-full bg-cyan-300" />
                                <div class="mb-1 h-8 w-8 rounded-full bg-violet-300" />
                            </div>
                            <svg
                                class="mt-6 h-24 w-24 text-teal-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.25"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <p class="mt-5 text-center text-base font-semibold text-slate-800">Kömek etmäge taýýar</p>
                            <p class="mt-1 text-center text-sm text-slate-500">Çalt we dostlukly jogaplar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
