/** Tailwind-классы бейджа категории. Ключи совпадают со значениями в БД. */
export const categoryBadgeClasses = {
    Docker: 'bg-blue-100 text-blue-800',
    Git: 'bg-amber-100 text-amber-800',
    Linux: 'bg-green-100 text-green-800',
    Python: 'bg-orange-100 text-orange-800',
    'JS/TS': 'bg-purple-100 text-purple-800',
    DevOps: 'bg-sky-100 text-sky-800',
    Database: 'bg-yellow-100 text-yellow-800',
    Other: 'bg-gray-100 text-gray-700',
};

/**
 * Классы бейджа по категории.
 * @param {string} category
 * @returns {string}
 */
export function badgeClassForCategory(category) {
    return categoryBadgeClasses[category] ?? categoryBadgeClasses.Other;
}

/**
 * Подпись на UI (JS·TS вместо JS/TS).
 * @param {string} category
 * @returns {string}
 */
export function categoryPillLabel(category) {
    if (category === 'JS/TS') return 'JS·TS';
    return category;
}
