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
    ];

    protected $casts = [
        'dimensions' => 'array',
        'metadata' => 'array',
        'is_gallery_visual' => 'boolean',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
