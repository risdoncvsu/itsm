<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// The route to show the login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// The route that processes the form submission using your code
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); // <-- Added name here
    
});

Route::get('/', function () {
    return view('welcome');
});
