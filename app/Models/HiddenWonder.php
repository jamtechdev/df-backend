<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiddenWonder extends Model
{
    public function getTable()
    {
        return np_table('hidden_wonders');
    }


    protected $fillable = [
        'national_park_translation_id',
        'section_heading',
        'section_title',
        'section_subtitle',
        'icon',
        'title',
        'subtitle',
        'description',
        'tip_heading',
        'tip_text',
        'quote',
        'sort_order',
        'is_active'
    ];
}
