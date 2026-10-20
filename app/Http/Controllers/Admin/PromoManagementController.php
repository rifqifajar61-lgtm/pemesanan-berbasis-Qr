<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoManagementController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->get();

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        Promo::create($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $this->validated($request, $promo);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        $promo->update($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    private function validated(Request $request, ?Promo $promo = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'code'             => [
                'required', 'string', 'max:50',
                Rule::unique('promos', 'code')->ignore($promo?->id),
            ],
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);
    }
}