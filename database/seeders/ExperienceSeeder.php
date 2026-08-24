<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::query()->delete();

        $experiences = [
            [
                'order' => 1,
                'title' => ['en' => 'Chief Technology Officer (CTO)', 'fr' => 'Directeur Technique (CTO)'],
                'company' => 'YULCOM Technologies Burkina',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2024-04-01',
                'end_date' => null,
                'description' => [
                    'en' => 'Lead the technical direction and full-stack development of large-scale national and international digital transformation projects. Oversee multidisciplinary teams and contribute directly to backend and frontend development using Laravel, Livewire, Spring Boot, Angular, Docker, and CI/CD pipelines to deliver secure, scalable, and high-performance platforms.',
                    'fr' => 'Pilote la direction technique et le développement full-stack de projets de transformation numérique d\'envergure nationale et internationale. Encadrement d\'équipes pluridisciplinaires et contribution directe au développement backend et frontend avec Laravel, Livewire, Spring Boot, Angular, Docker et des pipelines CI/CD.',
                ],
            ],
            [
                'order' => 2,
                'title' => ['en' => 'Consultant — Full-Stack Developer', 'fr' => 'Consultant — Développeur Full-Stack'],
                'company' => 'Class Navigator (Netherlands)',
                'location' => 'Remote — Netherlands',
                'start_date' => '2022-11-01',
                'end_date' => null,
                'description' => [
                    'en' => 'Engineered an innovative travel planning and booking platform (BidOnMyTrip) allowing travel agents to compete in real time. Built with Laravel, Angular, Docker, OpenAI API, LangChain, and Elasticsearch. URL: https://www.bidonmytrip.com',
                    'fr' => 'Développement d\'une plateforme innovante de planification et réservation de voyages (BidOnMyTrip) permettant aux agents de voyager en temps réel. Réalisée avec Laravel, Angular, Docker, OpenAI API, LangChain et Elasticsearch. URL : https://www.bidonmytrip.com',
                ],
            ],
            [
                'order' => 3,
                'title' => ['en' => 'Lead Full-Stack Web Developer', 'fr' => 'Développeur Web Full-Stack Lead'],
                'company' => 'Yulcom Technologies',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2022-05-01',
                'end_date' => '2024-03-31',
                'description' => [
                    'en' => 'Led the design and development of complex web platforms using Laravel, Livewire, Spring Boot, Angular, and modern DevOps tools. Collaborated closely with cross-functional teams to deliver scalable, secure, and high-performance solutions for national and international projects.',
                    'fr' => 'Direction de la conception et du développement de plateformes web complexes avec Laravel, Livewire, Spring Boot, Angular et des outils DevOps modernes. Collaboration étroite avec des équipes transversales pour livrer des solutions performantes pour des projets nationaux et internationaux.',
                ],
            ],
            [
                'order' => 4,
                'title' => ['en' => 'Consultant — Full-Stack Developer', 'fr' => 'Consultant — Développeur Full-Stack'],
                'company' => 'Class Navigator (Netherlands)',
                'location' => 'Remote — Netherlands',
                'start_date' => '2023-04-01',
                'end_date' => '2023-06-30',
                'description' => [
                    'en' => 'Designed and implemented a complete and feature-rich school management platform built with the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire). URL: http://staging-classnavigator.bytebrew.nl/',
                    'fr' => 'Conception et développement d\'une plateforme complète de gestion scolaire avec la stack TALL (Tailwind CSS, Alpine.js, Laravel, Livewire). URL : http://staging-classnavigator.bytebrew.nl/',
                ],
            ],
            [
                'order' => 5,
                'title' => ['en' => 'Lead Full-Stack Developer', 'fr' => 'Développeur Full-Stack Lead'],
                'company' => 'Switch Maker',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2020-10-01',
                'end_date' => '2022-04-30',
                'description' => [
                    'en' => 'Led end-to-end development of dynamic web applications, contributing to both backend and frontend layers using Laravel, Vue.js, and Livewire. Designed and implemented scalable architectures, optimised performance, and ensured code quality through best development practices.',
                    'fr' => 'Direction du développement de bout en bout d\'applications web dynamiques, en contribuant aux couches backend et frontend avec Laravel, Vue.js et Livewire. Conception d\'architectures évolutives, optimisation des performances et assurance qualité du code.',
                ],
            ],
            [
                'order' => 6,
                'title' => ['en' => 'Laravel Trainer', 'fr' => 'Formateur Laravel'],
                'company' => 'Switch Maker',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2021-04-01',
                'end_date' => '2021-05-31',
                'description' => [
                    'en' => 'Trained over 100 people in Laravel and e-services development techniques within the E-Burkina Project, aimed at extending the virtual counter of Burkina Faso\'s public administration.',
                    'fr' => 'Formation de plus de 100 personnes à Laravel et aux techniques de développement de services en ligne dans le cadre du projet E-Burkina, pour l\'extension du guichet virtuel de l\'administration publique.',
                ],
            ],
            [
                'order' => 7,
                'title' => ['en' => 'Founder', 'fr' => 'Fondateur'],
                'company' => 'DigiClass',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2018-08-01',
                'end_date' => null,
                'description' => [
                    'en' => 'Founded DigiClass, an online learning platform with the mission of improving academic outcomes for students in Africa through ICT. Tony Elumelu Foundation #TEF 2018 laureate. Website: www.digiclass.co',
                    'fr' => 'Fondation de DigiClass, une plateforme d\'apprentissage en ligne dédiée à l\'amélioration du niveau académique des élèves en Afrique grâce aux TIC. Lauréat de la Fondation Tony Elumelu #TEF 2018. Site : www.digiclass.co',
                ],
            ],
            [
                'order' => 8,
                'title' => ['en' => 'Web Developer', 'fr' => 'Développeur Web'],
                'company' => 'Switch Maker',
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => '2017-09-01',
                'end_date' => '2018-01-31',
                'description' => [
                    'en' => 'Developed the governmental platform E-Compétences, a directory of IT skills of companies and consultants of Burkina Faso, for the Ministry of Digital Transition. Website: http://www.e-competences.gov.bf/',
                    'fr' => 'Développement de la plateforme gouvernementale E-Compétences, répertoire des compétences informatiques des entreprises et consultants du Burkina Faso, pour le Ministère de la Transition Digitale. Site : http://www.e-competences.gov.bf/',
                ],
            ],
            [
                'order' => 9,
                'title' => ['en' => 'Developer', 'fr' => 'Développeur'],
                'company' => 'MyStudentWeek',
                'location' => 'Burkina Faso',
                'start_date' => '2016-12-01',
                'end_date' => '2017-09-30',
                'description' => [
                    'en' => 'Member of the development team of MyStudentWeek, winner of POESAM, GenieTIC 2016, and the National Internet Week (NIS) Hackathon 2016. A time management platform for universities and institutes. Website: https://www.mystudentweek.com',
                    'fr' => 'Membre de l\'équipe de développement de MyStudentWeek, lauréat du POESAM, du GenieTIC 2016 et du Hackathon de la Semaine Nationale de l\'Internet (SNI) 2016. Plateforme de gestion du temps pour universités et instituts. Site : https://www.mystudentweek.com',
                ],
            ],
            [
                'order' => 10,
                'title' => ['en' => 'Trainer — MS Office', 'fr' => 'Formateur — MS Office'],
                'company' => 'Wel Multi-Services Nouna',
                'location' => 'Nouna, Burkina Faso',
                'start_date' => '2015-01-01',
                'end_date' => '2016-12-31',
                'description' => [
                    'en' => 'Trained over 100 people in MS Office during the National Internet Week (NIS) 2016.',
                    'fr' => 'Formation de plus de 100 personnes à MS Office dans le cadre de la Semaine Nationale de l\'Internet (SNI) 2016.',
                ],
            ],
        ];

        foreach ($experiences as $data) {
            Experience::create($data);
        }
    }
}
