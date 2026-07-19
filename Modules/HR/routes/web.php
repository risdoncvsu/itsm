<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\HrDashboardController;

Route::get('/', function () {
    return redirect()->route('hr.dashboard');
});

Route::get('/dashboard', [HrDashboardController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [HrDashboardController::class, 'logout'])->name('logout');
