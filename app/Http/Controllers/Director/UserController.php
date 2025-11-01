<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // List all managers and employees
        $users = User::whereIn('role', ['manager', 'employee'])->with('branch')->paginate(15);
        return view('director.users.index', compact('users'));
    }

    public function create()
    {
        // Show form to create new user (manager or employee)
        $branches = \App\Models\Branch::all();
        return view('director.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        // Validate and store new user
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['manager', 'employee'])],
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'branch_id.exists' => 'Cabang yang dipilih tidak valid.',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->password = Hash::make($validated['password']);
        $user->branch_id = $validated['branch_id'] ?? null;
        $user->save();

        return redirect()->route('director.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Show form to edit user
        if (!in_array($user->role, ['manager', 'employee'])) {
            abort(403);
        }
        $branches = \App\Models\Branch::all();
        return view('director.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        // Validate and update user
        if (!in_array($user->role, ['manager', 'employee'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['manager', 'employee'])],
            'password' => 'nullable|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->branch_id = $validated['branch_id'] ?? null;
        $user->save();

        return redirect()->route('director.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Soft delete or deactivate user
        if (!in_array($user->role, ['manager', 'employee'])) {
            abort(403);
        }
        $user->delete();

        return redirect()->route('director.users.index')->with('success', 'User deleted successfully.');
    }
}
