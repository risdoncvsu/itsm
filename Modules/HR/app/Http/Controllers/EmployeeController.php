<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPerPage;
use App\Http\Controllers\Concerns\RespondsWithAjaxList;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ResolvesPerPage;
    use RespondsWithAjaxList;

    public function index(Request $request)
    {
        $employees = Employee::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $employees->where(function ($query) use ($search) {
                $query->whereRaw(
                    "CONCAT(first_name, ' ', last_name) LIKE ?",
                    ["%{$search}%"]
                )
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        switch (request('sort')) {
            case 'name_asc':       $employees->orderBy('first_name', 'asc'); break;
            case 'name_desc':      $employees->orderBy('first_name', 'desc'); break;
            case 'id_asc':         $employees->orderBy('id', 'asc'); break;
            case 'id_desc':        $employees->orderBy('id', 'desc'); break;
            case 'department_asc': $employees->orderBy('department', 'asc'); break;
            case 'department_desc':$employees->orderBy('department', 'desc'); break;
            case 'position_asc':   $employees->orderBy('position', 'asc'); break;
            case 'position_desc':  $employees->orderBy('position', 'desc'); break;
            case 'newest':         $employees->orderBy('created_at', 'desc'); break;
            case 'oldest':         $employees->orderBy('created_at', 'asc'); break;
            default:               $employees->orderBy('id', 'asc'); break;
        }

        $employees = $employees->paginate($this->perPage($request))->withQueryString();

        if ($this->wantsAjaxList($request)) {
            return $this->ajaxListResponse('employees.partials.list-results', compact('employees'));
        }

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required',
            'last_name'       => 'required',
            'email'           => 'required|email|unique:employees,email',
            'phone'           => 'nullable',
    'position'        => 'nullable',
    'department'      => 'required',
    'gender'          => 'nullable',
    'marital_status'  => 'nullable',
    'address'         => 'nullable',
    'profile_picture' => 'nullable|file',
]);


$imageName = null;

if ($request->hasFile('profile_picture')) {
    $imageName = time() . '.' . $request->file('profile_picture')->extension();
    $request->file('profile_picture')->move(public_path('profile_pictures'), $imageName);
}

$lastEmployee = Employee::latest('id')->first();

$nextNumber = $lastEmployee ? intval(substr($lastEmployee->employee_id, 4)) + 1 : 1;

$employeeId = date('Y') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
Employee::create([
    'employee_id' => $employeeId,
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'middle_name' => $request->middle_name,
    'email' => $request->email,
    'phone' => $request->phone,
    'position' => $request->position,
    'department' => $request->department,
    'gender' => $request->gender,
    'marital_status' => $request->marital_status,
    'address' => $request->address,
    'profile_picture' => $imageName,
]);
        return redirect()->route('dashboard')
            ->with('success', 'Employee added successfully!');
    }

    public function show($id)
{
    $employee = Employee::findOrFail($id);

    return view('employees.employeeform', compact('employee'));
}
public function update(Request $request, Employee $employee)
{
    $request->validate([
        'email'           => 'required|email|unique:employees,email,' . $employee->id,
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB
    ]);

    $data = [
        'first_name'     => $request->first_name,
        'middle_name'    => $request->middle_name,
        'last_name'      => $request->last_name,
        'suffix'         => $request->suffix,
        'department'     => $request->department,
        'position'       => $request->position,
        'gender'         => $request->gender,
        'marital_status' => $request->marital_status,
        'nationality'    => $request->nationality,
        'address'        => $request->address,
        'email'          => $request->email,
        'phone'          => $request->phone,
    ];

    $employee->update($data);

    return back()->with('success', 'Employee updated successfully.');
}

public function destroy($id)
{
    $employee = Employee::findOrFail($id);

    // Delete profile picture if meron
    if ($employee->profile_picture &&
        file_exists(public_path('profile_pictures/' . $employee->profile_picture))) {

        unlink(public_path('profile_pictures/' . $employee->profile_picture));
    }

    $employee->delete();

    return redirect('/employees')
    ->with('success','Employee deleted successfully!');
}
}