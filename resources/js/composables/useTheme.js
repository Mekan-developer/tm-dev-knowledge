import { onMounted, ref } from 'vue';

const isDark = ref(false);

/**
 * Тёмная тема: класс на <html>, сохранение в localStorage, учёт prefers-color-scheme.
 */
export function useTheme() {
    const init = () => {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            isDark.value = true;
            document.documentElement.classList.add('dark');
        } else {
            isDark.value = false;
            document.documentElement.classList.remove('dark');
        }
    };

    const toggle = () => {
        isDark.value = !isDark.value;
        if (isDark.value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    onMounted(init);

    return { isDark, toggle };
}
