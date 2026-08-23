@extends('layouts.admin')

@section('title', 'Tulis Berita & Artikel Baru')
@section('breadcrumb-item', 'Tulis Berita')
@section('page-title', 'Tulis Berita & Artikel Desa')

@push('styles')
<link rel="stylesheet" href="{{ asset('jodit-editor/jodit.min.css') }}"/>
@endpush

@section('page-actions')
<a href="{{ route('admin.master.berita.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors">
    ← Kembali ke Daftar Berita
</a>
@endsection

@section('content')
<form action="{{ route('admin.master.berita.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── LEFT COLUMN: JUDUL & JODIT EDITOR KONTEN ───────────── --}}
        <div class="lg:col-span-8 space-y-6">

            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Konten Berita / Artikel</h2>

                {{-- Judul --}}
                <div class="mb-5">
                    <label for="judul" class="block text-xs font-bold text-slate-700 mb-1">Judul Berita / Artikel <span class="text-rose-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Ketik judul berita menarik..."
                           class="block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none">
                    @error('judul')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jodit Editor Konten --}}
                <div>
                    <label for="editor" class="block text-xs font-bold text-slate-700 mb-1">Isi Lengkap Artikel <span class="text-rose-500">*</span></label>
                    <textarea id="editor" name="konten" rows="12">{{ old('konten') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1.5">Jodit Editor memungkinkan drag & drop gambar, klik gambar untuk mengubah ukuran (resize handles) secara bebas.</p>
                    @error('konten')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN: KATEGORI, COVER & PUBLISH OPTIONS ───── --}}
        <div class="lg:col-span-4 space-y-6">

            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm sticky top-20">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Pengaturan Publikasi</h3>

                {{-- Kategori --}}
                <div class="mb-4">
                    <label for="kategori_berita_id" class="block text-xs font-bold text-slate-700 mb-1">Kategori Berita <span class="text-rose-500">*</span></label>
                    <select id="kategori_berita_id" name="kategori_berita_id" required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-600 focus:outline-none">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_berita_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_berita_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-600 focus:outline-none">
                        <option value="Publikasi" {{ old('status', 'Publikasi') == 'Publikasi' ? 'selected' : '' }}>Publikasi Langsung</option>
                        <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                    </select>
                </div>

                {{-- Tanggal Publikasi --}}
                <div class="mb-5">
                    <label for="tanggal_publikasi" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Publikasi</label>
                    <input type="date" id="tanggal_publikasi" name="tanggal_publikasi" value="{{ old('tanggal_publikasi', now()->toDateString()) }}"
                           class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-600 focus:outline-none">
                </div>

                {{-- Upload Cover Image --}}
                <div class="mb-6" x-data="{ preview: null }">
                    <label for="gambar_file" class="block text-xs font-bold text-slate-700 mb-1">Foto Sampul (Cover Image)</label>
                    
                    {{-- Image Preview Box --}}
                    <div class="mb-2 border border-dashed border-slate-300 rounded-lg p-2 text-center bg-slate-50 flex items-center justify-center min-h-[120px]">
                        <template x-if="preview">
                            <img :src="preview" class="max-h-36 rounded shadow-sm object-cover">
                        </template>
                        <template x-if="!preview">
                            <div class="text-slate-400 text-xs">
                                <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H2.25A1.5 1.5 0 00.75 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                <span>Pilih foto sampul</span>
                            </div>
                        </template>
                    </div>

                    <input type="file" id="gambar_file" name="gambar_file" accept="image/*"
                           @change="const file = $event.target.files[0]; if(file) { preview = URL.createObjectURL(file); }"
                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                    Terbitkan Berita / Artikel
                </button>
            </div>

        </div>

    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('jodit-editor/jodit.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('#editor')) {
        const editor = Jodit.make('#editor', {
            height: 420,
            placeholder: 'Tuliskan isi artikel atau berita desa secara lengkap...',
            theme: 'default',
            buttonSize: 'medium',
            uploader: {
                url: '{{ route("admin.master.berita.upload-image") }}',
                format: 'json',
                filesVariableName: function (i) {
                    return 'upload';
                },
                prepareData: function (formData) {
                    formData.append('_token', '{{ csrf_token() }}');
                },
                isSuccess: function (resp) {
                    return resp.uploaded === true || resp.isSuccess === true;
                },
                process: function (resp) {
                    return {
                        files: resp.files || [resp.url],
                        path: '',
                        baseurl: '',
                        error: resp.error ? resp.error.message : null,
                        msg: 'Upload Success'
                    };
                },
                defaultHandlerSuccess: function (data) {
                    const files = data.files || [];
                    if (files.length) {
                        files.forEach(url => {
                            this.selection.insertImage(url);
                        });
                    }
                }
            }
        });
    }
});
</script>
@endpush
