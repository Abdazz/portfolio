<?php

namespace App\Services\Home;

class HomeLayoutRegistry
{
    /** @var array<string, string> slug => view path */
    private array $layouts = [
        'gerold-01' => 'home.layouts.gerold-01',
        'gerold-02' => 'home.layouts.gerold-02',
        'gerold-03' => 'home.layouts.gerold-03',
        'gerold-08' => 'home.layouts.gerold-08',
        'gerold-10' => 'home.layouts.gerold-10',
    ];

    private string $default = 'gerold-01';

    public function view(string $slug): string
    {
        return $this->layouts[$slug] ?? $this->layouts[$this->default];
    }

    /** @return array<string, string> */
    public function selectOptions(): array
    {
        return array_combine(
            array_keys($this->layouts),
            array_map(fn (string $slug) => 'Gerold '.substr($slug, -2), array_keys($this->layouts)),
        );
    }

    /** @return list<string> */
    public function slugs(): array
    {
        return array_keys($this->layouts);
    }
}
