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

        'published_at'
    ];

    protected $casts = [
        'park_stats' => 'array',
        'hero_image_content' => 'array',
        'closing_quote' => 'array',
        'published_at' => 'datetime',
    ];

    public function nationalPark()
    {
        return $this->belongsTo(NationalPark::class);
    }

    // Relation with Theme
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
