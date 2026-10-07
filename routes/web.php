<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\BusinessContextController;

Route::get('/', [
    AiChatController::class,
    'index'
])->name('ai.chat');

Route::get('/business-context', [
    BusinessContextController::class,
    'index'
]);

Route::post('/ai/chat', [
    AiChatController::class,
    'ask'
])->name('ai.ask');
