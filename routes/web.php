<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaudeController;

Route::get('/', [ClaudeController::class, 'index'])->name('claude.index');
Route::post('/claude', [ClaudeController::class, 'chat'])->name('claude.chat');
