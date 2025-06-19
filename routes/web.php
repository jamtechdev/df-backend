<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProjectUserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\NationalParkController;
use App\Http\Controllers\Admin\NationalParkTranslationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\HiddenWonderController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\MediaTranslationController;
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

    // Media CRUD
    Route::prefix('media')->group(function () {
        Route::get('/{id}', [MediaController::class, 'index'])->name('media.index');
        Route::get('{id}/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('{id}/store', [MediaController::class, 'store'])->name('media.store');
        Route::get('{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::post('{id}/{media}/update', [MediaController::class, 'update'])->name('media.update');
        Route::delete('{id}/{media}/delete', [MediaController::class, 'destroy'])->name('media.destroy');




        Route::prefix('/translations')->group(function () {
            Route::get('{media}/', [MediaTranslationController::class, 'index'])->name('media.translations.index');
            Route::get('{media_translation}/edit', [MediaTranslationController::class, 'edit'])->name('media.translations.edit');
            Route::post('{media}/store', [MediaTranslationController::class, 'store'])->name('media.translations.store');
            Route::post('{media_translation}/update-trans', [MediaTranslationController::class, 'updateTranslation'])->name('media.translations.update');
            Route::delete('{media_translation}/delete', [MediaTranslationController::class, 'destroy'])->name('media.translations.destroy');
        });


        // 🔥 Toggle is_gallery_visual (for checkbox toggle in DataTable)
        Route::post('/toggle-gallery-visual', [MediaController::class, 'toggleGalleryVisual'])->name('media.toggle-gallery-visual');
        // 🔥 Toggle status switch (new toggle method)
        Route::post('/toggle-status-switch', [MediaController::class, 'toggleStatusSwitch'])->name('media.toggle-status-switch');
    });

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
        Route::prefix('national-parks')->group(function () {
            Route::get('/', [NationalParkController::class, 'index'])->name('national-parks.index');
        });
        // National Park Translations CRUD
        Route::prefix('translations')->group(function () {
            Route::get('/{id}', [NationalParkTranslationController::class, 'index'])->name('national-parks.translations.index');
            Route::get('/{id?}/create', [NationalParkTranslationController::class, 'create'])->name('national-parks.translations.create');
            Route::post('/upload-image', [NationalParkTranslationController::class, 'uploadImage'])->name('national-parks.translations.uploadImage');
            Route::post('/delete-image', [NationalParkTranslationController::class, 'deleteImage'])->name('national-parks.translations.deleteImage');
            Route::post('/{id?}/store', [NationalParkTranslationController::class, 'store'])->name('national-parks.translations.store');
            Route::get('/{id}/edit', [NationalParkTranslationController::class, 'edit'])->name('national-parks.translations.edit');
            Route::post('/{id}/update', [NationalParkTranslationController::class, 'update'])->name('national-parks.translations.update');
            Route::delete('/{id}', [NationalParkTranslationController::class, 'destroy'])->name('national-parks.translations.destroy');
            Route::get('/datatable', [NationalParkTranslationController::class, 'dataTable'])->name('national-parks.translations.datatable');

            Route::prefix('hidden-wonders')->group(function () {
                Route::get('/{translation}', [HiddenWonderController::class, 'index'])->name('national-parks.translations.hidden-wonders.index');
                Route::get('/{hiddenWonder}/edit', [HiddenWonderController::class, 'edit'])->name('national-parks.translations.hidden-wonders.edit');
                Route::post('/store-hidden-wonder', [HiddenWonderController::class, 'store'])->name('national-parks.translations.hidden-wonders.store');
                Route::put('/{hiddenWonder}', [HiddenWonderController::class, 'update'])->name('national-parks.translations.hidden-wonders.update');
                Route::delete('/{hiddenWonder}', [HiddenWonderController::class, 'destroy'])->name('national-parks.translations.hidden-wonders.destroy');
            });
        });

        // Content Blocks CRUD
        Route::prefix('content-blocks')->group(function () {
            Route::get('{np_translation_id}/', [\App\Http\Controllers\Admin\ContentBlockController::class, 'index'])->name('national-parks.content-blocks.index');
            Route::get('fetch-translation-data', [\App\Http\Controllers\Admin\ContentBlockController::class, 'fetchtranslationData'])->name('national-parks.translation.fetchData');
            Route::get('{np_translation_id}/fetch-data', [\App\Http\Controllers\Admin\ContentBlockController::class, 'fetchData'])->name('national-parks.content-blocks.fetchData');
            Route::post('{np_translation_id}/store', [\App\Http\Controllers\Admin\ContentBlockController::class, 'store'])->name('national-parks.content-blocks.store');
            Route::put('{np_translation_id}/{content_block}/update', [\App\Http\Controllers\Admin\ContentBlockController::class, 'update'])->name('national-parks.content-blocks.update');
            Route::delete('/{np_translation_id}/{content_block}/delete', [\App\Http\Controllers\Admin\ContentBlockController::class, 'destroy'])->name('national-parks.content-blocks.destroy');
        });
    });

    // theme CRUD
    Route::prefix('themes')->group(function () {
        Route::get('/', [ThemeController::class, 'index'])->name('themes.index');
        Route::post('/', [ThemeController::class, 'store'])->name('themes.store'); // ✅ FIXED
        Route::post('/{id}', [ThemeController::class, 'update'])->name('themes.update'); // Using POST + _method=PUT
        Route::delete('/{id}', [ThemeController::class, 'destroy'])->name('themes.delete');
    });
});
