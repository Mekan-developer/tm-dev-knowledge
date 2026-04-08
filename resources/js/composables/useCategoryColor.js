export function useCategoryColor() {
    const colorMap = {
        blue: 'bg-blue-100 text-blue-700',
        amber: 'bg-amber-100 text-amber-700',
        green: 'bg-green-100 text-green-700',
        orange: 'bg-orange-100 text-orange-700',
        purple: 'bg-purple-100 text-purple-700',
        sky: 'bg-sky-100 text-sky-700',
        yellow: 'bg-yellow-100 text-yellow-700',
        red: 'bg-red-100 text-red-700',
        gray: 'bg-gray-100 text-gray-700',
    };

    const badgeClass = (color) => colorMap[color] ?? colorMap.gray;

    return { badgeClass };
}
