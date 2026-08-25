<?php

namespace Database\Seeders;

use App\Models\Award;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
    public function run(): void
    {
        Award::query()->delete();

        $awards = [
            [
                'title' => ['en' => 'Founder Institute Programme — Participant', 'fr' => 'Programme Founder Institute — Participant'],
                'issuer' => 'Founder Institute',
                'awarded_at' => '2020-11-01',
                'url' => null,
            ],
            [
                'title' => ['en' => 'GenieTIC Winner — Young Tech Talent Competition', 'fr' => 'Lauréat GenieTIC — Concours des jeunes talents technologiques'],
                'issuer' => 'GenieTIC Burkina Faso',
                'awarded_at' => '2020-09-01',
                'url' => null,
            ],
            [
                'title' => ['en' => 'AFIDBA Winner — AFD Inclusive & Digital Business in Africa', 'fr' => 'Lauréat AFIDBA — Programme AFD pour l\'entrepreneuriat inclusif et numérique en Afrique'],
                'issuer' => 'AFD (Agence Française de Développement)',
                'awarded_at' => '2018-10-01',
                'url' => null,
            ],
            [
                'title' => ['en' => 'POESAM 2018 International Laureate — Special Content Award', 'fr' => 'Lauréat international POESAM 2018 — Prix Spécial Contenu'],
                'issuer' => 'POESAM',
                'awarded_at' => '2018-09-01',
                'url' => null,
            ],
            [
                'title' => ['en' => 'Tony Elumelu Foundation #TEF Entrepreneurship Programme Winner', 'fr' => 'Lauréat du Programme d\'Entrepreneuriat de la Fondation Tony Elumelu (#TEF)'],
                'issuer' => 'Tony Elumelu Foundation',
                'awarded_at' => '2018-03-01',
                'url' => null,
            ],
            [
                'title' => ['en' => 'Certificate in Entrepreneurship Training', 'fr' => 'Certificat de Formation en Entrepreneuriat'],
                'issuer' => 'Tony Elumelu Foundation',
                'awarded_at' => '2018-10-01',
                'url' => null,
            ],
        ];

        foreach ($awards as $data) {
            Award::create($data);
        }
    }
}
