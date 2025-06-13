<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'language_code',
        'status',
        'overlay_title',
        'overlay_subtitle',
        'overlay_description',
        'title',
        'subtitle',
        'description',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
