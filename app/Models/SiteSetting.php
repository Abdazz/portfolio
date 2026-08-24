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
        'home_layout',
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
     *
     * `id` is intentionally excluded from $fillable, so firstOrCreate(['id' => 1], ...)
     * can never force the row's id via mass assignment; it must be set directly.
     */
    public static function instance(): static
    {
        if ($instance = static::find(1)) {
            return $instance;
        }

        $instance = new static([
            'meta_title' => ['en' => config('app.name'), 'fr' => config('app.name')],
            'meta_description' => ['en' => '', 'fr' => ''],
            'social_links' => [],
            'resume_template' => 'default',
            'home_layout' => 'gerold-01',
        ]);

        $instance->id = 1;
        $instance->save();

        return $instance;
    }
}
