<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // Middleware removed from here as it is applied in routes/web.php
    }

    /**
     * Display a listing of employees in the manager's branch.
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $employees = User::where('branch_id', $branchId)
            ->where('role', 'employee')
            ->withCount(['transactions' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->paginate(15);

        return view('manager.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('manager.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        $employee = new User();
        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->password = Hash::make($validated['password']);
        $employee->role = 'employee';
        $employee->branch_id = $branchId;
        $employee->is_active = $validated['is_active'] ?? true; // Default to active
        $employee->save();

        return redirect()->route('manager.employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(User $employee)
    {
        $this->authorize('manage', $employee);

        return view('manager.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, User $employee)
    {
        $this->authorize('manage', $employee);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->is_active = $validated['is_active'];

        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        $statusMessage = $validated['is_active']
            ? 'Pegawai berhasil diperbarui dan diaktifkan.'
            : 'Pegawai berhasil diperbarui dan dinonaktifkan.';

        return redirect()->route('manager.employees.index')->with('success', $statusMessage);
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(User $employee)
    {
        $this->authorize('manage', $employee);

        $employee->delete();

        return redirect()->route('manager.employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
