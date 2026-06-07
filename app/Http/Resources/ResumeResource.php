<?php

namespace App\Http\Resources;

use App\Services\Resume\ResumeData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeResource extends JsonResource
{
    /**
     * @param  ResumeData  $resource
     */
    public function toArray(Request $request): array
    {
        /** @var ResumeData $data */
        $data = $this->resource;
        $locale = $data->locale;

        return [
            'locale' => $locale,
            'template' => $data->template,

            'profile' => $data->profile ? [
                'name' => $data->profile->full_name,
                'headline' => $data->profile->getTranslation('headline', $locale),
                'bio' => $data->profile->getTranslation('bio', $locale),
                'email' => $data->profile->email,
                'phone' => $data->profile->phone,
                'location' => $data->profile->location,
                'social_links' => $data->profile->social_links ?? [],
            ] : null,

            'experiences' => $data->experiences->map(fn ($e) => [
                'title' => $e->getTranslation('title', $locale),
                'company' => $e->company,
                'location' => $e->location,
                'start_date' => $e->start_date?->format('Y-m'),
                'end_date' => $e->end_date?->format('Y-m'),
                'description' => $e->getTranslation('description', $locale),
            ])->values(),

            'education' => $data->education->map(fn ($edu) => [
                'institution' => $edu->institution,
                'degree' => $edu->getTranslation('degree', $locale),
                'field' => $edu->getTranslation('field', $locale),
                'start_date' => $edu->start_date?->format('Y'),
                'end_date' => $edu->end_date?->format('Y'),
                'description' => $edu->getTranslation('description', $locale),
            ])->values(),

            'skills' => $data->skills->groupBy('category')->map(fn ($items, $category) => [
                'category' => $category,
                'items' => $items->map(fn ($s) => [
                    'name' => $s->getTranslation('name', $locale),
                    'level' => $s->level,
                    'icon' => $s->icon,
                ])->values(),
            ])->values(),

            'certifications' => $data->certifications->map(fn ($c) => [
                'title' => $c->getTranslation('title', $locale),
                'issuer' => $c->issuer,
                'issued_at' => $c->issued_at?->format('Y-m'),
                'credential_url' => $c->credential_url,
            ])->values(),

            'languages' => $data->languages->map(fn ($l) => [
                'name' => $l->getTranslation('name', $locale),
                'level' => $l->level,
            ])->values(),
        ];
    }
}
