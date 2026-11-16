# Panduan Penguji & Bank Pertanyaan Kritis Uji Kompetensi Keahlian (UKK)
**Program Keahlian:** Rekayasa Perangkat Lunak (RPL)  
**Institusi / Sekolah:** SMK Pabelan  
**Nama Proyek:** E-PARKING SYSTEM (Sistem Manajemen & Otomasi Parkir Terintegrasi)  
**Tahun Ajaran:** 2025 / 2026  
**Penyusun / Penguji:** Tim Penguji Eksternal / Asesor Kompetensi RPL  

---

## 1. Distribusi Bobot Penilaian

Penilaian proyek UKK ini dibagi menjadi 3 (tiga) aspek utama dengan total akumulasi **100%**:

| No | Aspek Penilaian | Bobot | Fokus Penilaian Utama |
| :---: | :--- | :---: | :--- |
| **1** | **Konsep Pemrograman** | **35%** | Pemahaman arsitektur MVC, paradigma OOP, relasi ORM (Eloquent), keamanan (*Security: Auth, CSRF, Hashing, SQLi Prevention*), dan *State Management*. |
| **2** | **Prosedur dan Function** | **35%** | Modularitas kode (*clean code*), *Service Layer*, *Single Responsibility*, penanganan eksepsi (*Exception Handling* / *DB Transaction*), efisiensi algoritma perhitungan tarif, dan fungsi helper. |
| **3** | **Desain Aplikasi** | **30%** | Desain skema database relasional (Normalisasi & ERD), pengalaman pengguna (*UI/UX Responsiveness* & interaktivitas Select2), auditabilitas (*Activity Log*), serta kerapian tata letak sistem. |
| **Total** | | **100%** | **Standar Kelulusan Minimal (SKM): 75 / 100** |

---

## 2. Bank Pertanyaan Kritis & Panduan Penguji

---

### ASPEK 1: Konsep Pemrograman (Bobot: 35%)
> **Tujuan Pengujian:** Menguji apakah siswa benar-benar memahami arsitektur perangkat lunak, cara kerja framework, dan keamanan web, bukan sekadar meniru tutorial (*copy-paste*).

#### Pertanyaan 1.1 (Arsitektur MVC & Separation of Concerns)
* **Pertanyaan:**  
  *"Di mana kamu meletakkan logika perhitungan biaya parkir dan pengecekan kapasitas area? Mengapa logika tersebut tidak diletakkan langsung di dalam Controller atau View (Blade)?"*
* **Ekspektasi Jawaban Siswa:**  
  Siswa harus mampu menjelaskan prinsip *Separation of Concerns*:
  - **View:** Hanya bertanggung jawab untuk presentasi dan antarmuka pengguna (UI/Blade). Meletakkan logika perhitungan di view merusak maintainability.
  - **Controller:** Berfungsi sebagai *traffic controller* / pengatur lalu lintas request HTTP (menerima input, memanggil validasi, memanggil service/model, dan mengembalikan response/redirect).
  - **Service / Model Layer:** Logika bisnis (*business logic*) ditempatkan pada `ParkingService` atau *Model methods* agar reusable, mudah diuji secara otomatis (*automated testing*), dan tidak membuat Controller menjadi gemuk (*Fat Controller*).
* **Tanda Red Flag (Kurang Paham):**  
  Menjawab: *"Biar rapi saja pak"* tanpa bisa menjelaskan bahaya *fat controller* atau ketidakmampuan menggunakan kembali (*reusability*) kode tersebut.

---

#### Pertanyaan 1.2 (Eloquent ORM & Relasi Database)
* **Pertanyaan:**  
  *"Coba jelaskan relasi antara tabel `tb_transaksi`, `tb_kendaraan`, `tb_area_parkir`, dan `tb_tarif`. Method Eloquent apa yang kamu pakai di Model dan mengapa menggunakan ORM lebih aman daripada raw query `mysqli_query`?"*
