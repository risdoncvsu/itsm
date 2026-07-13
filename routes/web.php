<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// 1. The Public Landing Page for Potential Customers
Route::get('/', function () {
    return view('landing'); // Assumes resources/views/landing.blade.php exists
})->name('home');

// 2. Authentication Flow
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Strict Bouncer Control via 'auth' Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // General Dashboard Redirector Route
    Route::get('/dashboard', function () {
        return redirect()->route('admin.itsm.registration');
    })->name('dashboard');

    // ==========================================
    // ADMIN ITSM PORTAL
    // ==========================================
    Route::prefix('admin/itsm')->name('admin.itsm.')->group(function () {
        
        Route::get('/registration', function () {
            return view('dashboard');
        })->name('registration');

        Route::post('/registration', [CompanyController::class, 'store'])->name('registration.store');

        Route::get('/clients', [UserController::class, 'clients'])->name('clients');
        Route::patch('/clients/{company}', [CompanyController::class, 'update'])->name('clients.update');

        Route::get('/service-desk', function () {
            return view('service.service', [
                'portal' => 'admin',
                'active' => 'service-desk',
                'title' => 'Client Service Desk',
                'subtitle' => 'Requests from client companies using Nexora ERP',
            ]);
        })->name('service-desk');
    });

    // ==========================================
    // CLIENT ITSM PORTAL
    // ==========================================
    Route::prefix('client/itsm')->name('client.itsm.')->group(function () {
        
        Route::get('/', function () {
            return redirect()->route('client.itsm.employees');
        })->name('dashboard');

        Route::get('/employees', [UserController::class, 'employees'])->name('employees');
        Route::patch('/employees/{employee}', [UserController::class, 'updateEmployee'])->name('employees.update');

        Route::get('/service-desk', function () {
            return view('service.service', [
                'portal' => 'client',
                'active' => 'service-desk',
                'title' => 'Company Service Desk',
                'subtitle' => 'Internal ITSM requests for your company users',
            ]);
        })->name('service-desk');

        Route::view('/compliance', 'compliance')->name('compliance');
        Route::view('/risk', 'risk')->name('risk');
    });

    // ==========================================
    // GLOBAL USER MANAGEMENT
    // ==========================================
    Route::get('/users', [UserController::class, 'employees'])->name('users.index');

    // Logout Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});