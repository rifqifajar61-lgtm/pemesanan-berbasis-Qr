<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeManagementController extends Controller
{
    public function index()
    {
        $employees = User::whereIn('role', ['admin', 'cashier', 'kitchen'])
            ->orderBy('name')
            ->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => ['required', Rule::in(['admin', 'cashier', 'kitchen'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($employee->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => ['required', Rule::in(['admin', 'cashier', 'kitchen'])],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}