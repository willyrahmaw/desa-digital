# 🏛️ E-Desa: Sistem Informasi & Administrasi Desa Digital

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="280" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Security-AES--256--CBC-059669?style=for-the-badge&logo=security&logoColor=white" alt="AES-256">
  <img src="https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge" alt="License">
</p>

**E-Desa (Desa Digital)** adalah platform tata kelola pemerintahan dan administrasi desa terintegrasi berbasis standar **Permendagri**. Sistem ini dirancang untuk mewujudkan transparansi publik, digitalisasi pelayanan surat menyurat, pengamanan data identitas kependudukan sesuai **UU Perlindungan Data Pribadi (UU PDP No. 27/2022)**, serta portal informasi publik yang modern, responsif, dan *SEO-friendly*.

---

## 🌟 Fitur Utama (Key Features)

### 1. 🌐 Portal Publik & Transparansi Pemerintahan Desa
- **Beranda Interaktif**: Visualisasi statistik demografi penduduk (agama, dusun, pendidikan, pekerjaan), anggaran APBDes, berita terkini, dan profil desa.
- **Profil Desa & Aparatur**: Struktur organisasi perangkat desa resmi, visi & misi, demografi wilayah, dan sejarah desa.
- **Transparansi APBDes**: Rincian realisasi pendapatan, belanja, dan pembiayaan desa.
- **Direktori BUMDes & UMKM**: Katalog unit usaha desa dan produk pelaku usaha mikro warga desa.
- **Agenda & Galeri Publik**: Dokumentasi kegiatan, program kerja, dan kebudayaan desa.

### 2. 🛡️ Tata Kelola Kependudukan Terenkripsi (UU PDP Compliance)
- **Enkripsi Data Sensitif**: NIK dan Nomor KK dienkripsi secara *at-rest* di basis data menggunakan algoritma **AES-256-CBC**.
- **Pencarian Cepat & Aman**: Mendukung *deterministic indexing* sehingga pencarian dan relasi antar tabel tetap berjalan instan tanpa membuka kunci enkripsi secara global.
- **Integrasi Data Sosial / Bansos**: Manajemen status desil kesejahteraan warga (Desil 1 s/d Desil 4) untuk sinkronisasi kelayakan bantuan surat seperti SKTM.

### 3. 📜 Perancang Naskah Dinas & Mesin Penomoran Otomatis
- **Word 365 Executive Ribbon Editor**: Bilah alat penyuntingan naskah surat standar instansi pemerintah dengan kontrol tipografi (Times New Roman, Bookman Old Style, ukuran font dinamis, margin cetak A4, dan penyesuaian teks KOP).
- **Template Naskah Baku Permendagri**: Preset bawaan surat resmi (SKTM, SKU, SKD, SKCK, Keterangan Belum Menikah, Kematian, Kelahiran, Penghasilan).
- **Mesin Format Penomoran Dinamis**: Generator nomor surat otomatis dengan token variabel `[NOMOR_URUT]`, `[KODE_SURAT]`, `[ROMAN_BULAN]`, `[TAHUN]`, dan `[NAMA_DESA]`.
- **Verifikasi Dokumen Digital**: QR Code keabsahan surat publik dengan verifikasi UUID untuk mencegah pemalsuan dokumen.

### 4. 📢 Portal Aduan & Aspirasi Warga Berbasis NIK
- **Verifikasi Warga Otomatis**: Integrasi validasi NIK dengan proteksi *PII Data Masking* untuk melindungi privasi warga pelapor.
- **Sistem Tiket Pengaduan**: Registrasi tiket digital unik (`PGD-YYYY-XXXXX`) dan fitur pelacakan (*tracking*) status tindak lanjut aduan secara real-time.

### 5. 🔒 Arsitektur Keamanan Siber (Cyber Security Posture)
- **HTTP Security Headers**: Penerapan `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`, `Permissions-Policy`, dan `Strict-Transport-Security` (HSTS).
- **Role-Based Access Control (RBAC)**: Pembatasan ketat menu manajerial kritis (Manajemen Akun, Audit Log, dan Pengaturan Sistem) khusus peran `Super Admin`.
- **Anti Brute-Force & Rate Limiting**: Pembatasan frekuensi percobaan login dan *request throttling* pada endpoint publik.
- **Audit Logging**: Pencatatan riwayat login dan seluruh mutasi data penting oleh aparatur desa.

