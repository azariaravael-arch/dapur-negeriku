<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Proyek::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_proyek', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_proyek', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pagination dengan 4 data per halaman
        $proyek = $query->paginate(4)->withQueryString();

        return view('proyek.index', compact('proyek'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proyek.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto_proyek' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama_proyek' => 'required|string|max:250',
            'deskripsi_proyek' => 'required|string|max:250',
            'foto_tambahan_proyek' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:0,1',
        ]);

        // Handle file upload foto utama
        if ($request->hasFile('foto_proyek')) {
            $path = $request->file('foto_proyek')->store('proyek', 'public');
            $validated['foto_proyek'] = $path;
        }

        // Handle file upload foto tambahan
        if ($request->hasFile('foto_tambahan_proyek')) {
            $path = $request->file('foto_tambahan_proyek')->store('proyek/tambahan', 'public');
            $validated['foto_tambahan_proyek'] = $path;
        } else {
            $validated['foto_tambahan_proyek'] = null;
        }

        Proyek::create($validated);

        return redirect()->route('proyek.index')
            ->with('success', 'Data proyek berhasil ditambahkan!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyek $proyek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyek $proyek)
    {
        $validated = $request->validate([
            'foto_proyek' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama_proyek' => 'required|string|max:250',
            'deskripsi_proyek' => 'required|string|max:250',
            'foto_tambahan_proyek' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:0,1',
        ]);

        // Handle file upload foto utama
        if ($request->hasFile('foto_proyek')) {
            // Hapus file lama
            if ($proyek->foto_proyek) {
                Storage::disk('public')->delete($proyek->foto_proyek);
            }

            $path = $request->file('foto_proyek')->store('proyek', 'public');
            $validated['foto_proyek'] = $path;
        } else {
            $validated['foto_proyek'] = $proyek->foto_proyek;
        }

        // Handle file upload foto tambahan
        if ($request->hasFile('foto_tambahan_proyek')) {
            // Hapus file lama
            if ($proyek->foto_tambahan_proyek) {
                Storage::disk('public')->delete($proyek->foto_tambahan_proyek);
            }

            $path = $request->file('foto_tambahan_proyek')->store('proyek/tambahan', 'public');
            $validated['foto_tambahan_proyek'] = $path;
        } else {
            $validated['foto_tambahan_proyek'] = $proyek->foto_tambahan_proyek;
        }

        $proyek->update($validated);

        return redirect()->route('proyek.index')
            ->with('success', 'Data proyek berhasil diperbarui!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyek $proyek)
    {
        // Hapus file foto utama
        if ($proyek->foto_proyek) {
            Storage::disk('public')->delete($proyek->foto_proyek);
        }

        // Hapus file foto tambahan
        if ($proyek->foto_tambahan_proyek) {
            Storage::disk('public')->delete($proyek->foto_tambahan_proyek);
        }

        $proyek->delete();

        return redirect()->route('proyek.index')
            ->with('success', 'Data proyek berhasil dihapus!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Toggle status proyek
     */
    public function toggle(Proyek $proyek)
    {
        $proyek->status = $proyek->status == 1 ? 0 : 1;
        $proyek->save();

        return response()->json([
            'success' => true,
            'status' => $proyek->status
        ]);
    }
}
