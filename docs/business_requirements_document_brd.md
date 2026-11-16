# Business Requirements Document (BRD)
**Proyek:** E-PARKING SYSTEM (Aplikasi Manajemen Parkir Terintegrasi)
**Posisi:** Programmer (Software Developer)

## 1. Pendahuluan
Dokumen ini menjelaskan kebutuhan bisnis untuk pembuatan **"E-PARKING SYSTEM"**. Aplikasi ini ditujukan untuk mengelola sistem perparkiran modern yang mencakup pencatatan kendaraan masuk dan keluar secara cepat (dengan dukungan quick-picker master kendaraan), manajemen area parkir berkapasitas dinamis, penetapan master tarif, serta pelaporan keuangan transparan untuk manajemen.

## 2. Tujuan Bisnis
*   Mendigitalisasi proses pencatatan parkir (masuk dan keluar) agar lebih cepat dan akurat.
*   Mengelola kapasitas area parkir secara *real-time*.
*   Menyediakan laporan rekapitulasi transaksi yang transparan bagi pemilik (Owner).
*   Meningkatkan keamanan melalui pencatatan log aktivitas sistem.

## 3. Pengguna / Aktor (User Roles)
Sistem ini akan digunakan oleh 3 (tiga) level pengguna dengan hak akses yang berbeda:
1.  **Admin:** Bertanggung jawab atas pengelolaan data master (pengguna, tarif, area, kendaraan) dan pemantauan sistem (log aktivitas).
2.  **Petugas (Operator):** Bertanggung jawab atas operasional sehari-hari di lapangan, yaitu memproses transaksi parkir dan mencetak struk.
3.  **Owner (Pemilik):** Bertanggung jawab atas pengawasan bisnis, membutuhkan akses ke laporan transaksi parkir.

## 4. Ruang Lingkup Kebutuhan Bisnis
Berdasarkan tabel matriks kebutuhan bisnis, berikut adalah fungsi utama yang harus ada:
*   **Otentikasi:** Semua pengguna wajib melakukan *Login* dan *Logout*.
*   **Manajemen Data (oleh Admin):** 
    *   Manajemen (CRUD) data User.
    *   Manajemen (CRUD) Tarif Parkir berdasarkan jenis kendaraan.
    *   Manajemen (CRUD) Area Parkir dan kapasitasnya.
    *   Manajemen (CRUD) data Kendaraan.
    *   Melihat Log Aktivitas sistem.
*   **Operasional Parkir (oleh Petugas):**
    *   Mencatat Transaksi kendaraan masuk dan keluar.
    *   Fitur *Quick-Select & Auto-fill* kendaraan dari Master Kendaraan untuk percepatan antrean di gerbang masuk.
    *   Mencetak Struk parkir untuk pelanggan (tiket masuk thermal dan struk keluar).
*   **Pelaporan (oleh Owner):**
    *   Melihat rekap transaksi sesuai dengan rentang waktu yang diminta (filter tanggal/waktu).