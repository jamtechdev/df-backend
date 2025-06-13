<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalPark extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'theme_id'];

    public function translations()
    {
        return $this->hasMany(NationalParkTranslation::class);
    }
}
