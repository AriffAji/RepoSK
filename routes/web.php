<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| PUBLIC AREA (NO AUTH)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProjectController::class, 'publicDrive'])
    ->name('drive.index');

Route::prefix('drive')->group(function () {

    Route::get('/', [ProjectController::class, 'publicDrive'])
        ->name('drive.home');

    Route::get('/folder/{folder}', [ProjectController::class, 'publicFolder'])
        ->name('drive.folder');

    Route::get('/search', [ProjectController::class, 'publicSearch'])
        ->name('drive.search');
});

Route::get('/public/files/{file}', [ProjectController::class, 'publicDownload'])
    ->name('files.public');


/*
|--------------------------------------------------------------------------
| AUTH AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');

    Route::get('/projects/create', function () {
        return view('projects.create');
    })->name('projects.create');

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])
        ->name('projects.show');

    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.delete');


    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    */

    Route::post('/folders', [ProjectController::class, 'storeFolder'])
        ->name('folders.store');

    Route::patch('/folders/{folder}/rename', [ProjectController::class, 'renameFolder'])
        ->name('folders.rename');

    Route::delete('/folders/{folder}', [ProjectController::class, 'deleteFolder'])
        ->name('folders.delete');

    Route::get('/folders/{folder}', [ProjectController::class, 'showFolder'])
        ->name('folders.show');


    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    */

    Route::post('/files', [ProjectController::class, 'storeFile'])
        ->name('files.store');

    Route::delete('/files/{file}', [ProjectController::class, 'deleteFile'])
        ->name('files.delete');

    Route::get('/files/{file}/download', [ProjectController::class, 'downloadFile'])
        ->name('files.download');

    Route::patch('/files/{file}/toggle-public', [ProjectController::class, 'togglePublic'])
        ->name('files.togglePublic');


    /*
    |--------------------------------------------------------------------------
    | Monitoring
    |--------------------------------------------------------------------------
    */

    Route::get('/monitoring', [ProjectController::class, 'monitoring'])
        ->name('monitoring.index');
});


require __DIR__ . '/auth.php';
