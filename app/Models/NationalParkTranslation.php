<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalParkTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'national_park_id',
        'language_code',
        'status',
        'theme_id',
        'lead_quote',
        'title',
        'subtitle',
        'intro_text_first',
        'hero_image_content',
        'conservation_heading',
        'conservation_text',
        'park_stats',
        'visuals_title',
        'visuals_subtitle',
        'slug',
        'closing_quote',
        'meta_one',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'hero_image_content' => 'array',
        'park_stats' => 'array',
        'closing_quote' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function nationalPark()
    {
        return $this->belongsTo(NationalPark::class);
    }
}

