<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('notes', [NoteController::class, 'store'])->middleware('throttle:10,1')->name('notes.store');
    Route::get('notes/search', [NoteController::class, 'search'])->name('notes.search');
    Route::get('notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('notes/{note}/attachment', [NoteController::class, 'attach'])->name('notes.attach');
    Route::get('notes/{note}/attachment', [NoteController::class, 'download'])->name('notes.download');
});

require __DIR__.'/settings.php';
