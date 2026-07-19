<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\RiskMitigationController;
use App\Http\Controllers\IncidentController; 
use App\Http\Controllers\RiskAnalyticsController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\AuditController; 
use App\Http\Controllers\PermitController;
use App\Http\Controllers\RiskAssController;
use App\Http\Controllers\DocumentController; // Imported DocumentController
use App\Http\Controllers\NewUserSetupController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/newuser', [NewUserSetupController::class, 'show'])->name('newuser.show');
    Route::post('/newuser/password', [NewUserSetupController::class, 'storePassword'])->name('newuser.password');
    Route::post('/newuser/logo', [NewUserSetupController::class, 'storeLogo'])->name('newuser.logo');
    Route::post('/newuser/hr-manager', [NewUserSetupController::class, 'storeHrManager'])->name('newuser.hr-manager');

    Route::get('/dashboard', function () {
        return redirect()->route('admin.itsm.registration');
    })->name('dashboard');

    // ==========================================
    // ADMIN ITSM ROUTES
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
    // CLIENT ITSM ROUTES
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
        
        // ==========================================
        // COMPLIANCE MODULE ROUTES
        // ==========================================
        Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance');
        Route::post('/compliance/store', [ComplianceController::class, 'store'])->name('compliance.store');
        
        Route::get('/audit', [AuditController::class, 'index'])->name('audit');
        Route::post('/audit', [AuditController::class, 'index'])->name('audit.store');
        
        Route::get('/permit', [PermitController::class, 'index'])->name('permit');
        Route::post('/permit', [PermitController::class, 'index'])->name('permit.store');
        
        Route::get('/risk-assessment', [RiskAssController::class, 'index'])->name('risk.assessment');
        Route::post('/risk-assessment/store', [RiskAssController::class, 'store'])->name('risk.assessment.store');
        
        // BOUND TO CONTROLLER: Connected to DocumentController for functional filtering, search, and dynamic layout
        Route::get('/documents', [DocumentController::class, 'index'])->name('document');
        Route::post('/documents/store', [DocumentController::class, 'store'])->name('document.store');

        // Risk Management (Risk Register)
        Route::get('/risk', [RiskController::class, 'index'])->name('risk');
        Route::post('/risk/store', [RiskController::class, 'store'])->name('risk.store');
        Route::post('/risk/update', [RiskController::class, 'update'])->name('risk.update');
        Route::get('/risk/{id}/manage', [RiskController::class, 'manage'])->name('risk.manage');
        
        // Risk Management (Mitigation Plans)
        Route::get('/risk/mitigation', [RiskMitigationController::class, 'index'])->name('risk.mitigation');
        Route::post('/risk/mitigation/store', [RiskMitigationController::class, 'store'])->name('risk.mitigation.store');
        
        // Risk Management (Incident Reports)
        Route::get('/risk/incident', [IncidentController::class, 'index'])->name('risk.incident');
        Route::post('/risk/incident/store', [IncidentController::class, 'store'])->name('risk.incident.store');
        Route::post('/risk/incident/{id}/status', [IncidentController::class, 'updateStatus'])->name('risk.incident.status');
        
        // Risk Management Analytics Console Engine
        Route::get('/risk/analytics', [RiskAnalyticsController::class, 'index'])->name('risk.analytics');
        Route::get('/risk/analytics/export', [RiskAnalyticsController::class, 'export'])->name('risk.analytics.export');
    });

    Route::get('/users', [UserController::class, 'employees'])->name('users.index');
});

Route::get('/', function () {
    return view('welcome');
});
