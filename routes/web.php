<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ComplianceItemController;
use App\Http\Controllers\RiskAssessmentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {

    // Global Dashboard Redirect
    Route::get('/dashboard', function () {
        return redirect()->route('admin.itsm.registration');
    })->name('dashboard');

    // Admin ITSM Portal
    Route::prefix('admin/itsm')->name('admin.itsm.')->group(function () {
        
        Route::get('/registration', function () {
            return view('dashboard');
        })->name('registration');

        Route::post('/registration', [CompanyController::class, 'store'])->name('registration.store');
        Route::get('/clients', [UserController::class, 'clients'])->name('clients');
        Route::patch('/clients/{company}', [CompanyController::class, 'update'])->name('clients.update');

        Route::get('/service-desk', [TicketController::class, 'index'])->defaults('portal', 'admin')->name('service-desk');
        Route::post('/service-desk', [TicketController::class, 'store'])->name('service-desk.store');
        Route::patch('/service-desk/{ticket}', [TicketController::class, 'update'])->name('service-desk.update');
    });

    // Client ITSM Portal
    Route::prefix('client/itsm')->name('client.itsm.')->group(function () {
        
        Route::get('/', function () {
            return redirect()->route('client.itsm.employees');
        })->name('dashboard');

        Route::get('/employees', [UserController::class, 'employees'])->name('employees');
        Route::post('/employees', [UserController::class, 'storeEmployee'])->name('employees.store');
        Route::patch('/employees/{employee}', [UserController::class, 'updateEmployee'])->name('employees.update');

        Route::get('/service-desk', [TicketController::class, 'index'])->defaults('portal', 'client')->name('service-desk');
        Route::post('/service-desk', [TicketController::class, 'store'])->name('service-desk.store');
        Route::patch('/service-desk/{ticket}', [TicketController::class, 'update'])->name('service-desk.update');

        Route::get('/compliance', [ComplianceItemController::class, 'index'])->name('compliance');
        Route::post('/compliance', [ComplianceItemController::class, 'store'])->name('compliance.store');
        Route::patch('/compliance/{compliance}', [ComplianceItemController::class, 'update'])->name('compliance.update');

        Route::get('/risk', [RiskAssessmentController::class, 'index'])->name('risk');
        Route::post('/risk', [RiskAssessmentController::class, 'store'])->name('risk.store');
        Route::patch('/risk/{risk}', [RiskAssessmentController::class, 'update'])->name('risk.update');
    });

    // Global User Management
    Route::get('/users', [UserController::class, 'employees'])->name('users.index');

    // Logout Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
