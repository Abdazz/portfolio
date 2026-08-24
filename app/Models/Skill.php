<?php

namespace App\Models;

use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    /** @use HasFactory<SkillFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var list<string> */
    protected array $translatable = ['name'];

    protected $fillable = [
        'name',
        'category',
        'level',
        'icon',
        'order',
    ];
}