* **Ekspektasi Jawaban Siswa:**  
  - Relasi:
    - `Transaksi` *belongsTo* `Kendaraan` (Foreign Key: `id_kendaraan`)
    - `Transaksi` *belongsTo* `AreaParkir` (Foreign Key: `id_area`)
    - `Transaksi` *belongsTo* `Tarif` (Foreign Key: `id_tarif`)
    - `AreaParkir` *hasMany* `Transaksi`
  - Keamanan ORM: Eloquent secara default menggunakan *PDO Parameter Binding*, sehingga secara otomatis kebal terhadap serangan **SQL Injection**, berbeda dengan penggabungan string langsung (*string concatenation*) pada query manual.
* **Tanda Red Flag:**  
  Tidak tahu arti `belongsTo` vs `hasMany`, atau tidak tahu kolom foreign key mana yang menghubungkan kedua model tersebut.

---

#### Pertanyaan 1.3 (Keamanan & Autentikasi: Hash, CSRF, & Middleware)
* **Pertanyaan:**  
  1. *"Mengapa di form login dan transaksi wajib ada `@csrf`? Apa yang terjadi jika saya menghapus directive tersebut?"*  
  2. *"Bagaimana sistem memastikan petugas kasir tidak bisa membuka menu Kelola User milik Admin hanya dengan mengetik URL `/admin/users` secara langsung di browser?"*
* **Ekspektasi Jawaban Siswa:**  
  1. **CSRF (Cross-Site Request Forgery):** `@csrf` menyisipkan token acak berbasis session untuk memvalidasi bahwa request form benar-benar berasal dari aplikasi kita, bukan kiriman berbahaya dari situs pihak ketiga atas nama user yang sedang login. Jika dihapus, Laravel akan melempar error `419 Page Expired`.
  2. **Role-Based Access Control (RBAC):** Sistem dilindungi oleh `RoleMiddleware`. Saat route diakses, middleware mencegat request, memeriksa `Auth::user()->role`, dan jika role tidak cocok (misal: role 'petugas' mengakses route admin), request akan ditolak (redirect atau abort 403 Forbidden).
* **Tanda Red Flag:**  
  Mengira password disimpan dalam bentuk teks biasa (plaintext) atau tidak mengerti konsep middleware sebagai penjaga gerbang route.

---

#### Pertanyaan 1.4 (Pencegahan Validitas State Ganda / Concurrency Logic)
* **Pertanyaan:**  
  *"Bagaimana sistem kamu mencegah agar mobil dengan plat 'B 1234 ABC' tidak bisa dimasukkan dua kali ke dalam parkiran padahal mobil tersebut belum pernah keluar (masih parkir)?"*
* **Ekspektasi Jawaban Siswa:**  
  Siswa harus menunjukkan baris kode di `ParkingService` atau validasi Controller:
  - Sebelum transaksi masuk dibuat, sistem melakukan pengecekan `Transaksi::whereHas('kendaraan', ...)->where('status', 'masuk')->first()`.
  - Jika transaksi aktif ditemukan, sistem melempar `Exception` dan menolak proses check-in.
* **Tanda Red Flag:**  
  Siswa baru menyadari celah tersebut saat penguji bertanya, atau tidak tahu di mana pengecekan tersebut berada.

---

### ASPEK 2: Prosedur dan Function (Bobot: 35%)
> **Tujuan Pengujian:** Menguji kemampuan siswa dalam merancang fungsi yang efisien, menerapkan penanganan kesalahan (*error handling*), dan struktur kode yang bersih (*Clean Code*).

#### Pertanyaan 2.1 (Penerapan Service Pattern & Dekomposisi Fungsi)
* **Pertanyaan:**  
  *"Di dalam `ParkingService`, ada function `prosesMasuk()` dan `prosesKeluar()`. Apa saja parameter yang dibutuhkan fungsi tersebut dan nilai kembalian (return value) apa yang dihasilkan?"*
