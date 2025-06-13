<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    use HasFactory;

    public function getTable()
    {
        return np_table('content_blocks');
    }

    protected $fillable = [
        'national_park_translation_id',
        'section_type',
        'heading',
        'subheading',
        'icon',
        'title',
        'description',
        'sort_order',
        'is_active'
    ];
}
