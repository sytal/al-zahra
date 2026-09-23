<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Essential seeders run in every environment. Demo seeders (fake
     * articles/courses/research etc., see docs/PROJECT-BLUEPRINT.md
     * Part F2) are gated to non-production per docs/CLAUDE.md 22B.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            DirectorSeeder::class,
            SettingSeeder::class,
        ]);

        if (! app()->isProduction()) {
            $this->call([
                CategorySeeder::class,
                TagSeeder::class,
                ArticleSeeder::class,
                CourseSeeder::class,
                ResearchPaperSeeder::class,
                ResourceSeeder::class,
            ]);
        }
    }
}