* **Ekspektasi Jawaban Siswa:**  
  - `prosesMasuk(array $data, int $idUser): Transaksi`: Menerima array data tervalidasi dan ID user yang sedang bertugas. Mengembalikan instance model `Transaksi` yang baru dibuat.
  - `prosesKeluar(int $idParkir, int $idUser): Transaksi`: Menerima ID transaksi parkir dan ID user petugas checkout. Mengembalikan model `Transaksi` dengan status 'keluar', durasi jam, dan total biaya yang telah dikalkulasi.
* **Tanda Red Flag:**  
  Tidak bisa membaca signature fungsi buatan sendiri atau tidak memahami konsep *parameter passing* dan *return type*.

---

#### Pertanyaan 2.2 (Database Transaction & Data Consistency)
* **Pertanyaan:**  
  *"Pada saat kendaraan keluar, ada beberapa langkah yang terjadi: (1) update waktu keluar & total biaya, (2) update kapasitas terisi pada area parkir, dan (3) mencatat log aktivitas. Apa yang terjadi jika langkah ke-1 berhasil tapi server mati mendadak di langkah ke-2? Bagaimana caramu menjamin datanya tetap konsisten?"*
* **Ekspektasi Jawaban Siswa:**  
  - Menggunakan **Database Transaction** (`DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()` di dalam blok `try { ... } catch (Exception $e) { ... }`).
  - Jika terjadi error di tengah jalan, seluruh perubahan database akan di-rollback ke kondisi semula sehingga kapasitas area tidak menjadi tidak sinkron (*data inconsistency*).
* **Tanda Red Flag:**  
  Tidak pernah mendengar istilah database transaction / commit / rollback.

---

#### Pertanyaan 2.3 (Algoritma Perhitungan Waktu & Tarif)
* **Pertanyaan:**  
  *"Bagaimana algoritma pembulatan jam parkir yang kamu terapkan? Jika seorang pengendara parkir selama 1 jam 4 menit, berapa jam yang dihitung dan bagaimana implementasi kodenya di PHP?"*
* **Ekspektasi Jawaban Siswa:**  
  - Siswa menjelaskan metode pembulatan per jam ke atas (*ceil* / pembulatan ke atas):
    ```php
    $durasiMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
    $durasiJam = max(1, (int) ceil($durasiMenit / 60));
    $biayaTotal = $durasiJam * $tarif->tarif_per_jam;
    ```
  - Parkir 1 jam 4 menit = 64 menit. $64 / 60 = 1.066$, di-*ceil* menjadi **2 jam**.
  - Menggunakan `max(1, ...)` memastikan durasi minimal adalah 1 jam meskipun parkir hanya 5 menit.
* **Tanda Red Flag:**  
  Menggunakan pembulatan biasa (`round`) atau pembulatan ke bawah (`floor`) sehingga parkir 1 jam 20 menit hanya dihitung 1 jam, atau tidak bisa menulis rumus perhitungan waktunya.

---

#### Pertanyaan 2.4 (Modularitas Seeder)
* **Pertanyaan:**  
  *"Kenapa seeder database kamu dipisah menjadi `UserSeeder`, `TarifSeeder`, dan `AreaParkirSeeder` daripada ditulis menumpuk jadi satu di `DatabaseSeeder`?"*
* **Ekspektasi Jawaban Siswa:**  
  - **Single Responsibility Principle (SRP):** Setiap seeder bertanggung jawab hanya atas satu domain data master.
  - **Fleksibilitas:** Memungkinkan menjalankan seeder tertentu secara spesifik (misal: `php artisan db:seed --class=TarifSeeder`) tanpa harus me-reset data user atau transaksi.
  - **Kolaborasi Tim:** Memudahkan pengerjaan bersama banyak programmer tanpa konflik git merge pada satu file seeder besar.
* **Tanda Red Flag:**  
  Menjawab sekadar mengikuti template tanpa paham alasan pembagian file.

---

### ASPEK 3: Desain Aplikasi (Bobot: 30%)
> **Tujuan Pengujian:** Menguji kemampuan perancangan skema relasional, normalisasi basis data, ergonomi antarmuka pengguna (UI/UX), serta fitur produktivitas operator.

