<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'parent_id',
        'order',
        'title',
        'icon',
        'uri',
        'permission',
    ];

    /**
     * Menu belongs to a Project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Menu may have a parent menu (self relation).
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Menu may have many child menus (self relation).
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
