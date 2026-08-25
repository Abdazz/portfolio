<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        Certification::query()->delete();

        $certifications = [
            // Professional certificates (most prestigious first)
            [
                'title' => ['en' => 'IBM RAG and Agentic AI Professional Certificate', 'fr' => 'Certificat Professionnel IBM RAG et IA Agentique'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/professional-cert/0TI07UB3R97Z',
                'badge' => 'ibm-rag-agentic-ai-professional.png',
            ],
            [
                'title' => ['en' => 'Machine Learning Specialization', 'fr' => 'Spécialisation Machine Learning'],
                'issuer' => 'Stanford Online / DeepLearning.AI',
                'issued_at' => '2026-07-01',
                'credential_url' => 'https://coursera.org/verify/specialization/QLEBODR8T1MZ',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Unsupervised Learning, Recommenders, Reinforcement Learning', 'fr' => 'Apprentissage non supervisé, systèmes de recommandation, apprentissage par renforcement'],
                'issuer' => 'DeepLearning.AI, Stanford Online / Coursera',
                'issued_at' => '2026-07-01',
                'credential_url' => 'https://coursera.org/verify/B2RLFLO7OJG3',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Advanced Learning Algorithms', 'fr' => 'Algorithmes d\'apprentissage avancés'],
                'issuer' => 'DeepLearning.AI, Stanford Online / Coursera',
                'issued_at' => '2026-07-01',
                'credential_url' => 'https://coursera.org/verify/9C33OXWI7WR7',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Supervised Machine Learning: Regression and Classification', 'fr' => 'Machine Learning supervisé : régression et classification'],
                'issuer' => 'DeepLearning.AI, Stanford Online / Coursera',
                'issued_at' => '2026-04-01',
                'credential_url' => 'https://coursera.org/verify/7LT2ZTBD6RWO',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Senior Laravel Developer Certification — Laravel 12', 'fr' => 'Certification Senior Laravel — Laravel 12'],
                'issuer' => 'Laravel Certifications',
                'issued_at' => '2026-01-01',
                'credential_url' => 'https://verifier.certificationforlaravel.org/74882ab6-ec3a-4505-9ad8-90ea8ca48592',
                'badge' => 'senior-laravel-certification.png',
            ],

            // IBM / Coursera specialisations & courses
            [
                'title' => ['en' => 'Building AI Agents and Agentic Workflows (Specialization)', 'fr' => 'Construction d\'agents IA et flux de travail agentiques (Spécialisation)'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/specialization/25UWS5XT1U5W',
                'badge' => 'ibm-building-ai-agents-specialization.png',
            ],
            [
                'title' => ['en' => 'RAG for Generative AI Applications (Specialization)', 'fr' => 'RAG pour les applications d\'IA générative (Spécialisation)'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://coursera.org/verify/specialization/9B3ND26WFMVJ',
                'badge' => 'ibm-rag-genai-specialization.png',
            ],
            [
                'title' => ['en' => 'Agentic AI with LangGraph, CrewAI, AutoGen and BeeAI', 'fr' => 'IA agentique avec LangGraph, CrewAI, AutoGen et BeeAI'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/4Q48X8P16GRG',
                'badge' => 'ibm-agentic-ai-langgraph-crewai-autogen-beeai.jpg',
            ],
            [
                'title' => ['en' => 'Agentic AI with LangChain and LangGraph', 'fr' => 'IA agentique avec LangChain et LangGraph'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/8QSNCLSZRCOX',
                'badge' => 'ibm-agentic-ai-langchain-langgraph.jpg',
            ],
            [
                'title' => ['en' => 'Fundamentals of Building AI Agents', 'fr' => 'Fondamentaux de la conception d\'agents IA'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/XV8QCC4ZXXZA',
                'badge' => 'ibm-fundamentals-building-ai-agents.jpg',
            ],
            [
                'title' => ['en' => 'Build Multimodal Generative AI Applications', 'fr' => 'Construire des applications d\'IA générative multimodales'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-09-01',
                'credential_url' => 'https://coursera.org/verify/XC1FVA9C7C3S',
                'badge' => 'ibm-build-multimodal-genai-apps.jpg',
            ],
            [
                'title' => ['en' => 'Advanced RAG with Vector Databases and Retrievers', 'fr' => 'RAG avancé avec bases de données vectorielles et retrievers'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://coursera.org/verify/JD347EJ3C4ZH',
                'badge' => 'ibm-advanced-rag-vector-databases.jpg',
            ],
            [
                'title' => ['en' => 'Vector Databases for RAG: An Introduction', 'fr' => 'Bases de données vectorielles pour le RAG : Introduction'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://coursera.org/verify/J124NYAVTFAC',
                'badge' => 'ibm-vector-databases-for-rag.jpg',
            ],
            [
                'title' => ['en' => 'Develop Generative AI Applications: Get Started', 'fr' => 'Développer des applications d\'IA générative : Démarrer'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://coursera.org/verify/TV4NIIBPB8CN',
                'badge' => 'ibm-develop-genai-applications.jpg',
            ],
            [
                'title' => ['en' => 'Build RAG Applications: Get Started', 'fr' => 'Construire des applications RAG : Démarrer'],
                'issuer' => 'IBM / Coursera',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://coursera.org/verify/4HBSUUNN3UKD',
                'badge' => 'ibm-build-rag-applications.jpg',
            ],

            // Udemy courses
            [
                'title' => ['en' => 'LLM, LangChain, RAG, Agent: Create Your AI-Powered Apps', 'fr' => 'LLM, LangChain, RAG, Agent : Créez vos apps boostées à l\'IA'],
                'issuer' => 'Udemy',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://ude.my/UC-055e0933-eed9-4515-b5fe-80a65af1a59e',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Getting Started with Spring and Spring Boot for Java', 'fr' => 'Bien débuter avec Spring et Spring Boot pour Java'],
                'issuer' => 'Udemy',
                'issued_at' => '2025-08-01',
                'credential_url' => 'https://ude.my/UC-253ab6b0-c045-4bfa-91b0-af29aa8bb3f6',
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Ansible Essentials: Simplicity in Automation', 'fr' => 'Ansible Essentials : Simplicité dans l\'automatisation'],
                'issuer' => 'Udemy / Red Hat',
                'issued_at' => '2020-03-01',
                'credential_url' => 'https://ude.my/UC-9fff03e5-bfb2-4dcc-b6b1-1aa2b0c46198',
                'badge' => null,
            ],

            // OpenClassrooms — Java
            [
                'title' => ['en' => 'Build a Java Application with Spring Boot', 'fr' => 'Créez une application Java avec Spring Boot'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2025-02-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Organise and Package a Java Application with Apache Maven', 'fr' => 'Organisez et packagez une application Java avec Apache Maven'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2025-02-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Develop Web Sites with Java EE', 'fr' => 'Développez des sites web avec Java EE'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2025-02-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Learn to Programme in Java', 'fr' => 'Apprenez à programmer en Java'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2025-01-01',
                'credential_url' => null,
                'badge' => null,
            ],

            // OpenClassrooms — Python
            [
                'title' => ['en' => 'Set Up Your Python Environment', 'fr' => 'Mettez en place votre environnement Python'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2024-05-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Learn the Basics of the Python Language', 'fr' => 'Apprenez les bases du langage Python'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2024-05-01',
                'credential_url' => null,
                'badge' => null,
            ],

            // OpenClassrooms — Angular
            [
                'title' => ['en' => 'Master Angular', 'fr' => 'Perfectionnez-vous sur Angular'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2023-01-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Deepen Your Angular Knowledge', 'fr' => 'Complétez vos connaissances sur Angular'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2022-12-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Get Started with Angular', 'fr' => 'Débutez avec Angular'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2022-11-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Get Started with React', 'fr' => 'Débutez avec React'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2022-04-01',
                'credential_url' => null,
                'badge' => null,
            ],

            // OpenClassrooms — PHP & DevOps
            [
                'title' => ['en' => 'Object-Oriented Programming in PHP', 'fr' => 'Programmez en orienté objet en PHP'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2020-08-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Optimise Your Deployment by Creating Containers with Docker', 'fr' => 'Optimisez votre déploiement en créant des conteneurs avec Docker'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2020-03-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Use Ansible to Automate Your Configuration Tasks', 'fr' => 'Utilisez Ansible pour automatiser vos tâches de configuration'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2020-02-01',
                'credential_url' => null,
                'badge' => null,
            ],
            [
                'title' => ['en' => 'Keys to Successful Web SEO', 'fr' => 'Les clés pour réussir son référencement web'],
                'issuer' => 'OpenClassrooms',
                'issued_at' => '2020-02-01',
                'credential_url' => null,
                'badge' => null,
            ],
        ];

        foreach ($certifications as $data) {
            $badgeFile = $data['badge'];
            unset($data['badge']);

            $certification = Certification::create($data);

            if ($badgeFile !== null) {
                $badgePath = public_path("images/certifications/{$badgeFile}");

                if (file_exists($badgePath)) {
                    $certification->addMedia($badgePath)
                        ->preservingOriginal()
                        ->toMediaCollection('badge');
                }
            }
        }
    }
}
