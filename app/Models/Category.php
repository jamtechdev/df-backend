<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['slug', 'icon', 'is_active'];

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function getNameAttribute()
    {
        // Assuming app locale is used for language_code
        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();
        return $translation ? $translation->name : null;
    }
}
