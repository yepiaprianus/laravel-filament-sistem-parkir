# Product Requirements Document (PRD)
**Proyek:** E-PARKING SYSTEM (Aplikasi Manajemen Parkir Terintegrasi)

## 1. Deskripsi Produk
E-PARKING SYSTEM adalah perangkat lunak berbasis web dan *database* yang menangani seluruh siklus operasional perparkiran modern secara efisien, responsif, dan mudah digunakan. Aplikasi ini dilengkapi *branding* E-PARKING SYSTEM, antarmuka Select2 modern, visualisasi kapasitas area *real-time*, serta pengisian otomatis (*auto-lookup*) kendaraan terdaftar di pos operasional masuk.

## 2. Fitur dan Fungsionalitas (Feature Matrix)

| Fitur / Modul | Admin | Petugas | Owner |
| :--- | :---: | :---: | :---: |
| Login & Logout | Ya | Ya | Ya |
| CRUD User | Ya | Tidak | Tidak |
| CRUD Tarif Parkir | Ya | Tidak | Tidak |
| CRUD Area Parkir | Ya | Tidak | Tidak |
| CRUD Kendaraan | Ya | Tidak | Tidak |
| Akses Log Aktifitas | Ya | Tidak | Tidak |
| Transaksi Parkir | Tidak | Ya | Tidak |
| Cetak Struk Parkir | Tidak | Ya | Tidak |
| Rekap Transaksi (Filter Waktu)| Tidak | Tidak | Ya |

## 3. Alur Kerja Utama (Flow/Pseudocode Dasar)

### A. Proses Login
1. **Input:** User memasukkan `username` dan `password`.
2. **Proses:** Sistem mencocokkan kredensial dengan `tb_user`. Mengecek status `status_aktif` dan `role`.
3. **Output:** Jika valid, arahkan (redirect) ke *dashboard* sesuai `role`. Catat aktivitas ke `tb_log_aktivitas`. Jika gagal, tampilkan pesan error.

### B. Proses Transaksi
**Kendaraan Masuk:**
1. **Input:** Petugas memasukkan nomor plat atau memilih dari *Quick-Dropdown Master Kendaraan*.
2. **Proses:** Sistem mencocokkan plat nomor secara *real-time*. Jika ditemukan pada master kendaraan, sistem melakukan *auto-fill* (jenis kendaraan, warna, pemilik). Petugas menentukan area parkir tujuan (sistem menampilkan status kapasitas sisa). Sistem membuat record baru di `tb_transaksi` dengan `status`='masuk' dan `waktu_masuk` = waktu saat ini, serta memperbarui okupansi `terisi` pada `tb_area_parkir`. Jika kendaraan belum terdaftar, sistem mendaftarkannya otomatis ke master kendaraan.
3. **Output:** Tiket masuk (virtual/cetak struk thermal karcis masuk).

**Kendaraan Keluar:**
1. **Input:** Petugas memindai/memasukkan id_parkir/nomor plat.
2. **Proses:** Sistem menghitung `durasi_jam` (waktu saat ini - waktu masuk). Mengambil nilai `tarif_per_jam` dari `tb_tarif`. Menghitung `biaya_total` (durasi * tarif). Mengubah `status` menjadi 'keluar' dan mengisi `waktu_keluar`.
3. **Output:** Konfirmasi pembayaran.

### C. Cetak Struk
1. **Input:** `id_parkir` dari transaksi yang baru saja selesai.
2. **Proses:** Mengambil detail dari `tb_transaksi`, `tb_kendaraan` (jika ada), dan `tb_tarif`.
3. **Output:** Struk dalam format cetak berisi (ID Transaksi, Plat Nomor, Waktu Masuk, Waktu Keluar, Durasi, Total Biaya, Nama Petugas).

## 4. Persyaratan Teknis (Non-Functional Requirements)
Sesuai dengan instruksi *Coding Guidelines*:
*   **Kinerja Database:** Harus memuat halaman dengan cepat. Gunakan *query* yang efisien (gunakan `JOIN` dengan tepat, jangan panggil seluruh data jika tidak perlu).
*   **Struktur Kode:** Gunakan prosedur dan fungsi (*procedures and functions*) untuk modularitas kode.
*   **Pengolahan Data:** Boleh menggunakan array untuk memproses kumpulan data sebelum di-render ke UI. Hindari *looping* bersarang (nested loops) yang tidak perlu agar hemat memori.
*   **Limitasi Data:** Selalu gunakan *limit* / paginasi saat memanggil/menampilkan data besar (misal: di log aktivitas atau daftar transaksi).