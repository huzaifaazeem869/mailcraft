<?php

use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Main landing & template gallery
Route::get('/', [EmailTemplateController::class, 'index'])->name('templates.index');

// Editor routes (Guest or Auth)
Route::get('/editor', [EmailTemplateController::class, 'editor'])->name('editor.new');
Route::get('/editor/{template:slug}', [EmailTemplateController::class, 'editor'])->name('editor.edit');

// API routes for templates
Route::prefix('api/templates')->name('api.templates.')->group(function () {
    Route::post('/render-html', [EmailTemplateController::class, 'renderHtml'])->name('renderHtml');
    Route::post('/export-html', [EmailTemplateController::class, 'exportHtml'])->name('exportHtml');
    Route::post('/export-zip', [EmailTemplateController::class, 'exportZip'])->name('exportZip');
    Route::post('/save', [EmailTemplateController::class, 'store'])->name('save');
    Route::post('/{template}/update', [EmailTemplateController::class, 'update'])->name('update');
    Route::post('/{template}/duplicate', [EmailTemplateController::class, 'duplicate'])->name('duplicate');
    Route::delete('/{template}', [EmailTemplateController::class, 'destroy'])->name('destroy');
});

Route::get('/dashboard', function () {
    return redirect()->route('templates.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
