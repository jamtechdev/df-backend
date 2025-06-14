<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NationalPark;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalUsers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();
        $totalCategories = Category::count();
        $totalNationalParks = NationalPark::count();

        // Separate role counts
        $totalManager = Role::where('name', 'manager')->first()?->users()->count() ?? 0;
        $totalContentManager = Role::where('name', 'content_manager')->first()?->users()->count() ?? 0;
        $totalReader = Role::where('name', 'reader')->first()?->users()->count() ?? 0;

        return view('home', compact(
            'totalUsers',
            'totalCategories',
            'totalNationalParks',
        
            'totalManager',
            'totalContentManager',
            'totalReader'
        ));
    }
}
