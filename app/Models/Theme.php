<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'root',
        'slug',
    ];

    /**
     * Get all national park translations that use this theme.
     */
    public function translations()
    {
        return $this->hasMany(NationalParkTranslation::class, 'theme_id');
    }
}
