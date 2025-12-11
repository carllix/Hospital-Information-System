<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::where('is_deleted', false);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_layanan) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode_layanan) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $layanans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_layanan' => 'required|string|max:20|unique:layanan,kode_layanan',
            'nama_layanan' => 'required|string|max:255',
            'kategori' => 'required|in:konsultasi,tindakan',
            'harga' => 'required|numeric|min:0',
        ]);

        try {
            $layanan = Layanan::create([
                'kode_layanan' => $validated['kode_layanan'],
                'nama_layanan' => $validated['nama_layanan'],
                'kategori' => $validated['kategori'],
                'harga' => $validated['harga'],
                'is_deleted' => false,
            ]);

            return redirect()->route('admin.layanan.show', $layanan->layanan_id)
                ->with('success', 'Layanan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->is_deleted) {
            abort(404);
        }

        return view('admin.layanan.show', compact('layanan'));
    }

    public function edit(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->is_deleted) {
            abort(404);
        }

        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, string $id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'kode_layanan' => ['required', 'string', 'max:20', Rule::unique('layanan', 'kode_layanan')->ignore($id, 'layanan_id')],
            'nama_layanan' => 'required|string|max:255',
            'kategori' => 'required|in:konsultasi,tindakan',
            'harga' => 'required|numeric|min:0',
        ]);

        try {
            $layanan->update($validated);

            return redirect()->route('admin.layanan.show', $layanan->layanan_id)
                ->with('success', 'Data layanan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        try {
            $layanan->update(['is_deleted' => true]);

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}