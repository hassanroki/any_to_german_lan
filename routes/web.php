<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TranslationController::class, 'index']);
Route::post('/translate', [TranslationController::class, 'translate'])->name('translate');
