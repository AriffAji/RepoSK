<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Route::get('/drive', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');

    Route::get('/projects/create', function () {
        return view('projects.create');
    });

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])
        ->name('projects.show');

    Route::post('/folders', [ProjectController::class, 'storeFolder'])
        ->name('folders.store');

    Route::patch('/folders/{folder}/rename', [ProjectController::class, 'renameFolder'])
        ->name('folders.rename');

    Route::delete('/folders/{folder}', [ProjectController::class, 'deleteFolder'])
        ->name('folders.delete');

    Route::delete('/files/{file}', [ProjectController::class, 'deleteFile'])
        ->name('files.delete');
});

Route::post('/files', [ProjectController::class, 'storeFile'])
    ->name('files.store');

Route::get('/files/{file}/download', [ProjectController::class, 'downloadFile'])
    ->name('files.download')
    ->middleware('auth');

Route::patch('/files/{file}/toggle-public', [ProjectController::class, 'togglePublic'])
    ->name('files.togglePublic');

Route::get('/public/files/{file}', [ProjectController::class, 'publicDownload'])
    ->name('files.public');

Route::get('/', [ProjectController::class, 'publicDrive'])
    ->name('drive.index');

Route::get('/drive/folder/{folder}', [ProjectController::class, 'publicFolder'])
    ->name('drive.folder');

Route::get('/drive', [ProjectController::class, 'publicDrive'])
    ->name('drive.index');

Route::get('/drive/folder/{folder}', [ProjectController::class, 'publicFolder'])
    ->name('drive.folder');

Route::get('/drive/search', [ProjectController::class, 'publicSearch'])
    ->name('drive.search');

Route::get('/monitoring', [ProjectController::class, 'monitoring'])
    ->name('monitoring.index')
    ->middleware('auth');

Route::get('/folders/{folder}', [ProjectController::class, 'showFolder'])
    ->name('folders.show')
    ->middleware('auth');

Route::get(
    '/public/file/{token}',
    [ProjectController::class, 'togglePublic']
)->name('files.public.token');



require __DIR__ . '/auth.php';