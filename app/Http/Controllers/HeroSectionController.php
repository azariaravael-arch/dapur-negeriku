<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{




    public function destroy($id)
    {
        $hero = HeroSection::findOrFail($id);

        Storage::disk('public')->delete($hero->foto_hero);
        $hero->delete();

        return redirect()
            ->route('banner.index')
            ->with('success', 'Data berhasil dihapus');
    }

    // Di method index()
public function index(Request $request)
{
    $query = HeroSection::query();

    // Filter search
    if ($request->has('search') && $request->search != '') {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    // Filter status - DIPERBAIKI
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $heroes = $query->latest()->paginate(4);

    return view('banner.index', compact('heroes'));
}

// Di method store()
public function store(Request $request)
{
    $request->validate([
        'judul'     => 'required|string|max:250',
        'foto_hero' => 'required|image',
        'status'    => 'required|in:0,1', // validasi status
    ]);

    $fotoHero = $request->file('foto_hero')->store('hero', 'public');

    HeroSection::create([
        'judul'     => $request->judul,
        'foto_hero' => $fotoHero,
        'status'    => $request->status, // langsung ambil dari request
    ]);

    return redirect()
        ->route('banner.index')
        ->with('success', 'Data berhasil ditambahkan');
}

public function update(Request $request, $id)
{
    $hero = HeroSection::findOrFail($id);

    $request->validate([
        'judul' => 'required|string|max:250',
        'foto_hero' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'status'    => 'required|in:0,1',
    ]);

    $data = [
        'judul' => $request->judul,
        'status' => $request->status, // Pastikan ini benar
    ];

    if ($request->hasFile('foto_hero')) {
        // Hapus foto lama
        if ($hero->foto_hero && Storage::disk('public')->exists($hero->foto_hero)) {
            Storage::disk('public')->delete($hero->foto_hero);
        }
        $data['foto_hero'] = $request->file('foto_hero')->store('hero', 'public');
    }

    $hero->update($data);

    return redirect()
        ->route('banner.index')
        ->with('success', 'Data berhasil diperbarui');
}
}
