<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::paginate(15);
        return view('director.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('director.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'operational_area' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ], [
            'name.required' => 'Nama cabang wajib diisi.',
            'name.max' => 'Nama cabang tidak boleh lebih dari 255 karakter.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Branch::create($validated);

        return redirect()->route('director.branches.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('director.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'operational_area' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ], [
            'name.required' => 'Nama cabang wajib diisi.',
            'name.max' => 'Nama cabang tidak boleh lebih dari 255 karakter.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $branch->update($validated);

        return redirect()->route('director.branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('director.branches.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