#### Pertanyaan 3.1 (Normalisasi Basis Data & Skema ERD)
* **Pertanyaan:**  
  *"Mengapa nominal tarif per jam tidak disimpan langsung di tabel `tb_kendaraan` saja, melainkan dibuatkan tabel khusus `tb_tarif`? Jelaskan dari sudut pandang normalisasi basis data!"*
* **Ekspektasi Jawaban Siswa:**  
  - **Pencegahan Redundansi (Normalisasi 2NF & 3NF):** Jika tarif disimpan di tabel kendaraan, maka ribuan data motor akan mengulang angka '2000' berulang kali.
  - **Pencegahan Anomali Update:** Jika tarif motor dinaikkan dari Rp 2.000 menjadi Rp 3.000, tanpa tabel `tb_tarif`, kita harus meng-update ribuan record kendaraan. Dengan tabel tarif terpisah, admin hanya cukup mengubah 1 baris saja di `tb_tarif`.
* **Tanda Red Flag:**  
  Tidak memahami istilah redundansi data atau anomali pembaharuan data (*update anomaly*).

---

#### Pertanyaan 3.2 (Desain UX di Pos Masuk: Auto-Lookup & Select2)
* **Pertanyaan:**  
  *"Di pos masuk kendaraan, kamu mengintegrasikan dropdown Select2 kendaraan terdaftar dan auto-fill saat mengetik plat nomor. Mengapa fitur ini penting bagi petugas loket dan bagaimana cara kerja logikanya di sisi front-end?"*
* **Ekspektasi Jawaban Siswa:**  
  - **Ergonomi & Kecepatan Layanan:** Mengurangi waktu tunggu antrean di loket parkir. Petugas tidak perlu mengetik ulang jenis kendaraan, warna, dan pemilik bagi kendaraan langganan/terdaftar.
  - **Cara Kerja:** 
    - Saat dropdown Select2 dipilih, event `change` membaca atribut `data-jenis`, `data-warna`, dan `data-pemilik` lalu mengisi field form secara instan.
    - Saat petugas mengetik plat di input text, script JavaScript mencocokkan input dengan objek JSON master kendaraan (`vehicleMap`), dan jika cocok, otomatis mengisi jenis kendaraan dan memunculkan badge verifikasi hijau.
* **Tanda Red Flag:**  
  Tidak tahu cara JavaScript berinteraksi dengan elemen DOM atau tidak mengerti event listener (`input`, `change`).

---

#### Pertanyaan 3.3 (Auditability: Log Aktivitas Sistem)
* **Pertanyaan:**  
  *"Mengapa di aplikasi parkir ini ada tabel `tb_log_aktivitas`? Informasi apa saja yang dicatat dan mengapa fitur ini sangat krusial bagi Owner?"*
* **Ekspektasi Jawaban Siswa:**  
  - **Tujuan Audit & Akuntabilitas:** Mencegah kecurangan (*fraud*) atau penghapusan data sepihak oleh oknum petugas/admin.
  - **Informasi:** Mencatat siapa pelakunya (`id_user`), waktu kejadian, dan deskripsi aktivitas (misal: "Petugas [nama] memproses keluar kendaraan B 1234 ABC", "Admin mengubah tarif motor").
  - **Manfaat bagi Owner:** Memberikan transparansi penuh terhadap semua aktivitas operasional yang berdampak pada keuangan.
* **Tanda Red Flag:**  
  Menganggap log aktivitas hanya fitur pelengkap tanpa tahu nilai bisnisnya terhadap integritas audit.

---

## 3. Uji Tantangan Spontan (Live Coding Challenge)
Penguji disarankan memilih **salah satu** dari tantangan di bawah ini untuk menguji apakah siswa benar-benar menguasai kode programnya:

