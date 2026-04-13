<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Скрытая страница входа администратора.
 */
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

/** Açar sözini tekst görnüşinde görkezmek ýa-da gizlemek */
const showPassword = ref(false);

function submit() {
    form.post(route('admin.login'), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Administrator girişi" />

    <div class="relative flex min-h-dvh flex-col items-center justify-center overflow-hidden bg-slate-950 px-4 py-12">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(56,189,248,0.12),transparent_40%),radial-gradient(circle_at_85%_80%,rgba(14,165,233,0.16),transparent_42%),linear-gradient(to_bottom,rgba(15,23,42,0.9),rgba(2,6,23,1))]" />

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-700/60 bg-slate-900/55 p-8 shadow-[0_20px_50px_-24px_rgba(14,165,233,0.45)] backdrop-blur-xl">
            <div class="mb-6 text-center">
                <Link
                    href="/"
                    class="group inline-block rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-cyan-400/60"
                    aria-label="Baş sahypa"
                >
                    <h1
                        class="text-xl font-bold tracking-wide text-slate-100 transition group-hover:text-cyan-300 group-focus-visible:text-cyan-300"
                    >
                        DevKnowledge
                    </h1>
                </Link>
                <p class="mt-1 text-xs text-slate-400">Administrator paneli</p>
            </div>
            <form class="space-y-4" @submit.prevent="submit">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-300" for="email">E-poçta</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-xl border border-slate-600/70 bg-slate-900/65 px-3.5 py-2.5 text-sm text-slate-100 shadow-[inset_0_1px_0_rgba(148,163,184,0.12),0_8px_24px_-16px_rgba(15,23,42,1)] placeholder:text-slate-400 focus:border-cyan-400/80 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                        placeholder="E-poçtaňyzy ýazyň"
                        required
                        autocomplete="username"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-300" for="password">Açar söz</label>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="w-full rounded-xl border border-slate-600/70 bg-slate-900/65 py-2.5 pl-3.5 pr-11 text-sm text-slate-100 shadow-[inset_0_1px_0_rgba(148,163,184,0.12),0_8px_24px_-16px_rgba(15,23,42,1)] placeholder:text-slate-400 focus:border-cyan-400/80 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                            placeholder="Açar sözi ýazyň"
                            required
                            autocomplete="current-password"
                        />
                        <button
                            type="button"
                            class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800/90 hover:text-slate-200"
                            :aria-pressed="showPassword"
                            :aria-label="showPassword ? 'Açar sözini gizle' : 'Açar sözini görkez'"
                            @click="showPassword = !showPassword"
                        >
                            <svg
                                v-if="!showPassword"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.75"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg
                                v-else
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.75"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-cyan-500 focus:ring-cyan-500/50" />
                    Ýatda sakla
                </label>
                <button
                    type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 py-2.5 text-sm font-semibold text-white shadow-[0_12px_24px_-14px_rgba(6,182,212,0.9)] transition hover:from-cyan-400 hover:to-blue-400 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Ulgama gir
                </button>
            </form>
        </div>
    </div>
</template>
