<?php

namespace App\Services\Resume;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Profile;
use App\Models\Skill;

class ResumeBuilder
{
    /**
     * Aggregate all resume data from the database and return an immutable DTO.
     */
    public function build(string $locale, string $template = 'default'): ResumeData
    {
        return new ResumeData(
            profile: Profile::first(),
            experiences: Experience::orderBy('order')->get(),
            education: Education::orderBy('start_date', 'desc')->get(),
            skills: Skill::orderBy('order')->get(),
            certifications: Certification::orderBy('issued_at', 'desc')->get(),
            languages: LanguageSpoken::all(),
            locale: $locale,
            template: $template,
        );
    }

    /**
     * Compute a content hash from the last-updated timestamps of all resume models.
     * The hash changes whenever any resume record is modified.
     */
    public function contentHash(string $locale, string $template): string
    {
        $timestamps = [
            (string) Profile::max('updated_at'),
            (string) Experience::max('updated_at'),
            (string) Education::max('updated_at'),
            (string) Skill::max('updated_at'),
            (string) Certification::max('updated_at'),
            (string) LanguageSpoken::max('updated_at'),
        ];

        return md5($locale.$template.implode(',', $timestamps));
    }
}
