<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Str;

class ThemeService
{
    public function store(array $data): Theme
    {
        $data['slug'] = Str::slug($data['name']);
        return Theme::create($data);
    }

    public function update(Theme $theme, array $data): Theme
    {
        $data['slug'] = Str::slug($data['name']); 
        $theme->update($data);
        return $theme;
    }
}
