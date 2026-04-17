<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Админ, пример контрибьютора и демо-гайды.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(GuideCategorySeeder::class);
        $this->call(GitGuideSeeder::class);
        $this->call(PhpGuideSeeder::class);
        $this->call(LaravelGuideSeeder::class);
    }
}
