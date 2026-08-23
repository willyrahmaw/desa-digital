<?php

namespace App\Http\Controllers;

use App\Models\BannerHero;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class BannerHeroController extends Controller
{
    public function index(): View
    {
        $banners = BannerHero::orderBy('urutan', 'asc')->get();
        return view('admin.master.banner_hero.index', compact('banners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'urutan' => 'required|integer',
            'status_aktif' => 'nullable',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('banner_hero', 'public');
        }

        BannerHero::create([
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'tag' => $request->tag,
            'link_url' => $request->link_url,
            'button_text' => $request->button_text,
            'gambar' => $gambarPath,
            'urutan' => (int) $request->urutan,
            'status_aktif' => $request->has('status_aktif'),
        ]);

        return redirect()->route('admin.master.banner_hero.index')
            ->with('success', 'Banner Hero berhasil ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $banner = BannerHero::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'urutan' => 'required|integer',
            'status_aktif' => 'nullable',
        ]);

        $gambarPath = $banner->gambar;
        if ($request->hasFile('gambar')) {
            if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }
            $gambarPath = $request->file('gambar')->store('banner_hero', 'public');
        }

        $banner->update([
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'tag' => $request->tag,
            'link_url' => $request->link_url,
            'button_text' => $request->button_text,
            'gambar' => $gambarPath,
            'urutan' => (int) $request->urutan,
            'status_aktif' => $request->has('status_aktif'),
        ]);

        return redirect()->route('admin.master.banner_hero.index')
            ->with('success', 'Banner Hero berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $banner = BannerHero::findOrFail($id);

        if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        return redirect()->route('admin.master.banner_hero.index')
            ->with('success', 'Banner Hero berhasil dihapus.');
    }
}
