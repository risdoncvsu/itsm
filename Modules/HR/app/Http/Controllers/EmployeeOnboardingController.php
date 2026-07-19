<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeOnboardingController extends Controller
{
    public function step1()
    {
        return view('employees.onboarding.step1');
    }

    public function storeStep1(Request $request)
{

    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:employees,email',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except('profile_picture');

    if ($request->hasFile('profile_picture')) {
        $imageName = time() . '.' . $request->file('profile_picture')->extension();
        $request->file('profile_picture')->move(public_path('profile_pictures'), $imageName);

        $data['profile_picture'] = $imageName;
    }

    session(['step1' => $data]);

    return redirect()->route('onboarding.step2');
}

    public function step2()
    {
        if (! session('step1')) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Please complete step 1 first.');
        }

        return view('employees.onboarding.step2');
    }

    public function storeStep2(Request $request)
    {
        if (! session('step1')) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Please complete step 1 first.');
        }

        $validated = $request->validate([
            'department' => 'required|string',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $start = \Carbon\Carbon::parse($validated['start_time']);
        $end = \Carbon\Carbon::parse($validated['end_time']);

        if ($end->lte($start)) {
            return back()
                ->withErrors(['end_time' => 'End Time must be after Start Time.'])
                ->withInput();
        }

        $validated['start_time'] = $start->format('H:i');
        $validated['end_time'] = $end->format('H:i');
        $validated['work_schedule'] = $validated['start_time'].'-'.$validated['end_time'];

        session(['step2' => $validated]);

        return redirect()->route('onboarding.step3');
    }

    public function step3()
    {
        if (! session('step1')) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Please complete step 1 first.');
        }

        if (! session('step2')) {
            return redirect()->route('onboarding.step2')
                ->with('error', 'Please complete step 2 first.');
        }

        return view('employees.onboarding.step3');
    }

    public function storeStep3(Request $request)
    {
        if (! session('step1') || ! session('step2')) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Your onboarding session expired. Please start again.');
        }

        $data = $request->except([
            'birth_certificate',
            'curriculum_vitae',
            'valid_id',
        ]);

        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate'] =
                $request->file('birth_certificate')->store('documents', 'public');
        }

        if ($request->hasFile('curriculum_vitae')) {
            $data['curriculum_vitae'] =
                $request->file('curriculum_vitae')->store('documents', 'public');
        }

        if ($request->hasFile('valid_id')) {
            $data['valid_id'] =
                $request->file('valid_id')->store('documents', 'public');
        }

        session(['step3' => $data]);

        return redirect()->route('onboarding.step4');
    }

    public function step4()
    {
        if (! session('step1')) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Please complete step 1 first.');
        }

        if (! session('step2')) {
            return redirect()->route('onboarding.step2')
                ->with('error', 'Please complete step 2 first.');
        }

        if (! session('step3')) {
            return redirect()->route('onboarding.step3')
                ->with('error', 'Please complete step 3 first.');
        }

        return view('employees.onboarding.step4');
    }

    public function storeStep4(Request $request)
    {
        $step1 = session('step1');
        $step2 = session('step2');
        $step3 = session('step3');

        if (! $step1 || ! $step2 || ! $step3) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Your onboarding session expired. Please start again.');
        }

        $request->validate([
            'policy_1' => 'accepted',
            'policy_2' => 'accepted',
            'policy_3' => 'accepted',
            'policy_4' => 'accepted',
            'policy_5' => 'accepted',
            'policy_6' => 'accepted',
        ]);

        $firstName = preg_replace('/\s+/', '', $step1['first_name']);
        $lastName = preg_replace('/\s+/', '', $step1['last_name']);

        $companyEmail = strtolower($firstName . $lastName . '@nexora.com');
        $password = 'NEX-' . Str::upper(Str::random(6));

    $employee = Employee::create([
        'first_name' => $step1['first_name'],
        'middle_name' => $step1['middle_name'] ?? null,
        'last_name' => $step1['last_name'],
        'suffix' => $step1['suffix'] ?? null,
        'gender' => $step1['gender'] ?? null,
        'marital_status' => $step1['marital_status'] ?? null,
        'nationality' => $step1['nationality'] ?? null,
        'address' => $step1['address'] ?? null,
        'phone' => $step1['phone'] ?? null,
        'email' => $step1['email'],
        'profile_picture' => $step1['profile_picture'] ?? null,
        'department' => $step2['department'],
        'position' => $step2['position'],
        'hire_date' => $step2['hire_date'],
        'work_schedule' => $step2['work_schedule'],
        'birth_certificate' => $step3['birth_certificate'] ?? null,
        'curriculum_vitae' => $step3['curriculum_vitae'] ?? null,
        'valid_id' => $step3['valid_id'] ?? null,
        'medical_certificate' => $step3['medical_certificate'] ?? null,
        'company_email' => $companyEmail,
        'temporary_password' => $password,
    ]);

    // Ngayon meron na tayong auto-increment id, gamitin natin siya
    $employee->employee_id = date('Y') . str_pad($employee->id, 4, '0', STR_PAD_LEFT);
    $employee->save();

    session()->forget(['step1', 'step2', 'step3']);
    session(['employee' => $employee]);

    return redirect()->route('onboarding.success');
}

    public function success()
    {
        $employee = session('employee');

        if (! $employee) {
            return redirect()->route('onboarding.step1');
        }

        return view('employees.onboarding.success', compact('employee'));
    }
}
