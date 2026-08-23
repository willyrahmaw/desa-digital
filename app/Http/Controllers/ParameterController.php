<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ParameterController extends Controller
{
    private array $allowedTypes = [
        'agama' => \App\Models\Agama::class,
        'pendidikan' => \App\Models\Pendidikan::class,
        'pekerjaan' => \App\Models\Pekerjaan::class,
        'golongan_darah' => \App\Models\GolonganDarah::class,
        'status_kawin' => \App\Models\StatusKawin::class,
        'status_tinggal' => \App\Models\StatusTinggal::class,
        'kewarganegaraan' => \App\Models\Kewarganegaraan::class,
        'jabatan' => \App\Models\Jabatan::class,
    ];

    private function getModel(string $type)
    {
        if (!array_key_exists($type, $this->allowedTypes)) {
            abort(404, 'Tipe parameter tidak ditemukan.');
        }
        return new $this->allowedTypes[$type];
    }

    private function getLabel(string $type): string
    {
        return Str::headline($type);
    }

    public function index(string $type, Request $request): View
    {
        $model = $this->getModel($type);
        $search = $request->get('search', '');
        
        $query = $model::query();
        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%");
        }
        $items = $query->latest()->paginate(10);
        $label = $this->getLabel($type);

        return view('admin.master.parameter.index', compact('items', 'search', 'type', 'label'));
    }

    public function store(string $type, Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
        ]);

        $model = $this->getModel($type);
        $model::create(['nama' => $request->nama]);

        return redirect()->route('admin.master.parameter.index', $type)
            ->with('success', "Parameter {$this->getLabel($type)} baru berhasil ditambahkan.");
    }

    public function update(string $type, Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
        ]);

        $model = $this->getModel($type);
        $record = $model::findOrFail($id);
        $record->update(['nama' => $request->nama]);

        return redirect()->route('admin.master.parameter.index', $type)
            ->with('success', "Data {$this->getLabel($type)} berhasil diperbarui.");
    }

    public function destroy(string $type, $id): RedirectResponse
    {
        $model = $this->getModel($type);
        $record = $model::findOrFail($id);
        
        try {
            $record->delete();
            return redirect()->route('admin.master.parameter.index', $type)
                ->with('success', "Data {$this->getLabel($type)} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.master.parameter.index', $type)
                ->with('error', "Gagal menghapus data {$this->getLabel($type)} karena data ini sedang digunakan oleh data penduduk atau perangkat desa.");
        }
    }
}
