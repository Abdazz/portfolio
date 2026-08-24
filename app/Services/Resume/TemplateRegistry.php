<?php

namespace App\Services\Resume;

class TemplateRegistry
{
    /** @var array<string, string> slug => view path */
    private array $templates = [
        'default' => 'resume.templates.default',
        'minimal' => 'resume.templates.minimal',
    ];

    /**
     * Register an additional template (e.g. from a service provider or package).
     */
    public function register(string $slug, string $view): void
    {
        $this->templates[$slug] = $view;
    }

    /**
     * Resolve the view path for a given template slug.
     * Falls back to 'default' if the slug is unknown.
     */
    public function view(string $slug): string
    {
        return $this->templates[$slug] ?? $this->templates['default'];
    }

    /**
     * All registered templates as slug => label pairs for Filament select options.
     *
     * @return array<string, string>
     */
    public function selectOptions(): array
    {
        return array_combine(
            array_keys($this->templates),
            array_map(fn (string $slug) => ucfirst($slug), array_keys($this->templates)),
        );
    }

    /**
     * All registered template slugs.
     *
     * @return list<string>
     */
    public function slugs(): array
    {
        return array_keys($this->templates);
    }
}
