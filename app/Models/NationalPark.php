<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalPark extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'theme_id',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_featured'
    ];

    // Relations
    public function translations()
    {
        return $this->hasMany(NationalParkTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Polymorphic relation for media.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