### 6. 🗺️ SEO & Google Search Console (GSC) Ready
- **Physical XML Sitemap**: Auto-generator berkas fisik `public/sitemap.xml` setiap ada penambahan atau pembaruan artikel berita.
- **Robots.txt Standar**: Konfigurasi perayapan mesin pencari dengan proteksi direktori administratif.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

| Lapisan | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12.x / PHP 8.2+ |
| **Basis Data** | MySQL / MariaDB (InnoDB) |
| **Frontend Styling** | TailwindCSS v4 + Custom Administrative CSS |
| **Interaktivitas UI** | Alpine.js 3.x |
| **Visualisasi Data** | Chart.js |
| **Kriptografi** | OpenSSL AES-256-CBC |
| **Testing** | PHPUnit (Feature & Unit Tests) |

---

## 🚀 Panduan Instalasi (Installation Guide)

### Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `openssl`, `pdo_mysql`, `mbstring`, `curl`, `fileinfo`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL >= 8.0 atau MariaDB >= 10.4

### Langkah Instalasi

1. **Clone Repository**:
   ```bash
   git clone git@github.com:willyrahmaw/desa-digital.git
   cd desa-digital
   ```

2. **Install Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Sesuaikan Konfigurasi Basis Data di `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e-desa
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat Tautan Berkas Penyimpanan (Storage Link)**:
   ```bash
   php artisan storage:link
   ```

7. **Generate Berkas Sitemap XML**:
   ```bash
   php artisan sitemap:generate
   ```

8. **Jalankan Server Pengembangan**:
   ```bash
   # Terminal 1: Server Backend
   php artisan serve --port=8001

   # Terminal 2: Asset Compiler
   npm run dev
   ```

9. **Akses Aplikasi**:
   - Portal Publik: `http://127.0.0.1:8001`
   - Panel Admin: `http://127.0.0.1:8001/login`

---

## 🔑 Kredensial Default (Default Accounts)

Setelah menjalankan `php artisan migrate --seed`, akun bawaan berikut tersedia untuk pengujian:

| Peran (Role) | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin@desa.id` | `password` |
| **Operator Desa** | `operator@desa.id` | `password` |
| **Kepala Desa** | `kades@desa.id` | `password` |

---

## 🧪 Menjalankan Pengujian Otomatis (Testing)

Untuk memvalidasi integritas logika bisnis, otorisasi, dan proteksi keamanan:

```bash
php artisan test
```

Untuk menjalankan pengujian spesifik sistem keamanan:
```bash
php artisan test --filter=SecurityAuthorizationTest
```

---

## 📁 Struktur Direktori Utama

```
e-desa/
├── app/
│   ├── Helpers/            # Helper Kriptografi AES-256 & Builders
│   ├── Http/
│   │   ├── Controllers/    # Controller Portal Publik & Panel Admin
│   │   ├── Middleware/     # SecurityHeadersMiddleware, RoleMiddleware
│   │   └── Requests/       # Form Request Validation
│   ├── Models/             # Eloquent Models & Audit Log Traits
│   ├── Repositories/       # Data Access Layer & Interfaces
│   └── Services/           # Business Logic Layer (Surat, Kependudukan, Sitemap)
├── config/                 # Konfigurasi Aplikasi & Keamanan
├── database/
│   ├── migrations/         # Skema Basis Data & Foreign Keys
│   └── seeders/            # Data Dummy Master & Wilayah
├── public/
│   ├── sitemap.xml         # Berkas Fisik XML Sitemap untuk GSC
│   └── robots.txt          # Aturan Perayapan Mesin Pencari
├── resources/
│   ├── css/                # TailwindCSS & Tema Instansi
│   ├── js/                 # Script Alpine.js & Komponen Interaktif
│   └── views/
│       ├── admin/          # Antarmuka Tata Usaha & Editor Surat
│       ├── layouts/        # Layout Admin & Portal Publik
│       └── public/         # Halaman Portal Publik Desa
└── routes/
    └── web.php             # Definisi Rute, Throttling & RBAC
```

---

## 📄 Lisensi

Aplikasi ini dilisensikan di bawah lisensi open-source **[MIT License](LICENSE)**.
