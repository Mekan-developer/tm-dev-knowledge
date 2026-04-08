<script setup>
import { Head, useForm } from '@inertiajs/vue3';

/**
 * Скрытая страница входа администратора.
 */
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(route('admin.login'), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Вход администратора" />

    <div class="relative flex min-h-dvh flex-col items-center justify-center overflow-hidden bg-slate-950 px-4 py-12">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(56,189,248,0.12),transparent_40%),radial-gradient(circle_at_85%_80%,rgba(14,165,233,0.16),transparent_42%),linear-gradient(to_bottom,rgba(15,23,42,0.9),rgba(2,6,23,1))]" />

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-700/60 bg-slate-900/55 p-8 shadow-[0_20px_50px_-24px_rgba(14,165,233,0.45)] backdrop-blur-xl">
            <div class="mb-6 text-center">
                <h1 class="text-xl font-bold tracking-wide text-slate-100">DevKnowledge</h1>
                <p class="mt-1 text-xs text-slate-400">Панель администратора</p>
            </div>
            <form class="space-y-4" @submit.prevent="submit">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-300" for="email">E-mail</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-xl border border-slate-600/70 bg-slate-900/65 px-3.5 py-2.5 text-sm text-slate-100 shadow-[inset_0_1px_0_rgba(148,163,184,0.12),0_8px_24px_-16px_rgba(15,23,42,1)] placeholder:text-slate-400 focus:border-cyan-400/80 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                        placeholder="Введите ваш e-mail"
                        required
                        autocomplete="username"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-300" for="password">Пароль</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-xl border border-slate-600/70 bg-slate-900/65 px-3.5 py-2.5 text-sm text-slate-100 shadow-[inset_0_1px_0_rgba(148,163,184,0.12),0_8px_24px_-16px_rgba(15,23,42,1)] placeholder:text-slate-400 focus:border-cyan-400/80 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                        placeholder="Введите пароль"
                        required
                        autocomplete="current-password"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-cyan-500 focus:ring-cyan-500/50" />
                    Запомнить меня
                </label>
                <button
                    type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 py-2.5 text-sm font-semibold text-white shadow-[0_12px_24px_-14px_rgba(6,182,212,0.9)] transition hover:from-cyan-400 hover:to-blue-400 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Войти в систему
                </button>
            </form>
        </div>
    </div>
</template>
