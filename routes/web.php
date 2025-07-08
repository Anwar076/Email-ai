<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gmail OAuth routes
    Route::get('/google', [GmailController::class, 'redirectToGoogle'])->name('google.auth');
    Route::get('/google/callback', [GmailController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::get('/gmail/disconnect', [GmailController::class, 'disconnect'])->name('gmail.disconnect');
    Route::get('/gmail/status', [GmailController::class, 'checkConnection'])->name('gmail.status');
    
    // Email management routes
    Route::get('/inbox', [GmailController::class, 'inbox'])->name('inbox');
    Route::get('/email/{messageId}', [EmailController::class, 'getEmailDetails'])->name('email.details');
    Route::get('/email/{messageId}/summary', [EmailController::class, 'generateSummary'])->name('email.summary');
    
    // AI reply routes
    Route::get('/generate-reply/{messageId}', [EmailController::class, 'generateReply'])->name('generate.reply');
    Route::post('/send-reply/{messageId}', [EmailController::class, 'sendReply'])->name('send.reply');
});

require __DIR__.'/auth.php';