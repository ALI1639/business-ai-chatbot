<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\BusinessContextController;


/*
|--------------------------------------------------------------------------
| AI Chat Page
|--------------------------------------------------------------------------
*/

Route::get('/', [
    AiChatController::class,
    'index'
])->name('ai.chat');


/*
|--------------------------------------------------------------------------
| Business Context
|--------------------------------------------------------------------------
*/

Route::get('/business-context', [
    BusinessContextController::class,
    'index'
]);


/*
|--------------------------------------------------------------------------
| AI Chat
|--------------------------------------------------------------------------
*/

Route::post('/ai/chat', [
    AiChatController::class,
    'ask'
])->name('ai.ask');


/*
|--------------------------------------------------------------------------
| Conversations
|--------------------------------------------------------------------------
*/

Route::get('/ai/conversations', [
    AiChatController::class,
    'conversations'
])->name('ai.conversations');


/*
|--------------------------------------------------------------------------
| Conversation Messages
|--------------------------------------------------------------------------
*/

Route::get('/ai/chat/{conversation}/messages', [
    AiChatController::class,
    'messages'
])->name('ai.messages');


/*
|--------------------------------------------------------------------------
| Delete Conversation
|--------------------------------------------------------------------------
*/

Route::delete('/ai/chat/{conversation}', [
    AiChatController::class,
    'destroy'
])->name('ai.destroy');
