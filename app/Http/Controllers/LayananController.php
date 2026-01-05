<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Layanan::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_layanan', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_layanan', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pagination with 4 items per page
        $layanan = $query->latest()->paginate(4)->withQueryString();

        return view('layanan.index', compact('layanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Layanan::getValidationRules());

        // Handle file upload
        if ($request->hasFile('foto_layanan')) {
            $path = $request->file('foto_layanan')->store('layanan', 'public');
            $validated['foto_layanan'] = $path;
        }

        Layanan::create($validated);

        return redirect()->route('layanan.index')
            ->with([
                'success' => 'Layanan berhasil ditambahkan!',
                'type' => 'success',
                'icon' => 'check_circle'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Layanan $layanan)
    {
        return view('layanan.show', compact('layanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate(Layanan::getValidationRules(true));

        // Handle file upload if new file
        if ($request->hasFile('foto_layanan')) {
            // Delete old file
            if ($layanan->foto_layanan && Storage::disk('public')->exists($layanan->foto_layanan)) {
                Storage::disk('public')->delete($layanan->foto_layanan);
            }

            $path = $request->file('foto_layanan')->store('layanan', 'public');
            $validated['foto_layanan'] = $path;
        } else {
            // Keep old file
            $validated['foto_layanan'] = $layanan->foto_layanan;
        }

        $layanan->update($validated);

        return redirect()->route('layanan.index')
            ->with([
                'success' => 'Layanan berhasil diperbarui!',
                'type' => 'success',
                'icon' => 'check_circle'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        // Delete file
        if ($layanan->foto_layanan && Storage::disk('public')->exists($layanan->foto_layanan)) {
            Storage::disk('public')->delete($layanan->foto_layanan);
        }

        $layanan->delete();

        return redirect()->route('layanan.index')
            ->with([
                'success' => 'Layanan berhasil dihapus!',
                'type' => 'success',
                'icon' => 'check_circle'
            ]);
    }

    /**
     * Toggle status
     */
    public function toggle(Layanan $layanan)
    {
        $layanan->status = !$layanan->status;
        $layanan->save();

        return response()->json([
            'success' => true,
            'status' => $layanan->status
        ]);
    }
}
