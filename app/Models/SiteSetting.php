<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    /** @var list<string> */
    protected array $translatable = ['meta_title', 'meta_description'];

    protected $fillable = [
        'meta_title',
        'meta_description',
        'og_image',
        'twitter_handle',
        'social_links',
        'resume_template',
        'contact_email',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image_en')->singleFile();
        $this->addMediaCollection('og_image_fr')->singleFile();
    }

    public function ogImageUrl(string $locale): ?string
    {
        return $this->getFirstMediaUrl("og_image_{$locale}") ?: ($this->og_image ?: null);
    }

    /**
     * Always return the singleton record, creating it if it doesn't exist.
     */
    public static function instance(): static
    {
        /** @var static */
        return static::firstOrCreate(['id' => 1], [
            'meta_title' => ['en' => config('app.name'), 'fr' => config('app.name')],
            'meta_description' => ['en' => '', 'fr' => ''],
            'social_links' => [],
            'resume_template' => 'default',
        ]);
    }
}
