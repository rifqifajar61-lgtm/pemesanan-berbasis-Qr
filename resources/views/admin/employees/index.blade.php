@extends('admin.layouts.app')

@section('title', 'Kelola Pegawai')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Pegawai</h1>
        <p class="text-xs text-gray-500">Daftar akun admin, kasir, dan dapur</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow">
        + Tambah Pegawai
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-stone-900 text-white text-xs uppercase tracking-wider">
                <th class="p-4 w-12">No</th>
                <th class="p-4">Nama</th>
                <th class="p-4">Email</th>
                <th class="p-4">Peran</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
            @forelse($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="p-4 font-bold text-gray-900">
                        {{ $employee->name }}
                        @if($employee->id === auth()->id())
                            <span class="text-[10px] font-semibold text-gray-400">(Kamu)</span>
                        @endif
                    </td>
                    <td class="p-4 text-xs text-gray-500">{{ $employee->email }}</td>
                    <td class="p-4">
                        @php
                            $roleStyle = match ($employee->role) {
                                'admin'   => 'bg-red-100 text-red-800',
                                'cashier' => 'bg-blue-100 text-blue-800',
                                'kitchen' => 'bg-orange-100 text-orange-800',
                                default   => 'bg-gray-100 text-gray-800',
                            };
                            $roleLabel = match ($employee->role) {
                                'admin'   => 'Admin',
                                'cashier' => 'Kasir',
                                'kitchen' => 'Dapur',
                                default   => ucfirst($employee->role),
                            };
                        @endphp
                        <span class="{{ $roleStyle }} text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $roleLabel }}</span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                        @if($employee->id !== auth()->id())
                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400 text-xs font-semibold">
                        Belum ada pegawai. Silakan klik "+ Tambah Pegawai".
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection