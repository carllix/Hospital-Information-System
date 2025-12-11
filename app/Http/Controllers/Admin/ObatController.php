<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::where('is_deleted', false);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_obat) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode_obat) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('stok_status')) {
            if ($request->stok_status === 'habis') {
                $query->where('stok', '<=', 0);
            } elseif ($request->stok_status === 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimum')
                    ->where('stok', '>', 0);
            }
        }

        $obats = $query->orderBy('created_at', 'desc')->paginate(10);

        $kategoriList = ['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'lainnya'];

        return view('admin.obat.index', compact('obats', 'kategoriList'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|in:tablet,kapsul,sirup,salep,injeksi,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $obat = Obat::create([
                'kode_obat' => $validated['kode_obat'],
                'nama_obat' => $validated['nama_obat'],
                'kategori' => $validated['kategori'],
                'satuan' => $validated['satuan'],
                'stok' => $validated['stok'],
                'stok_minimum' => $validated['stok_minimum'],
                'harga' => $validated['harga'],
                'deskripsi' => $validated['deskripsi'],
                'is_deleted' => false,
            ]);

            return redirect()->route('admin.obat.show', $obat->obat_id)
                ->with('success', 'Obat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->is_deleted) {
            abort(404);
        }

        return view('admin.obat.show', compact('obat'));
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->is_deleted) {
            abort(404);
        }

        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'kode_obat' => ['required', 'string', 'max:20', Rule::unique('obat', 'kode_obat')->ignore($id, 'obat_id')],
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|in:tablet,kapsul,sirup,salep,injeksi,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $obat->update($validated);

            return redirect()->route('admin.obat.show', $obat->obat_id)
                ->with('success', 'Data obat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);

        try {
            $obat->update(['is_deleted' => true]);

            return redirect()->route('admin.obat.index')
                ->with('success', 'Obat berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}