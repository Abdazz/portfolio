<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::query()->delete();

        $entries = [
            [
                'institution' => 'Aube Nouvelle University — Bobo-Dioulasso Campus',
                'degree' => [
                    'en' => "Bachelor's Degree in Computer Engineering",
                    'fr' => "Diplôme d'Ingénieur des Travaux Informatiques",
                ],
                'field' => [
                    'en' => 'Network Technologies and Computer Systems',
                    'fr' => 'Technologies des Réseaux et Systèmes Informatiques',
                ],
                'start_date' => '2014-09-01',
                'end_date' => '2017-07-01',
                'description' => [
                    'en' => 'Obtained the diploma of engineer of computer work, option network technologies and computer systems.',
                    'fr' => 'Obtention du diplôme d\'ingénieur des travaux informatiques, option technologies des réseaux et systèmes informatiques.',
                ],
            ],
            [
                'institution' => 'Lycée Provincial des Banwa',
                'degree' => [
                    'en' => 'Baccalaureate — Series D',
                    'fr' => 'Baccalauréat — Série D',
                ],
                'field' => [
                    'en' => 'Sciences',
                    'fr' => 'Sciences',
                ],
                'start_date' => '2011-09-01',
                'end_date' => '2014-07-01',
                'description' => [
                    'en' => 'Obtained the baccalaureate diploma, series D (science).',
                    'fr' => 'Obtention du baccalauréat, série D (sciences).',
                ],
            ],
        ];

        foreach ($entries as $data) {
            Education::create($data);
        }
    }
}
