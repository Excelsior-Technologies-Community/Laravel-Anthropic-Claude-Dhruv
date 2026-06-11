<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaudeController;
use App\Http\Controllers\McpController;

Route::get('/', [ClaudeController::class, 'index'])->name('claude.index');
Route::post('/claude', [ClaudeController::class, 'chat'])->name('claude.chat');
Route::post('/claude/clear', [ClaudeController::class, 'clear'])->name('claude.clear');

Route::get('/mcp', [McpController::class, 'index'])->name('mcp.index');
Route::post('/mcp', [McpController::class, 'chat'])->name('mcp.chat');
Route::post('/mcp/clear', [McpController::class, 'clear'])->name('mcp.clear');
