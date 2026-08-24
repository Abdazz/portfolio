<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    /** @var list<string> */
    protected array $translatable = ['title', 'slug', 'summary', 'body', 'cover_alt'];

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'cover_alt',
        'tech_stack',
        'links',
        'featured',
        'order',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'links' => 'array',
            'featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    }

    /**
     * Register a 800 px-wide WebP thumbnail for every uploaded cover and gallery image.
     * The conversion runs in the queue so uploads stay fast.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(800)
            ->format('webp')
            ->performOnCollections('cover', 'gallery')
            ->queued();
    }
}
