# 🅿️ Parking System Management (Filament v4)

Sistem manajemen transaksi parkir modern yang dibangun dengan **Laravel 12** dan **Filament v4**. Aplikasi ini dirancang untuk menangani alur masuk dan keluar kendaraan secara efisien, penghitungan tarif otomatis, serta manajemen tipe kendaraan.

---

## 🌟 Fitur Utama

- **Dashboard Statistik**: Ringkasan jumlah kendaraan yang sedang parkir dan total pendapatan.
- **Manajemen Transaksi**: Pencatatan kendaraan masuk, pencatatan waktu keluar, dan status pembayaran.
- **Filtering Kendaraan**: Fitur filter cerdas berdasarkan tipe kendaraan (Mobil, Motor, dll) menggunakan Resource Filament.
- **Manajemen Tarif**: Pengaturan tarif berbeda untuk setiap jenis kendaraan.
- **Sistem Aksi (Action)**: Integrasi tombol aksi langsung pada tabel untuk mempercepat proses _check-out_ kendaraan.
- **Cetak Struk**: Cetak struk parkir saat kendaraan keluar

## 🛠️ Tech Stack

- **Framework:** [Laravel 12](https://laravel.com)
- **Admin Panel:** [Filament v4](https://filamentphp.com)
- **Database:** MySQL
- **Styling:** Tailwind CSS
- **Tools:** Vite

## 📦 Panduan Instalasi

Pastikan Anda sudah menginstal PHP >= 8.2 dan Composer di perangkat Anda.

1. **Clone Repository**

   ```bash
   git clone [https://github.com/yepiaprianus/laravel-filament-sistem-parkir.git](https://github.com/yepiaprianus/laravel-filament-sistem-parkir.git)
   cd parking-system-filament
   ```
2. **Instal Dependensi**

```bash
   composer install
```

3. **Konfigurasi Environment**

```bash
   cp .env.example .env
   php artisan key:generate
```

4.**Migrasi Database**

```bash
php artisan migrate --seed
```

5. **Jalankan Server**

```bash
php artisan serve
```

6. **User default**

```admin
admin

officer
officer

owner
owner
```
