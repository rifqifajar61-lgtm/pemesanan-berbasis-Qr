<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableManagementController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('table_number')->get();

        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:20|unique:tables,table_number',
        ]);

        $data['token'] = Str::random(20);

        Table::create($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:20|unique:tables,table_number,' . $table->id,
        ]);

        $table->update($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}