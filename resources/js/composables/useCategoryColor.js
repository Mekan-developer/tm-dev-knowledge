export function useCategoryColor() {
    const colorMap = {
        blue: 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-200',
        amber: 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-200',
        green: 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-200',
        orange: 'bg-orange-100 text-orange-700 dark:bg-orange-950 dark:text-orange-200',
        purple: 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-200',
        sky: 'bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-200',
        yellow: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950 dark:text-yellow-200',
        red: 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-200',
        gray: 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200',
    };

    const badgeClass = (color) => colorMap[color] ?? colorMap.gray;

    return { badgeClass };
}
