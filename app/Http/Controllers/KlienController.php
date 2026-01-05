<?php

namespace App\Http\Controllers;

use App\Models\Klien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KlienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Klien::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_klient', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pagination dengan 4 data per halaman
        $klien = $query->paginate(4)->withQueryString();

        return view('klien.index', compact('klien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('klien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto_klient' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama_klient' => 'required|string|max:250',
            'status' => 'required|in:0,1',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_klient')) {
            $path = $request->file('foto_klient')->store('klien', 'public');
            $validated['foto_klient'] = $path;
        }

        Klien::create($validated);

        return redirect()->route('klien.index')
            ->with('success', 'Data klien berhasil ditambahkan!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Display the specified resource.
     */
    public function show(Klien $klien)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Klien $klien)
    {
        return view('klien.edit', compact('klien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Klien $klien)
    {
        $validated = $request->validate([
            'foto_klient' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama_klient' => 'required|string|max:250',
            'status' => 'required|in:0,1',
        ]);

        // Handle file upload jika ada file baru
        if ($request->hasFile('foto_klient')) {
            // Hapus file lama
            if ($klien->foto_klient) {
                Storage::disk('public')->delete($klien->foto_klient);
            }

            $path = $request->file('foto_klient')->store('klien', 'public');
            $validated['foto_klient'] = $path;
        } else {
            // Jika tidak ada file baru, gunakan file lama
            $validated['foto_klient'] = $klien->foto_klient;
        }

        $klien->update($validated);

        return redirect()->route('klien.index')
            ->with('success', 'Data klien berhasil diperbarui!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Klien $klien)
    {
        // Hapus file foto
        if ($klien->foto_klient) {
            Storage::disk('public')->delete($klien->foto_klient);
        }

        $klien->delete();

        return redirect()->route('klien.index')
            ->with('success', 'Data klien berhasil dihapus!')
            ->with('type', 'success')
            ->with('icon', 'check_circle');
    }

    /**
     * Toggle status klien
     */
    public function toggle(Klien $klien)
    {
        $klien->status = $klien->status == 1 ? 0 : 1;
        $klien->save();

        return response()->json([
            'success' => true,
            'status' => $klien->status
        ]);
    }
}
