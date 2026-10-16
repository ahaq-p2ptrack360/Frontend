<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIQueryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// EncryptCookies + StartSession are added here because the default 'api' group has no
// session support, which meant Auth::user() was always null for these routes.
Route::middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Session\Middleware\StartSession::class,
    'cookie.auth',
])->group(function () {
    Route::post('/agent-chat', [App\Http\Controllers\AgentChatController::class, 'ask']);
    Route::get('/ai/conversations', [App\Http\Controllers\AgentChatController::class, 'conversations']);
    Route::get('/ai/conversations/{conversation}/messages', [App\Http\Controllers\AgentChatController::class, 'messages']);
    Route::delete('/ai/conversations/{conversation}', [App\Http\Controllers\AgentChatController::class, 'destroy']);
    Route::post('/ai/query/plan', [AIQueryController::class, 'plan']);
});

