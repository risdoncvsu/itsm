<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeOnboardingController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/* EMPLOYEES */
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

/* DEPARTMENTS */
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');


Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');

Route::get('/departments/{department}', [DepartmentController::class, 'show'])
    ->name('departments.show');
Route::prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/step1', [EmployeeOnboardingController::class, 'step1'])->name('step1');
    Route::post('/step1', [EmployeeOnboardingController::class, 'storeStep1'])->name('storeStep1');

    Route::get('/step2', [EmployeeOnboardingController::class, 'step2'])->name('step2');
    Route::post('/step2', [EmployeeOnboardingController::class, 'storeStep2'])->name('storeStep2');

    Route::get('/step3', [EmployeeOnboardingController::class, 'step3'])->name('step3');
    Route::post('/step3', [EmployeeOnboardingController::class, 'storeStep3'])->name('storeStep3');

    Route::get('/step4', [EmployeeOnboardingController::class, 'step4'])->name('step4');
    Route::post('/step4', [EmployeeOnboardingController::class, 'storeStep4'])->name('storeStep4');

    Route::get('/success', [EmployeeOnboardingController::class, 'success'])->name('success');
    
    
});


Route::get('/signin', function () {
    return view('auth.signin');
});

Route::get('/signin', function () {
    return view('auth.signin');
})->name('signin');

Route::post('/signin', [AuthController::class, 'login'])
    ->name('signin.post');



    Route::middleware('employee.auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments.show');

    Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments.index');

   
          
       

        
    // all onboarding routes
});



Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');

Route::get('/clockinout', function () {
    return view('clockinout.index');
})->name('clockinout');





Route::get('/reports-analytics/employee-attendance', function () {
    return view('reports-analytics.employee-attendance');
})->name('reports-analytics.employee-attendance');

use App\Models\Employee;

Route::get('/reports-analytics/attendance-overview', function () {

    $employees = Employee::all();

    return view(
        'reports-analytics.attendance-overview',
        compact('employees')
    );

})->name('reports-analytics.attendance-overview');



Route::post('/clock-in', [AttendanceController::class, 'clockIn'])
    ->name('clockinout.index');

    Route::get('/attendance/today-count', function () {
    return response()->json([
        'count' => \App\Models\Attendance::whereDate(
            'attendance_date',
            today()
        )->count()
    ]);
});

Route::get(
    '/reports-analytics/employee-attendance/{employee}',
    [AttendanceOverviewController::class, 'employeeAttendance']
)->name('reports-analytics.employee-attendance');