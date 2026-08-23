<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DusunController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\PerangkatDesaController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\DataSosialController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriBeritaController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\UmkmPelakuController;
use App\Http\Controllers\UmkmKategoriController;
use App\Http\Controllers\BumdesController;
use App\Http\Controllers\BumdesUnitController;
use App\Http\Controllers\ApbdesController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\KlasifikasiSuratController;
use App\Http\Controllers\PengaturanPenomoranController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPengaduanController;

/*
|--------------------------------------------------------------------------
| Public Routes (Portal & Website Resmi Desa)
|--------------------------------------------------------------------------
*/
Route::name('public.')->group(function () {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/profil', [PublicController::class, 'profil'])->name('profil');
    Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
    Route::get('/berita', [PublicController::class, 'berita'])->name('berita.index');
    Route::get('/berita/{slug}', [PublicController::class, 'beritaShow'])->name('berita.show');
    Route::get('/agenda', [PublicController::class, 'agenda'])->name('agenda');
    Route::get('/umkm', [PublicController::class, 'umkm'])->name('umkm');
    Route::get('/galeri', [PublicController::class, 'galeri'])->name('galeri');
    
    // Pengaduan Public Routes
    Route::get('/pengaduan', [PublicPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::post('/pengaduan', [PublicPengaduanController::class, 'store'])->name('pengaduan.store');
    Route::post('/pengaduan/check-nik', [PublicPengaduanController::class, 'checkNik'])->name('pengaduan.check_nik');
    Route::post('/pengaduan/track', [PublicPengaduanController::class, 'trackStatus'])->name('pengaduan.track');
});

// Verification Route
Route::get('/verifikasi/{uuid}', [VerifikasiController::class, 'verify'])->name('public.verifikasi');

// SEO Sitemap & Robots
Route::get('/sitemap.xml', function () {
    $beritas = \App\Models\Berita::latest()->get();
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= '<url><loc>' . url('/') . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
    $xml .= '<url><loc>' . route('public.profil') . '</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . route('public.layanan') . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . route('public.berita.index') . '</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
    
    foreach ($beritas as $berita) {
        $xml .= '<url><loc>' . route('public.berita.show', $berita->slug ?? $berita->id) . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>';
    }
    
    $xml .= '<url><loc>' . route('public.agenda') . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . route('public.umkm') . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . route('public.galeri') . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>';
    $xml .= '<url><loc>' . route('public.pengaduan.index') . '</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'text/xml');
});

Route::get('/robots.txt', function () {
    $robots = "User-agent: *\nDisallow: /admin/\nAllow: /\nSitemap: " . url('/sitemap.xml');
    return response($robots, 200)->header('Content-Type', 'text/plain');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Auth Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Master Data Group
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('dusun', DusunController::class)->except(['create', 'show', 'edit']);
            Route::resource('rw', RwController::class)->except(['create', 'show', 'edit']);
            Route::resource('rt', RtController::class)->except(['create', 'show', 'edit']);
            Route::resource('perangkat_desa', PerangkatDesaController::class)->except(['create', 'show', 'edit']);
            Route::resource('kartu_keluarga', KartuKeluargaController::class)->except(['create', 'show', 'edit']);
            Route::resource('penduduk', PendudukController::class)->except(['show']);
            Route::resource('data_social', DataSosialController::class)->except(['create', 'show', 'edit']);
            Route::resource('template_surat', TemplateSuratController::class);
            Route::resource('surat', SuratController::class)->except(['show', 'edit']);
            Route::post('surat/{id}/approve', [SuratController::class, 'approve'])->name('surat.approve');
            Route::post('surat/{id}/reject', [SuratController::class, 'reject'])->name('surat.reject');
            Route::get('surat/{id}/print', [SuratController::class, 'print'])->name('surat.print');
            Route::get('surat/preview-nomor', [SuratController::class, 'previewNomor'])->name('surat.preview_nomor');
            Route::resource('klasifikasi_surat', KlasifikasiSuratController::class)->except(['create', 'show', 'edit']);
            Route::resource('pengaduan', PengaduanController::class)->except(['create', 'edit']);
            Route::post('pengaduan/{id}/respond', [PengaduanController::class, 'respond'])->name('pengaduan.respond');
            Route::resource('berita', BeritaController::class)->except(['show']);
            Route::post('berita/upload-image', [BeritaController::class, 'uploadImage'])->name('berita.upload-image');
            Route::resource('kategori_berita', KategoriBeritaController::class)->except(['create', 'show', 'edit']);
            Route::resource('agenda', AgendaController::class)->except(['create', 'show', 'edit']);
            Route::resource('galeri', GaleriController::class)->except(['create', 'show', 'edit']);
            Route::resource('umkm', UmkmController::class)->except(['create', 'show', 'edit']);
            Route::resource('umkm-pelaku', UmkmPelakuController::class)->except(['create', 'show', 'edit']);
            Route::resource('umkm-kategori', UmkmKategoriController::class)->except(['create', 'show', 'edit']);
            Route::resource('bumdes', BumdesController::class)->except(['create', 'show', 'edit']);
            Route::resource('bumdes-unit', BumdesUnitController::class)->except(['create', 'show', 'edit']);
            Route::resource('apbdes', ApbdesController::class)->except(['create', 'show', 'edit']);
            Route::resource('banner_hero', \App\Http\Controllers\BannerHeroController::class)->except(['create', 'show', 'edit']);

            Route::get('parameter/{type}', [ParameterController::class, 'index'])->name('parameter.index');
            Route::post('parameter/{type}', [ParameterController::class, 'store'])->name('parameter.store');
            Route::put('parameter/{type}/{id}', [ParameterController::class, 'update'])->name('parameter.update');
            Route::delete('parameter/{type}/{id}', [ParameterController::class, 'destroy'])->name('parameter.destroy');
        });

        Route::resource('user', UserManagementController::class)->except(['create', 'show', 'edit']);
        Route::get('audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::resource('penomoran', PengaturanPenomoranController::class)->except(['create', 'show', 'edit']);
            Route::post('penomoran/preview', [PengaturanPenomoranController::class, 'preview'])->name('penomoran.preview');
        });
    });
});
