<?php

namespace App\Models;

use Database\Factories\CertificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Certification extends Model implements HasMedia
{
    /** @use HasFactory<CertificationFactory> */
    use HasFactory;

    use HasTranslations;
    use InteractsWithMedia;

    /** @var list<string> */
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'issuer',
        'issued_at',
        'credential_url',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('badge')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    }
}
