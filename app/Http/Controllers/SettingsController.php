<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Pengaturan::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:20',
            'alamat_kantor' => 'nullable|string|max:500',
            'email_desa' => 'nullable|email|max:255',
            'telp_desa' => 'nullable|string|max:50',
            'nama_kades' => 'nullable|string|max:255',
            'nama_sekdes' => 'nullable|string|max:255',
            'motto_desa' => 'nullable|string|max:500',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'logo_desa' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'favicon' => 'nullable|mimes:ico,png,jpg,jpeg,webp,svg|max:2048',
        ]);

        $data = $request->except(['_token', 'logo_desa', 'favicon']);

        // Handle Logo Desa Upload
        if ($request->hasFile('logo_desa')) {
            $oldLogo = Pengaturan::where('key', 'logo_desa')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('logo_desa')->store('settings', 'public');
            $data['logo_desa'] = $logoPath;
        }

        // Handle Favicon / Icon Desa Upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = Pengaturan::where('key', 'favicon')->value('value');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = $faviconPath;
            $data['icon_desa'] = $faviconPath;
        }

        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'general'
                ]
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem & logo/icon desa berhasil disimpan.');
    }
}
