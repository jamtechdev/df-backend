<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProjectUserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\NationalParkController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::middleware(['auth'])->group(function () {

    Route::get('/user/{user}/roles', [ProjectUserController::class, 'getUserRoles']);

    Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'getPermissions']);
    Route::post('/roles/{role}/permissions/update', [RolePermissionController::class, 'updatePermissions']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Project Permissions
    Route::prefix('projects')->group(function () {
        Route::prefix('permissions')->group(function () {
            Route::get('/', [ProjectUserController::class, 'index'])->name('projects.permissions.index');
            Route::get('/data', [ProjectUserController::class, 'fetchData'])->name('projects.permissions.data');

            Route::put('/{id}', [ProjectUserController::class, 'update'])->name('projects.permissions.update');
            Route::delete('/{id}/{user_id}', [ProjectUserController::class, 'destroy'])->name('projects.permissions.destroy');
        });
    });

    // ✅ Users → Members & Visitors CRUD
    Route::prefix('users')->group(function () {

        // Members CRUD
        Route::prefix('members')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('members.index');
            Route::get('/create', [MemberController::class, 'create'])->name('members.create');
            Route::get('/data', [MemberController::class, 'fetchData'])->name('members.data');
            Route::post('/', [MemberController::class, 'store'])->name('members.store');
            Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
            Route::put('/{member}', [MemberController::class, 'update'])->name('members.update');
            Route::delete('/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        });

        // Visitors CRUD
        Route::prefix('visitors')->group(function () {
            Route::get('/', [VisitorController::class, 'index'])->name('visitors.index');
            Route::get('/data', [VisitorController::class, 'fetchData'])->name('visitors.data');
            Route::delete('/{visitor}', [VisitorController::class, 'destroy'])->name('visitors.destroy');
            Route::post('/{visitor}/toggle-block', [VisitorController::class, 'toggleIsBlocked'])->name('visitors.toggleBlock');
        });
    });

    // National Parks CRUD
    Route::prefix('national-parks')->group(function () {
        Route::get('/', [NationalParkController::class, 'index'])->name('national-parks.index');
        Route::get('/fetch-data', [NationalParkController::class, 'fetchData'])->name('national-parks.fetchData');
        Route::get('/fetch-categories-themes', [NationalParkController::class, 'fetchCategoriesAndThemes'])->name('national-parks.fetchCategoriesAndThemes');
        Route::get('/create', [NationalParkController::class, 'create'])->name('national-parks.create');
        Route::post('/', [NationalParkController::class, 'store'])->name('national-parks.store');
        Route::get('/{id}/edit', [NationalParkController::class, 'edit'])->name('national-parks.edit');
        Route::put('/{id}', [NationalParkController::class, 'update'])->name('national-parks.update');
        Route::delete('/{id}', [NationalParkController::class, 'destroy'])->name('national-parks.destroy');
    });
});
