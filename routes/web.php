<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

// The route to show the login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// The route that processes the form submission using your code
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('admin.itsm.registration');
    })->name('dashboard');

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

    Route::get('/users', [UserController::class, 'employees'])->name('users.index');
});

Route::get('/', function () {
    return view('welcome');
});
