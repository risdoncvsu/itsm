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
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HrPortalController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/hr/dashboard', [HrPortalController::class, 'dashboard'])->name('hr.dashboard');

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
        Route::delete('/clients/{company}', [CompanyController::class, 'destroy'])->name('clients.destroy');

        Route::get('/service-desk', [TicketController::class, 'index'])->defaults('portal', 'admin')->name('service-desk');
        Route::patch('/service-desk/{ticket}', [TicketController::class, 'update'])->name('service-desk.update');
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
        Route::get('/pending-approvals', [UserController::class, 'pendingApprovals'])->name('pending-approvals');
        Route::post('/pending-approvals/{employee}/approve', [UserController::class, 'approveHrManager'])->name('pending-approvals.approve');

        Route::get('/service-desk', [TicketController::class, 'index'])->name('service-desk');
        Route::post('/service-desk', [TicketController::class, 'store'])->name('service-desk.store');
        Route::patch('/service-desk/{ticket}', [TicketController::class, 'update'])->name('service-desk.update');
        Route::get('/service-desk/support', [TicketController::class, 'supportIndex'])->name('service-desk.support');
        Route::post('/service-desk/support', [TicketController::class, 'store'])->name('service-desk.support.store');
        
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
    return redirect()->route('login');
});