### Tantangan A: Mengubah Kebijakan Durasi Parkir (Waktu pengerjaan: 5 - 10 Menit)
* **Instruksi ke Siswa:**  
  *"Sekarang ubah logika perhitungan parkir: Berikan masa tenggang (grace period) bebas biaya parkir jika kendaraan keluar dalam waktu kurang dari atau sama dengan 10 menit (gratis / Rp 0). Tunjukkan baris kode mana yang kamu ubah dan uji di browser!"*
* **Solusi Kode yang Diharapkan:**
  Di file `app/Models/Transaksi.php` atau `ParkingService.php`:
  ```php
  $durasiMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
  if ($durasiMenit <= 10) {
      return 0; // Bebas biaya jika <= 10 menit
  }
  ```

### Tantangan B: Menambahkan Filter Sederhana (Waktu pengerjaan: 5 - 10 Menit)
* **Instruksi ke Siswa:**  
  *"Di menu Laporan Owner, tambahkan opsi urutan agar data transaksi bisa diurutkan dari biaya tertinggi ke terendah. Tunjukkan perubahannya di Controller!"*
* **Solusi Kode yang Diharapkan:**
  Di `LaporanController.php`:
  ```php
  $query->orderByDesc('biaya_total');
  ```

---

## 4. Lembar Rubrik & Rekapitulasi Skor Penguji

Gunakan skala skor **1 – 4** untuk setiap indikator, kemudian hitung nilai akhir:

| No | Komponen yang Dinilai | Skor (1 - 4) | Bobot | Nilai Akhir (Skor × Bobot) |
| :---: | :--- | :---: | :---: | :---: |
| **I** | **Konsep Pemrograman (Bobot 35%)** | | | |
| 1.1 | Pemahaman arsitektur MVC & alur request Laravel | | 10% | |
| 1.2 | Penguasaan Eloquent ORM & Relasi Antar-Model | | 10% | |
| 1.3 | Keamanan aplikasi (Auth, Role Middleware, Hash, CSRF) | | 15% | |
| **II** | **Prosedur dan Function (Bobot 35%)** | | | |
| 2.1 | Pemisahan logika bisnis (Service Layer / Helper Methods) | | 10% | |
| 2.2 | Penanganan error & transaksi database (*Exception & DB Transactions*) | | 10% | |
| 2.3 | Algoritma perhitungan durasi, pembulatan tarif, & modularitas seeder | | 15% | |
| **III** | **Desain Aplikasi (Bobot 30%)** | | | |
| 3.1 | Normalisasi basis data & struktur ERD sesuai spesifikasi | | 10% | |
| 3.2 | Ergonomi antarmuka (Select2, Auto-lookup plat, UX loket kasir) | | 10% | |
| 3.3 | Desain responsif, branding (E-PARKING SYSTEM), & audit log | | 10% | |
| **TOTAL** | | | **100%** | **(Maksimal: 100)** |

### Pedoman Pemberian Skor (1 - 4):
* **Skor 4 (Sangat Kompeten / Mahir):** Menjawab cepat, logis, menunjukkan baris kode dengan tepat, serta memahami implikasi keamanan & arsitektur.
* **Skor 3 (Kompeten):** Menjawab benar sesuai konsep, dapat menunjukkan kode, namun membutuhkan sedikit pancingan pada aspek tingkat lanjut.
* **Skor 2 (Cukup Kompeten):** Menjawab fungsi secara umum namun kesulitan menjelaskan alasan teknis di balik struktur kode yang dibuat.
* **Skor 1 (Belum Kompeten):** Tidak memahami alur kode, tidak bisa menjawab konsep dasar, atau terindikasi tidak membuat sendiri aplikasinya.

---

**Keputusan Akhir Penguji:**  
*( ) Sangat Kompeten (Nilai: 90 - 100)*  
*( ) Kompeten (Nilai: 75 - 89)*  
*( ) Belum Kompeten (Nilai: < 75)*  

**Tanda Tangan Penguji UKK:**  


_____________________________  
*(Nama Penguji / Asesor Kompetensi)*
