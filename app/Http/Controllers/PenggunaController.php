<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    // ===== INDEX (pencarian + filter status) =====
    public function index(Request $request)
    {
        $query = Pengguna::query();

        // Searching
        if ($request->search) {
            $query->where('nama_pengguna', 'like', '%'.$request->search.'%')
                  ->orWhere('email_pengguna', 'like', '%'.$request->search.'%');
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Pagination 4 data per halaman
        $penggunas = $query->paginate(4);

        return view('pengguna.index', compact('penggunas'));
    }


// Update method di PenggunaController:

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_pengguna' => 'required|string|max:255',
        'email_pengguna' => 'required|email|unique:pengguna,email_pengguna',
        'password_admin' => 'required|string|min:6',
        'foto_pengguna' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:aktif,tidak',
    ]);

    if ($request->hasFile('foto_pengguna')) {
        $validated['foto_pengguna'] = $request->file('foto_pengguna')->store('foto_pengguna', 'public');
    } else {
        $validated['foto_pengguna'] = null;
    }

    $validated['password_admin'] = Hash::make($validated['password_admin']);

    Pengguna::create($validated);

    return redirect()->route('pengguna.index')
        ->with([
            'success' => 'Data pengguna berhasil ditambahkan',
            'type' => 'success', // tambah tipe notifikasi
            'icon' => 'check_circle' // tambah icon
        ]);
}

public function update(Request $request, Pengguna $pengguna)
{
    $validated = $request->validate([
        'nama_pengguna' => 'required|string|max:255',
        'email_pengguna' => 'required|email|unique:pengguna,email_pengguna,' . $pengguna->id,
        'password_admin' => 'nullable|string|min:6',
        'foto_pengguna' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:aktif,tidak',
    ]);

    $updateData = [
        'nama_pengguna' => $validated['nama_pengguna'],
        'email_pengguna' => $validated['email_pengguna'],
        'status' => $validated['status'],
    ];

    // Update foto jika ada
    if ($request->hasFile('foto_pengguna')) {
        if ($pengguna->foto_pengguna) {
            Storage::disk('public')->delete($pengguna->foto_pengguna);
        }
        $updateData['foto_pengguna'] = $request->file('foto_pengguna')->store('foto_pengguna', 'public');
    }

    // Update password hanya jika diisi
    if ($request->filled('password_admin')) {
        $updateData['password_admin'] = Hash::make($request->password_admin);
    }

    $pengguna->update($updateData);

    return redirect()->route('pengguna.index')
        ->with([
            'success' => 'Data pengguna berhasil diupdate',
            'type' => 'info', // tipe notifikasi untuk update
            'icon' => 'update' // icon untuk update
        ]);
}

// ===== DESTROY =====
public function destroy(Pengguna $pengguna)
{
    if ($pengguna->foto_pengguna) {
        Storage::disk('public')->delete($pengguna->foto_pengguna);
    }

    $pengguna->delete();

    return redirect()->route('pengguna.index')
        ->with([
            'success' => 'Data pengguna berhasil dihapus',
            'type' => 'warning', // tipe notifikasi untuk hapus
            'icon' => 'delete' // icon untuk hapus
        ]);
}

public function toggle($id)
{
    $user = Pengguna::findOrFail($id);

    // toggle aktif/nonaktif
    $user->status = $user->status === 'aktif' ? 'tidak' : 'aktif';
    $user->save();

    return back()
        ->with([
            'success' => 'Status pengguna berhasil diubah',
            'type' => 'success',
            'icon' => 'update'
        ]);
}
}

