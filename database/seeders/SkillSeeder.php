<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        Skill::query()->delete();

        $skills = [
            // Backend & Languages
            ['category' => 'Backend & Languages', 'order' => 1,  'name' => ['en' => 'PHP',        'fr' => 'PHP']],
            ['category' => 'Backend & Languages', 'order' => 2,  'name' => ['en' => 'Laravel',    'fr' => 'Laravel']],
            ['category' => 'Backend & Languages', 'order' => 3,  'name' => ['en' => 'Spring Boot', 'fr' => 'Spring Boot']],
            ['category' => 'Backend & Languages', 'order' => 4,  'name' => ['en' => 'Java',       'fr' => 'Java']],
            ['category' => 'Backend & Languages', 'order' => 5,  'name' => ['en' => 'Python',     'fr' => 'Python']],
            ['category' => 'Backend & Languages', 'order' => 6,  'name' => ['en' => 'JavaScript', 'fr' => 'JavaScript']],
            ['category' => 'Backend & Languages', 'order' => 7,  'name' => ['en' => 'C',          'fr' => 'Langage C']],

            // Frontend
            ['category' => 'Frontend',  'order' => 1,  'name' => ['en' => 'Livewire',    'fr' => 'Livewire']],
            ['category' => 'Frontend',  'order' => 2,  'name' => ['en' => 'Angular',     'fr' => 'Angular']],
            ['category' => 'Frontend',  'order' => 3,  'name' => ['en' => 'Vue.js',      'fr' => 'Vue.js']],
            ['category' => 'Frontend',  'order' => 4,  'name' => ['en' => 'React',       'fr' => 'React']],
            ['category' => 'Frontend',  'order' => 5,  'name' => ['en' => 'Alpine.js',   'fr' => 'Alpine.js']],
            ['category' => 'Frontend',  'order' => 6,  'name' => ['en' => 'Tailwind CSS', 'fr' => 'Tailwind CSS']],
            ['category' => 'Frontend',  'order' => 7,  'name' => ['en' => 'HTML & CSS',  'fr' => 'HTML & CSS']],

            // AI & Machine Learning
            ['category' => 'AI & Machine Learning', 'order' => 1,  'name' => ['en' => 'LangChain',       'fr' => 'LangChain']],
            ['category' => 'AI & Machine Learning', 'order' => 2,  'name' => ['en' => 'LangGraph',       'fr' => 'LangGraph']],
            ['category' => 'AI & Machine Learning', 'order' => 3,  'name' => ['en' => 'LlamaIndex',      'fr' => 'LlamaIndex']],
            ['category' => 'AI & Machine Learning', 'order' => 4,  'name' => ['en' => 'RAG Pipelines',   'fr' => 'Pipelines RAG']],
            ['category' => 'AI & Machine Learning', 'order' => 5,  'name' => ['en' => 'CrewAI',          'fr' => 'CrewAI']],
            ['category' => 'AI & Machine Learning', 'order' => 6,  'name' => ['en' => 'AutoGen (AG2)',   'fr' => 'AutoGen (AG2)']],
            ['category' => 'AI & Machine Learning', 'order' => 7,  'name' => ['en' => 'IBM BeeAI',       'fr' => 'IBM BeeAI']],
            ['category' => 'AI & Machine Learning', 'order' => 8,  'name' => ['en' => 'OpenAI API',      'fr' => 'OpenAI API']],
            ['category' => 'AI & Machine Learning', 'order' => 9,  'name' => ['en' => 'FAISS / ChromaDB', 'fr' => 'FAISS / ChromaDB']],
            ['category' => 'AI & Machine Learning', 'order' => 10, 'name' => ['en' => 'Ollama',          'fr' => 'Ollama']],
            ['category' => 'AI & Machine Learning', 'order' => 11, 'name' => ['en' => 'Groq API',        'fr' => 'Groq API']],

            // DevOps & Infrastructure
            ['category' => 'DevOps & Infrastructure', 'order' => 1, 'name' => ['en' => 'Docker',          'fr' => 'Docker']],
            ['category' => 'DevOps & Infrastructure', 'order' => 2, 'name' => ['en' => 'GitHub Actions',  'fr' => 'GitHub Actions']],
            ['category' => 'DevOps & Infrastructure', 'order' => 3, 'name' => ['en' => 'Ansible',         'fr' => 'Ansible']],
            ['category' => 'DevOps & Infrastructure', 'order' => 4, 'name' => ['en' => 'CI/CD',           'fr' => 'CI/CD']],
            ['category' => 'DevOps & Infrastructure', 'order' => 5, 'name' => ['en' => 'Linux / Ubuntu',  'fr' => 'Linux / Ubuntu']],

            // Databases & Search
            ['category' => 'Databases & Search', 'order' => 1, 'name' => ['en' => 'MySQL',         'fr' => 'MySQL']],
            ['category' => 'Databases & Search', 'order' => 2, 'name' => ['en' => 'PostgreSQL',    'fr' => 'PostgreSQL']],
            ['category' => 'Databases & Search', 'order' => 3, 'name' => ['en' => 'Elasticsearch', 'fr' => 'Elasticsearch']],
        ];

        foreach ($skills as $data) {
            Skill::create($data);
        }
    }
}
