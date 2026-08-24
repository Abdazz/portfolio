<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::updateOrCreate(
            ['id' => 1],
            [
                'full_name' => 'Abdoul-Aziz ZOROM',
                'headline' => [
                    'en' => 'Senior Full-Stack Developer & AI Expert',
                    'fr' => 'Développeur Full-Stack Senior & Expert IA',
                ],
                'bio' => [
                    'en' => 'Senior Full-Stack Developer and AI Expert with over 8 years of experience building scalable web platforms and intelligent systems. Specialising in Laravel, Livewire, Angular, and modern AI technologies including LangChain, RAG pipelines, and multi-agent orchestration. Currently CTO at YULCOM Technologies, leading national and international digital transformation projects across Burkina Faso and beyond. I blend creativity, precision, and innovation to deliver next-generation digital experiences.',
                    'fr' => 'Développeur Full-Stack Senior et Expert IA avec plus de 8 ans d\'expérience dans la conception de plateformes web évolutives et de systèmes intelligents. Je me spécialise dans Laravel, Livewire, Angular et les technologies IA modernes, notamment LangChain, les pipelines RAG et l\'orchestration multi-agents. Actuellement CTO chez YULCOM Technologies, je pilote des projets de transformation numérique nationaux et internationaux. Je mêle créativité, précision et innovation pour créer des expériences numériques de nouvelle génération.',
                ],
                'email' => 'abdoulazizzorom@gmail.com',
                'phone' => '+226 77-40-61-01',
                'location' => 'Karpala, Ouagadougou, Burkina Faso',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/abdoul-aziz-zorom',
                ],
            ],
        );

        $avatarPath = public_path('images/profile/avatar.jpg');

        if (file_exists($avatarPath) && $profile->getMedia('avatar')->isEmpty()) {
            $profile->addMedia($avatarPath)
                ->preservingOriginal()
                ->toMediaCollection('avatar');
        }
    }
}
