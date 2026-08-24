<?php

namespace App\Services\Resume;

use App\Models\Profile;
use Illuminate\Support\Collection;

/**
 * Immutable value object carrying all resume data for a given locale + template.
 *
 * @property-read Profile|null              $profile
 * @property-read Collection               $experiences   Ordered by `order`
 * @property-read Collection               $education     Ordered by `start_date` desc
 * @property-read Collection               $skills        Ordered by `order` (flat, not grouped)
 * @property-read Collection               $certifications Ordered by `issued_at` desc
 * @property-read Collection               $languages
 */
readonly class ResumeData
{
    public function __construct(
        public ?Profile $profile,
        public Collection $experiences,
        public Collection $education,
        public Collection $skills,
        public Collection $certifications,
        public Collection $languages,
        public string $locale,
        public string $template,
    ) {}
}
