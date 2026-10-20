@extends('admin.layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Pegawai</h1>

    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 mb-1">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-gray-600 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="role" class="block text-xs font-bold text-gray-600 mb-1">Peran</label>
            <select id="role" name="role" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="admin" @selected(old('role', $employee->role) === 'admin')>Admin</option>
                <option value="cashier" @selected(old('role', $employee->role) === 'cashier')>Kasir</option>
                <option value="kitchen" @selected(old('role', $employee->role) === 'kitchen')>Dapur</option>
            </select>
            @error('role') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-gray-600 mb-1">Password Baru <span class="font-normal text-gray-400">(kosongkan jika tidak diubah)</span></label>
            <input type="password" id="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-600 mb-1">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Perbarui</button>
            <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection