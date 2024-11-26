<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EknexaController;

Route::get('/', [EknexaController::class, 'index']);
Route::post('/create', [EknexaController::class, 'store']);
Route::get('/create', [EknexaController::class, 'create'])->name('create');
