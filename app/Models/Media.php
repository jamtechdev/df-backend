<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'type',
        's3_bucket',
        's3_url',
        'file_size',
        'mime_type',
        'dimensions',
        'sort_order',
        'metadata',
        'is_gallery_visual',
        'uploaded_by',
        'status',
    ];

    protected $casts = [
        'dimensions' => 'array',
        'metadata' => 'array',
        'is_gallery_visual' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Polymorphic relation back to the owning model.
     */
    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * Relation to media translations.
     */
    public function translations()
    {
        return $this->hasMany(MediaTranslation::class);
    }

    /**
     * Get translation for a specific language or default.
     */
    public function translation($languageCode = 'en')
    {
        return $this->hasOne(MediaTranslation::class)->where('language_code', $languageCode);
    }
}
