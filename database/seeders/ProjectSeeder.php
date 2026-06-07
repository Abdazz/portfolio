<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->forceDelete();

        $projects = [
            [
                'order' => 1,
                'featured' => true,
                'title' => [
                    'en' => 'BidOnMyTrip',
                    'fr' => 'BidOnMyTrip',
                ],
                'slug' => [
                    'en' => 'bidonmytrip',
                    'fr' => 'bidonmytrip',
                ],
                'summary' => [
                    'en' => 'An innovative travel planning and booking platform that lets travellers post their dream vacation and have travel agents compete in real time to offer the best tailored package.',
                    'fr' => 'Une plateforme innovante de planification et réservation de voyages où les voyageurs publient leur demande et les agences se font concurrence en temps réel pour proposer le meilleur forfait.',
                ],
                'body' => [
                    'en' => "BidOnMyTrip revolutionises the way travellers plan and book their holidays. Instead of searching dozens of agencies, travellers post a single request and receive competing offers from professional travel agents in real time.\n\nThe platform is built with Laravel and Angular and integrates Docker for containerisation, the OpenAI API and LangChain for intelligent itinerary suggestions, and Elasticsearch for fast, full-text search across destinations and packages.",
                    'fr' => "BidOnMyTrip révolutionne la façon dont les voyageurs planifient et réservent leurs vacances. Au lieu de consulter des dizaines d'agences, les voyageurs publient une seule demande et reçoivent des offres concurrentes d'agences professionnelles en temps réel.\n\nLa plateforme est développée avec Laravel et Angular, et intègre Docker pour la conteneurisation, l'API OpenAI et LangChain pour des suggestions d'itinéraires intelligentes, ainsi qu'Elasticsearch pour la recherche rapide en texte intégral.",
                ],
                'tech_stack' => ['Laravel', 'Angular', 'Docker', 'OpenAI API', 'LangChain', 'Elasticsearch'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://www.bidonmytrip.com'],
                ],
                'cover_alt' => [
                    'en' => 'BidOnMyTrip — travel bidding platform screenshot',
                    'fr' => 'BidOnMyTrip — capture d\'écran de la plateforme d\'enchères de voyages',
                ],
            ],
            [
                'order' => 2,
                'featured' => true,
                'title' => [
                    'en' => 'DigiClass',
                    'fr' => 'DigiClass',
                ],
                'slug' => [
                    'en' => 'digiclass',
                    'fr' => 'digiclass',
                ],
                'summary' => [
                    'en' => 'An online learning platform for African students (grades 6–12) offering courses, exercises, and quizzes, with a home tutoring marketplace connecting students\' parents with qualified teachers.',
                    'fr' => 'Une plateforme d\'apprentissage en ligne pour les élèves africains (6e–Terminale) proposant cours, exercices et quiz, avec une place de marché pour les cours particuliers à domicile.',
                ],
                'body' => [
                    'en' => "DigiClass was founded in 2018 with the mission of improving academic outcomes for students in Africa through ICT. The platform offers:\n\n- **Students (grades 6–12):** Access to courses, exercises, and quizzes to learn, practise, and self-evaluate.\n- **Parents:** Easily find a qualified teacher for home schooling, monitor progress, and pay online.\n\nDigiClass was selected for the Tony Elumelu Foundation #TEF 2018 entrepreneurship programme and won the AFIDBA award from Agence Française de Développement.",
                    'fr' => "DigiClass a été fondée en 2018 avec la mission d'améliorer le niveau académique des élèves en Afrique grâce aux TIC. La plateforme propose :\n\n- **Élèves (6e–Terminale) :** Accès à des cours, exercices et quiz pour apprendre, pratiquer et s'auto-évaluer.\n- **Parents :** Trouver facilement un enseignant qualifié pour les cours à domicile, suivre les progrès et payer en ligne.\n\nDigiClass a été sélectionnée par la Fondation Tony Elumelu (#TEF 2018) et a remporté le prix AFIDBA de l'Agence Française de Développement.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL', 'JavaScript'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://www.digiclass.co'],
                ],
                'cover_alt' => [
                    'en' => 'DigiClass — online learning platform screenshot',
                    'fr' => 'DigiClass — capture d\'écran de la plateforme d\'apprentissage en ligne',
                ],
            ],
            [
                'order' => 3,
                'featured' => false,
                'title' => [
                    'en' => 'Class Navigator',
                    'fr' => 'Class Navigator',
                ],
                'slug' => [
                    'en' => 'class-navigator',
                    'fr' => 'class-navigator',
                ],
                'summary' => [
                    'en' => 'A complete and feature-rich school management platform built for a Netherlands-based client, covering enrolments, scheduling, grades, and communication.',
                    'fr' => 'Une plateforme complète de gestion scolaire développée pour un client néerlandais, couvrant les inscriptions, les emplois du temps, les notes et la communication.',
                ],
                'body' => [
                    'en' => "Class Navigator is a full-featured school management system designed and implemented for a Netherlands-based education company. The platform handles the complete lifecycle of school administration: enrolment management, timetabling, grade tracking, and parent/teacher communication.\n\nBuilt with the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire) for a reactive, server-rendered experience with minimal JavaScript overhead.",
                    'fr' => "Class Navigator est un système complet de gestion scolaire conçu et développé pour une société éducative néerlandaise. La plateforme couvre l'ensemble du cycle d'administration scolaire : gestion des inscriptions, emplois du temps, suivi des notes et communication parents/enseignants.\n\nDéveloppée avec la stack TALL (Tailwind CSS, Alpine.js, Laravel, Livewire) pour une expérience réactive et rendue côté serveur avec un minimum de JavaScript.",
                ],
                'tech_stack' => ['Laravel', 'Livewire', 'Tailwind CSS', 'Alpine.js'],
                'links' => [
                    ['label' => 'Staging', 'url' => 'http://staging-classnavigator.bytebrew.nl'],
                ],
                'cover_alt' => [
                    'en' => 'Class Navigator — school management platform screenshot',
                    'fr' => 'Class Navigator — capture d\'écran de la plateforme de gestion scolaire',
                ],
            ],
            [
                'order' => 4,
                'featured' => false,
                'title' => [
                    'en' => 'AgriTube',
                    'fr' => 'AgriTube',
                ],
                'slug' => [
                    'en' => 'agritube',
                    'fr' => 'agritube',
                ],
                'summary' => [
                    'en' => 'A government agricultural training platform delivering video courses in local Burkinabè languages, built for the Ministry of Agriculture.',
                    'fr' => 'Une plateforme gouvernementale de formation agricole diffusant des cours vidéo en langues locales du Burkina Faso, développée pour le Ministère de l\'Agriculture.',
                ],
                'body' => [
                    'en' => "AgriTube is an agricultural training platform created for the Ministry of Agriculture, Hydro-Agricultural Development, Mechanization, and Animal and Fisheries Resources of Burkina Faso. The platform delivers video-based training content in local Burkinabè languages, making agricultural knowledge accessible to rural communities who are not fluent in French.\n\nBuilt with Laravel.",
                    'fr' => "AgriTube est une plateforme de formation agricole créée pour le Ministère de l'Agriculture, des Aménagements Hydro-Agricoles, de la Mécanisation et des Ressources Animales et Halieutiques du Burkina Faso. Elle diffuse des contenus de formation vidéo en langues locales burkinabè, rendant les connaissances agricoles accessibles aux communautés rurales.\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://agritube.gov.bf'],
                ],
                'cover_alt' => [
                    'en' => 'AgriTube — agricultural training platform screenshot',
                    'fr' => 'AgriTube — capture d\'écran de la plateforme de formation agricole',
                ],
            ],
            [
                'order' => 5,
                'featured' => false,
                'title' => [
                    'en' => 'Associations & Political Parties Virtual Counter (DGLPAP)',
                    'fr' => 'Guichet Unique des Associations et Partis Politiques (DGLPAP)',
                ],
                'slug' => [
                    'en' => 'dglpap-virtual-counter',
                    'fr' => 'guichet-unique-dglpap',
                ],
                'summary' => [
                    'en' => 'A government platform for the end-to-end administrative management of associations, political parties, and foundations in Burkina Faso, built for the Ministry of Territorial Administration.',
                    'fr' => 'Une plateforme gouvernementale de gestion administrative de bout en bout des associations, partis politiques et fondations du Burkina Faso, pour le Ministère de l\'Administration Territoriale.',
                ],
                'body' => [
                    'en' => "The Single Window for Associations (Guichet Unique) was developed for the Ministry of Territorial Administration, Decentralization and Security (MATDS). The platform manages the entire administrative lifecycle of associations, foreign associations, unions, foundations, political parties, and formations.\n\nKey features include:\n- Full circuit management of association recognition and follow-up.\n- NPO management within the framework of anti-money-laundering and counter-terrorism-financing regulations.\n- Integration with the Official Gazette for publishing recognition acts.\n- Multi-level deployment: central, regional, and provincial.\n\nBuilt with Laravel.",
                    'fr' => "Le Guichet Unique des Associations a été développé pour le Ministère de l'Administration Territoriale, de la Décentralisation et de la Sécurité (MATDS). La plateforme gère l'ensemble du circuit administratif des associations nationales, étrangères, des syndicats, fondations, partis et formations politiques.\n\nFonctionnalités principales :\n- Gestion complète du circuit de reconnaissance et de suivi des associations.\n- Gestion des OSBL dans le cadre de la lutte contre le blanchiment de capitaux et le financement du terrorisme.\n- Intégration avec le Journal Officiel pour la publication des actes de reconnaissance.\n- Déploiement multi-niveaux : central, régional et provincial.\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://www.dglpap.gov.bf'],
                ],
                'cover_alt' => [
                    'en' => 'DGLPAP virtual counter — government platform screenshot',
                    'fr' => 'Guichet unique DGLPAP — capture d\'écran de la plateforme gouvernementale',
                ],
            ],
            [
                'order' => 6,
                'featured' => false,
                'title' => [
                    'en' => 'ENAFA Online Registration Platform',
                    'fr' => 'Plateforme d\'Inscription en Ligne de l\'ENAFA',
                ],
                'slug' => [
                    'en' => 'enafa-registration-platform',
                    'fr' => 'plateforme-inscription-enafa',
                ],
                'summary' => [
                    'en' => 'An online admission and registration platform for the National Agricultural Training School (ENAFA) of Matourkou, enabling candidates to apply, pay fees, and track their admission online.',
                    'fr' => 'Une plateforme d\'inscription en ligne pour l\'École Nationale des Agents de l\'Agriculture (ENAFA) de Matourkou, permettant aux candidats de soumettre leurs dossiers, payer les frais et suivre leur admission.',
                ],
                'body' => [
                    'en' => "Developed within the E-Burkina project to digitalise admission and registration processes for the National Agricultural Training School (ENAFA) of Matourkou.\n\nThe platform enables:\n- Candidates to submit applications online, pay application fees, and pay tuition fees upon admission.\n- The ENAFA administration to manage applications and training sessions end-to-end.\n\nBuilt with Laravel.",
                    'fr' => "Développée dans le cadre du projet E-Burkina pour numériser les processus d'admission et d'inscription de l'ENAFA de Matourkou.\n\nLa plateforme permet :\n- Aux candidats de soumettre leurs dossiers en ligne, payer les frais de dossier et les frais de scolarité en cas d'admission.\n- À l'administration de l'ENAFA de gérer de bout en bout les candidatures et les sessions de formation.\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://inscriptions-enafa.gov.bf'],
                ],
                'cover_alt' => [
                    'en' => 'ENAFA registration platform screenshot',
                    'fr' => 'Capture d\'écran de la plateforme d\'inscription ENAFA',
                ],
            ],
            [
                'order' => 7,
                'featured' => false,
                'title' => [
                    'en' => 'E-Compétences',
                    'fr' => 'E-Compétences',
                ],
                'slug' => [
                    'en' => 'e-competences',
                    'fr' => 'e-competences',
                ],
                'summary' => [
                    'en' => 'A government IT skills directory listing companies and consultants in Burkina Faso, developed for the Ministry of Digital Transition.',
                    'fr' => 'Un répertoire gouvernemental des compétences informatiques des entreprises et consultants du Burkina Faso, développé pour le Ministère de la Transition Digitale.',
                ],
                'body' => [
                    'en' => "E-Compétences is a government platform developed for the Ministry of Digital Transition, Posts and Electronic Communications of Burkina Faso. It serves as an official directory of IT skills for companies and independent consultants in the country, facilitating the identification and procurement of technology expertise for both public and private sector projects.\n\nBuilt with PHP / Laravel.",
                    'fr' => "E-Compétences est une plateforme gouvernementale développée pour le Ministère de la Transition Digitale, des Postes et des Communications Électroniques du Burkina Faso. Elle constitue un répertoire officiel des compétences informatiques des entreprises et consultants indépendants du pays, facilitant l'identification et le recrutement d'expertises technologiques pour les projets des secteurs public et privé.\n\nDéveloppée avec PHP / Laravel.",
                ],
                'tech_stack' => ['PHP', 'Laravel', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'http://www.e-competences.gov.bf'],
                ],
                'cover_alt' => [
                    'en' => 'E-Compétences — government IT skills directory screenshot',
                    'fr' => 'E-Compétences — capture d\'écran du répertoire gouvernemental de compétences IT',
                ],
            ],
            [
                'order' => 8,
                'featured' => false,
                'title' => [
                    'en' => 'Lebonken',
                    'fr' => 'Lebonken',
                ],
                'slug' => [
                    'en' => 'lebonken',
                    'fr' => 'lebonken',
                ],
                'summary' => [
                    'en' => 'A marketplace platform allowing users to open an online store, sell and buy products.',
                    'fr' => 'Une plateforme marketplace permettant aux utilisateurs d\'ouvrir une boutique en ligne pour vendre et acheter des produits.',
                ],
                'body' => [
                    'en' => "Lebonken is a marketplace that allows users to create their own online store and sell or buy products. The platform provides sellers with easy-to-use store management tools and buyers with a clean browsing and purchasing experience.\n\nBuilt with Laravel.",
                    'fr' => "Lebonken est une marketplace qui permet aux utilisateurs de créer leur propre boutique en ligne et de vendre ou acheter des produits. La plateforme offre aux vendeurs des outils de gestion de boutique simples et aux acheteurs une expérience de navigation et d'achat fluide.\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://www.lebonken.com'],
                ],
                'cover_alt' => [
                    'en' => 'Lebonken marketplace screenshot',
                    'fr' => 'Capture d\'écran de la marketplace Lebonken',
                ],
            ],
            [
                'order' => 9,
                'featured' => false,
                'title' => [
                    'en' => 'GovPay — Government Electronic Payment Platform',
                    'fr' => 'GovPay — Plateforme de Paiement Électronique Gouvernementale',
                ],
                'slug' => [
                    'en' => 'govpay',
                    'fr' => 'govpay',
                ],
                'summary' => [
                    'en' => 'An electronic payment gateway for online services of the Burkina Faso public administration, supporting multiple payment methods.',
                    'fr' => 'Une passerelle de paiement électronique pour les services en ligne de l\'administration publique du Burkina Faso, supportant plusieurs méthodes de paiement.',
                ],
                'body' => [
                    'en' => "GovPay is an electronic payment platform designed to centralise and secure online payment for Burkina Faso's public administration services. The platform is built to embed multiple payment methods, allowing citizens to pay for government services through a single, unified gateway.\n\nBuilt with Laravel.",
                    'fr' => "GovPay est une plateforme de paiement électronique conçue pour centraliser et sécuriser le paiement en ligne des services de l'administration publique du Burkina Faso. La plateforme est conçue pour intégrer plusieurs méthodes de paiement, permettant aux citoyens de régler les services gouvernementaux via une passerelle unique et unifiée.\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [],
                'cover_alt' => [
                    'en' => 'GovPay — government payment platform screenshot',
                    'fr' => 'GovPay — capture d\'écran de la plateforme de paiement gouvernementale',
                ],
            ],
            [
                'order' => 10,
                'featured' => false,
                'title' => [
                    'en' => 'SMGES — Accounting Management Platform',
                    'fr' => 'SMGES — Plateforme de Gestion Comptable',
                ],
                'slug' => [
                    'en' => 'smges',
                    'fr' => 'smges',
                ],
                'summary' => [
                    'en' => 'An accounting management platform compliant with OHADA standards that automates the generation of all financial statements, accessible to non-accountants.',
                    'fr' => 'Une plateforme de gestion comptable conforme aux normes OHADA qui automatise la génération de tous les états financiers, accessible aux non-comptables.',
                ],
                'body' => [
                    'en' => "SMGES is an accounting management platform that allows companies to manage their accounting with ease, even without accounting expertise. The platform automatically generates all standard financial statements required under the OHADA (Organisation pour l'Harmonisation en Afrique du Droit des Affaires) accounting standards.\n\nBuilt with Laravel.",
                    'fr' => "SMGES est une plateforme de gestion comptable qui permet aux entreprises de gérer leur comptabilité facilement, même sans expertise comptable. La plateforme génère automatiquement tous les états financiers standard requis par les normes comptables OHADA (Organisation pour l'Harmonisation en Afrique du Droit des Affaires).\n\nDéveloppée avec Laravel.",
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL'],
                'links' => [],
                'cover_alt' => [
                    'en' => 'SMGES accounting platform screenshot',
                    'fr' => 'Capture d\'écran de la plateforme comptable SMGES',
                ],
            ],
            [
                'order' => 11,
                'featured' => false,
                'title' => [
                    'en' => 'MyStudentWeek',
                    'fr' => 'MyStudentWeek',
                ],
                'slug' => [
                    'en' => 'mystudentweek',
                    'fr' => 'mystudentweek',
                ],
                'summary' => [
                    'en' => 'A time management platform for universities and institutes, winner of POESAM, GenieTIC 2016, and the National Internet Week (NIS) Hackathon 2016.',
                    'fr' => 'Une plateforme de gestion du temps pour universités et instituts, lauréate du POESAM, du GenieTIC 2016 et du Hackathon de la Semaine Nationale de l\'Internet (SNI) 2016.',
                ],
                'body' => [
                    'en' => 'MyStudentWeek is a time management platform for universities and institutes, developed as part of a team. The project won three major awards: POESAM, GenieTIC 2016, and the National Internet Week (NIS) Hackathon 2016 in Burkina Faso, recognising it as an outstanding digital solution for education.',
                    'fr' => "MyStudentWeek est une plateforme de gestion du temps pour universités et instituts, développée en équipe. Le projet a remporté trois prix majeurs : le POESAM, le GenieTIC 2016 et le Hackathon de la Semaine Nationale de l'Internet (SNI) 2016 au Burkina Faso, le distinguant comme une solution numérique remarquable pour l'éducation.",
                ],
                'tech_stack' => ['PHP', 'JavaScript', 'MySQL'],
                'links' => [
                    ['label' => 'Live', 'url' => 'https://www.mystudentweek.com'],
                ],
                'cover_alt' => [
                    'en' => 'MyStudentWeek — time management platform screenshot',
                    'fr' => 'MyStudentWeek — capture d\'écran de la plateforme de gestion du temps',
                ],
            ],
        ];

        foreach ($projects as $data) {
            Project::create($data);
        }
    }
}
