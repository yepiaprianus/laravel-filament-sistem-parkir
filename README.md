# 🅿️ Sistem Manajemen Parkir - Versi Laravel Murni (Blade MVC)

> **Branch:** `laravel-murni`  
> Proyek aplikasi sistem perparkiran yang dibangun menggunakan **Laravel 12** murni (Blade Templates, Tailwind CSS, Standard MVC Controller, Service Layer, dan Database Schema sesuai ERD dokumen soal).

---

## 🌟 Fitur Utama Sesuai Dokumen BRD & PRD

1. **Otentikasi & Otorisasi Berbasis Role:**
   - 3 Level Aktor: **Admin**, **Petugas (Operator)**, dan **Owner (Pemilik)**.
   - Pengecekan status akun aktif (`status_aktif`) dan pencatatan log otomatis saat login/logout.
   - Redirection otomatis ke dashboard masing-masing role.
2. **Master Data (Admin):**
   - CRUD User (`tb_user`)
   - CRUD Area Parkir & Kapasitas (`tb_area_parkir`)
   - CRUD Tarif Parkir per Jam berdasarkan jenis kendaraan (`tb_tarif`)
   - CRUD Master Kendaraan (`tb_kendaraan`)
   - Audit Log Aktivitas Sistem (`tb_log_aktivitas`) dengan paginasi dan filter.
3. **Operasional Lapangan (Petugas):**
   - Pencatatan kendaraan masuk dengan validasi kapasitas area & pencegahan plat nomor ganda.
   - Otomatis mencetak **Tiket Masuk**.
   - Pemrosesan kendaraan keluar dengan kalkulasi durasi jam otomatis (pembulatan ke atas, min 1 jam) dan total biaya berdasarkan tarif kendaraan.
   - Otomatis mencetak **Struk Keluar Pembayaran**.
4. **Laporan & Rekapitulasi (Owner):**
   - Ringkasan total pendapatan, total transaksi selesai, dan kendaraan parkir aktif.
   - Filter transaksi berdasarkan rentang tanggal (`dari_tanggal` s/d `sampai_tanggal`), jenis kendaraan, dan status.
   - Format cetak laporan rekapitulasi bersih (siap cetak / export PDF).

---

## 🗄️ Skema Database (Sesuai ERD Dokumen Soal)

- `tb_user`: Menyimpan data user (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `status_aktif`)
- `tb_area_parkir`: Menyimpan lokasi dan kapasitas (`id_area`, `nama_area`, `kapasitas`, `terisi`)
- `tb_tarif`: Master tarif per jam (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`)
- `tb_kendaraan`: Master plat nomor (`id_kendaraan`, `plat_nomor`, `jenis_kendaraan`, `warna`, `pemilik`, `id_user`)
- `tb_transaksi`: Transaksi parkir (`id_parkir`, `id_kendaraan`, `waktu_masuk`, `waktu_keluar`, `id_tarif`, `durasi_jam`, `biaya_total`, `status`, `id_user`, `id_area`)
- `tb_log_aktivitas`: Catatan audit aktivitas (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`)

---

## 🚀 Panduan Menjalankan Proyek

### 1. Pindah ke Branch Ini
```bash
git checkout laravel-murni
```

### 2. Instalasi Dependensi
```bash
composer install
```

### 3. Konfigurasi Environment & Database
Salin `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```
*Secara default, proyek sudah dikonfigurasi menggunakan SQLite (`database/database.sqlite`) sehingga dapat langsung dijalankan tanpa perlu setup database server terpisah. Jika ingin menggunakan MySQL, cukup ubah `DB_CONNECTION=mysql` dan sesuaikan kredensial di `.env`.*

### 4. Migrasi & Seeder Data Awal
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Server
```bash
php artisan serve
```
Akses di browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 6. Menjalankan Pengujian Otomatis (Unit & Feature Tests)
```bash
php artisan test
```

---

## 🔑 Kredensial Default (Seeder)

| Role | Username | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin` | Master User, Area, Tarif, Kendaraan, Log Aktivitas |
| **Petugas** | `petugas` | `petugas` | Transaksi Masuk, Transaksi Keluar, Cetak Struk |
| **Owner** | `owner` | `owner` | Dashboard Keuangan & Rekap Laporan Transaksi |
