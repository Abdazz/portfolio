<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProfileSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            SkillSeeder::class,
            CertificationSeeder::class,
            AwardSeeder::class,
            LanguageSpokenSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
