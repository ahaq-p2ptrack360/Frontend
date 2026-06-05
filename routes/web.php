<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Auth::routes();
// In web.php or api.php
Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});

Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::middleware(['cookie.auth'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/ticket', 'ticket');
    Route::view('/status', 'status');
    Route::view('/priority', 'priority');
    Route::view('/impact', 'impact');
    Route::view('/buisness-unit', 'buisness-unit');
    Route::view('/assign-group', 'assign-group');
    Route::view('/create-group', 'create-group');
    Route::view('/vendor', 'vendor');
    Route::view('/subscription', 'subscription');
    Route::view('/domain', 'domain');
    Route::view('/company', 'company');
    Route::view('/company-profile', 'company-profile');
    Route::view('/user', 'user');
    Route::view('/designation', 'designation');
    Route::view('/customer', 'customer');
    Route::view('/types', 'types');
    Route::view('/permissions', 'permission');
    Route::view('/roles', 'role');
    Route::view('/reports', 'report');
    Route::view('/quotations', 'quotation');
});


// Debug Routes
Route::get('/debug-role', function () {
    $authUser = Auth::user();
    $freshFromModel = User::find($authUser->id);
    $raw = DB::selectOne('SELECT role FROM users WHERE id = ?', [$authUser->id]);

    return response()->json([
        'auth_user_attributes' => $authUser->toArray(),
        'fresh_model_attributes' => $freshFromModel->toArray(),
        'auth_user_role_property' => $authUser->role,
        'fresh_model_role_property' => $freshFromModel->role,
        'raw_query' => $raw,
    ]);
});

Route::get('/debug-auth', function () {
    return response()->json([
        'is_authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => Session::getId(),
        'session_all' => Session::all(),
        'cookies_received' => request()->cookies->all(),
        'laravel_session_cookie' => Cookie::get('laravel_session'),
        'app_env' => config('app.env'),
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'app_url' => config('app.url'),
    ]);
});

Route::get('/debug-session', function () {

    echo "<h3>1. Session Data:</h3>";
    dump(session()->all());

    echo "<h3>2. Auth Check:</h3>";
    echo "Is authenticated: " . (auth()->check() ? 'YES' : 'NO');

    echo "<h3>3. Auth User:</h3>";
    dump(auth()->user());

    echo "<h3>4. Auth ID:</h3>";
    echo "User ID: " . auth()->id();

    echo "<h3>5. Cookies:</h3>";
    dump(request()->cookies->all());

    echo "<h3>6. Session Config:</h3>";
    echo "Driver: " . config('session.driver');
    echo "<br>Session Name: " . config('session.cookie');
});
// routes/web.php
Route::get('/api/check-session', function() {
    return response()->json([
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
    ]);
});

// Catch-all route (always last)
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])
    ->where('any', '.*')
    ->name('index');

    