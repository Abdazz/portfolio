<?php

namespace App\Models;

use Database\Factories\AwardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Award extends Model
{
    /** @use HasFactory<AwardFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var list<string> */
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'issuer',
        'awarded_at',
        'url',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'awarded_at' => 'date',
        ];
    }
}
