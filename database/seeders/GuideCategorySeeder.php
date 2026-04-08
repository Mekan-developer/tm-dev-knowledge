<?php

namespace Database\Seeders;

use App\Models\GuideCategory as GuideCategoryModel;
use Illuminate\Database\Seeder;

class GuideCategorySeeder extends Seeder
{
    /**
     * Создает базовый набор категорий гайдов.
     */
    public function run(): void
    {
        $categorySeed = [
            ['name' => 'Docker', 'color' => 'blue'],
            ['name' => 'Git', 'color' => 'amber'],
            ['name' => 'Linux', 'color' => 'green'],
            ['name' => 'Python', 'color' => 'orange'],
            ['name' => 'JS/TS', 'color' => 'purple'],
            ['name' => 'DevOps', 'color' => 'sky'],
            ['name' => 'Database', 'color' => 'yellow'],
            ['name' => 'PHP', 'color' => 'red'],
            ['name' => 'Laravel', 'color' => 'red'],
            ['name' => 'Other', 'color' => 'gray'],
        ];

        foreach ($categorySeed as $index => $item) {
            GuideCategoryModel::query()->updateOrCreate(
                ['name' => $item['name']],
                ['color' => $item['color'], 'sort_order' => $index + 1],
            );
        }
    }
}
