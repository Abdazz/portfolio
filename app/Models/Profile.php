<?php

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Profile extends Model implements HasMedia
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    use HasTranslations;
    use InteractsWithMedia;

    /** @var list<string> */
    protected array $translatable = ['headline', 'bio'];

    protected $fillable = [
        'full_name',
        'headline',
        'bio',
        'email',
        'phone',
        'location',
        'social_links',
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
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    }
}
