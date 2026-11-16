# Desain Database & ERD Aplikasi Parkir

Di bawah ini adalah struktur Entity Relationship Diagram (ERD) yang direpresentasikan menggunakan sintaks diagram Mermaid, sesuai dengan skema yang ada pada dokumen soal.

## 1. Diagram ERD

```mermaid
erDiagram
    tb_user ||--o{ tb_transaksi : "melayani"
    tb_user ||--o{ tb_log_aktivitas : "melakukan"
    tb_area_parkir ||--o{ tb_transaksi : "ditempati"
    tb_tarif ||--o{ tb_transaksi : "dikenakan"
    tb_kendaraan ||--o{ tb_transaksi : "melakukan"
    tb_user ||--o{ tb_kendaraan : "didaftarkan oleh"

    tb_user {
        int id_user PK
        varchar(50) nama_lengkap
        varchar(50) username
        varchar(100) password
        enum role "'admin', 'petugas', 'owner'"
        tinyint(1) status_aktif
    }

    tb_area_parkir {
        int id_area PK
        varchar(50) nama_area
        int(5) kapasitas
        int(5) terisi
    }

    tb_tarif {
        int id_tarif PK
        enum jenis_kendaraan "'motor', 'mobil', 'lainnya'"
        decimal(10_0) tarif_per_jam
    }

    tb_kendaraan {
        int id_kendaraan PK
        varchar(15) plat_nomor
        varchar(20) jenis_kendaraan
        varchar(20) warna
        varchar(100) pemilik
        int id_user FK
    }

    tb_transaksi {
        int id_parkir PK
        int id_kendaraan FK
        datetime waktu_masuk
        datetime waktu_keluar
        int id_tarif FK
        int(5) durasi_jam
        decimal(10_0) biaya_total
        enum status "'masuk', 'keluar'"
        int id_user FK
        int id_area FK
    }

    tb_log_aktivitas {
        int id_log PK
        int id_user FK
        varchar(100) aktivitas
        datetime waktu_aktivitas
    }
```

## 2. Rincian Kamus Data (Data Dictionary)

Berdasarkan skema pada soal, berikut rincian tabelnya:

1. **`tb_user`** (Menyimpan data pengguna)
   - `id_user`: Primary Key, INT(11)
   - `nama_lengkap`: VARCHAR(50)
   - `username`: VARCHAR(50)
   - `password`: VARCHAR(100)
   - `role`: ENUM ('admin', 'petugas', 'owner')
   - `status_aktif`: TINYINT(1)

2. **`tb_area_parkir`** (Menyimpan area/lokasi parkir beserta kapasitasnya)
   - `id_area`: Primary Key, INT(11)
   - `nama_area`: VARCHAR(50)
   - `kapasitas`: INT(5)
   - `terisi`: INT(5)

3. **`tb_tarif`** (Menyimpan master tarif per jam berdasarkan kendaraan)
   - `id_tarif`: Primary Key, INT(11)
   - `jenis_kendaraan`: ENUM ('motor', 'mobil', 'lainnya')
   - `tarif_per_jam`: DECIMAL(10,0)

4. **`tb_kendaraan`** (Menyimpan master data kendaraan, bisa jadi opsional jika plat langsung diinput di transaksi, namun sesuai relasi tabel ini eksis)
   - `id_kendaraan`: Primary Key, INT(11)
   - `plat_nomor`: VARCHAR(15)
   - `jenis_kendaraan`: VARCHAR(20)
   - `warna`: VARCHAR(20)
   - `pemilik`: VARCHAR(100)
   - `id_user`: Foreign Key, INT(11)

5. **`tb_transaksi`** (Tabel operasional untuk pencatatan parkir)
   - `id_parkir`: Primary Key, INT(11)
   - `id_kendaraan`: Foreign Key, INT(11)
   - `waktu_masuk`: DATETIME
   - `waktu_keluar`: DATETIME
   - `id_tarif`: Foreign Key, INT(11)
   - `durasi_jam`: INT(5)
   - `biaya_total`: DECIMAL(10,0)
   - `status`: ENUM ('masuk', 'keluar')
   - `id_user`: Foreign Key, INT(11) (Mencatat petugas siapa yang melayani)
   - `id_area`: Foreign Key, INT(11)

6. **`tb_log_aktivitas`** (Menyimpan log audit/aktivitas admin)
   - `id_log`: Primary Key, INT(11)
   - `id_user`: Foreign Key, INT(11)
   - `aktivitas`: VARCHAR(100)
   - `waktu_aktivitas`: DATETIME