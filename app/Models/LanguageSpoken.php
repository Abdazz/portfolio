<?php

namespace App\Models;

use Database\Factories\LanguageSpokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LanguageSpoken extends Model
{
    /** @use HasFactory<LanguageSpokenFactory> */
    use HasFactory;

    use HasTranslations;

    protected $table = 'languages_spoken';

    /** @var list<string> */
    protected array $translatable = ['name'];

    protected $fillable = [
        'name',
        'level',
    ];
}
