<?php

namespace Database\Seeders;

use App\Models\LanguageSpoken;
use Illuminate\Database\Seeder;

class LanguageSpokenSeeder extends Seeder
{
    public function run(): void
    {
        LanguageSpoken::query()->delete();

        $languages = [
            [
                'name' => ['en' => 'French', 'fr' => 'Français'],
                'level' => 'C2',
            ],
            [
                'name' => ['en' => 'English', 'fr' => 'Anglais'],
                'level' => 'C1',
            ],
            [
                'name' => ['en' => 'Mooré', 'fr' => 'Mooré'],
                'level' => 'native',
            ],
            [
                'name' => ['en' => 'Dioula', 'fr' => 'Dioula'],
                'level' => 'B1',
            ],
        ];

        foreach ($languages as $data) {
            LanguageSpoken::create($data);
        }
    }
}
