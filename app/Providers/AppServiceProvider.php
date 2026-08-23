<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\DusunRepositoryInterface;
use App\Repositories\DusunRepository;
use App\Interfaces\RwRepositoryInterface;
use App\Repositories\RwRepository;
use App\Interfaces\RtRepositoryInterface;
use App\Repositories\RtRepository;
use App\Interfaces\PerangkatDesaRepositoryInterface;
use App\Repositories\PerangkatDesaRepository;
use App\Interfaces\KartuKeluargaRepositoryInterface;
use App\Repositories\KartuKeluargaRepository;
use App\Interfaces\PendudukRepositoryInterface;
use App\Repositories\PendudukRepository;
use App\Interfaces\DataSosialRepositoryInterface;
use App\Repositories\DataSosialRepository;
use App\Interfaces\TemplateSuratRepositoryInterface;
use App\Repositories\TemplateSuratRepository;
use App\Interfaces\SuratRepositoryInterface;
use App\Repositories\SuratRepository;
use App\Interfaces\PengaduanRepositoryInterface;
use App\Repositories\PengaduanRepository;
use App\Interfaces\BeritaRepositoryInterface;
use App\Repositories\BeritaRepository;
use App\Interfaces\AgendaRepositoryInterface;
use App\Repositories\AgendaRepository;
use App\Interfaces\AlbumGaleriRepositoryInterface;
use App\Repositories\AlbumGaleriRepository;
use App\Interfaces\UmkmProdukRepositoryInterface;
use App\Repositories\UmkmProdukRepository;
use App\Interfaces\BumdesLaporanRepositoryInterface;
use App\Repositories\BumdesLaporanRepository;
use App\Interfaces\ApbdesRepositoryInterface;
use App\Repositories\ApbdesRepository;
use App\Interfaces\UmkmPelakuRepositoryInterface;
use App\Repositories\UmkmPelakuRepository;
use App\Interfaces\UmkmKategoriRepositoryInterface;
use App\Repositories\UmkmKategoriRepository;
use App\Interfaces\BumdesUnitRepositoryInterface;
use App\Repositories\BumdesUnitRepository;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DusunRepositoryInterface::class, DusunRepository::class);
        $this->app->bind(RwRepositoryInterface::class, RwRepository::class);
        $this->app->bind(RtRepositoryInterface::class, RtRepository::class);
        $this->app->bind(PerangkatDesaRepositoryInterface::class, PerangkatDesaRepository::class);
        $this->app->bind(KartuKeluargaRepositoryInterface::class, KartuKeluargaRepository::class);
        $this->app->bind(PendudukRepositoryInterface::class, PendudukRepository::class);
        $this->app->bind(DataSosialRepositoryInterface::class, DataSosialRepository::class);
        $this->app->bind(TemplateSuratRepositoryInterface::class, TemplateSuratRepository::class);
        $this->app->bind(SuratRepositoryInterface::class, SuratRepository::class);
        $this->app->bind(PengaduanRepositoryInterface::class, PengaduanRepository::class);
        $this->app->bind(BeritaRepositoryInterface::class, BeritaRepository::class);
        $this->app->bind(AgendaRepositoryInterface::class, AgendaRepository::class);
        $this->app->bind(AlbumGaleriRepositoryInterface::class, AlbumGaleriRepository::class);
        $this->app->bind(UmkmProdukRepositoryInterface::class, UmkmProdukRepository::class);
        $this->app->bind(BumdesLaporanRepositoryInterface::class, BumdesLaporanRepository::class);
        $this->app->bind(ApbdesRepositoryInterface::class, ApbdesRepository::class);
        $this->app->bind(UmkmPelakuRepositoryInterface::class, UmkmPelakuRepository::class);
        $this->app->bind(UmkmKategoriRepositoryInterface::class, UmkmKategoriRepository::class);
        $this->app->bind(BumdesUnitRepositoryInterface::class, BumdesUnitRepository::class);
        $this->app->bind(\App\Interfaces\PublicRepositoryInterface::class, \App\Repositories\PublicRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamic permission mapping to Laravel Gates
        try {
            // Check if table exists to prevent migration/composer failures during setup
            if (app()->runningInConsole() === false || \Illuminate\Support\Facades\Schema::hasTable('permission')) {
                Permission::all()->each(function ($permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                });
            }
        } catch (\Exception $e) {
            // Silently catch during migration phases
        }
    }
}
