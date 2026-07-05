# SPESIFIKASI KEBUTUHAN PERANGKAT LUNAK
## (Software Requirements Specification)
### Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Spesifikasi Kebutuhan Perangkat Lunak |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Pendahuluan
   1.1. Tujuan Penulisan Dokumen
   1.2. Ruang Lingkup
   1.3. Definisi dan Akronim
2. Deskripsi Umum Sistem
   2.1. Arsitektur Sistem
   2.2. Spesifikasi Perangkat Keras dan Lunak
3. Kebutuhan Fungsional
   3.1. Modul Autentikasi
   3.2. Modul Departemen
   3.3. Modul Pengelolaan Pengguna
   3.4. Modul Pengelolaan Klien
   3.5. Modul Pengelolaan Proyek
   3.6. Modul Tim Proyek
   3.7. Modul Pengelolaan Pekerjaan
   3.8. Modul Pengelolaan Faktur
   3.9. Modul Pengelolaan Portofolio
   3.10. Modul Dasbor
   3.11. Modul Area Kru
   3.12. Modul Portal Klien
   3.13. Modul Laporan
   3.14. Modul Notifikasi
4. Kebutuhan Non-Fungsional
5. Kebutuhan Data
6. Kebutuhan Antarmuka

---

## 1. PENDAHULUAN

### 1.1 Tujuan Penulisan Dokumen

Dokumen Spesifikasi Kebutuhan Perangkat Lunak (SKPL) ini bertujuan untuk mendefinisikan secara rinci kebutuhan fungsional dan non-fungsional dari Sistem Informasi Manajemen _Agency_ Kreatif yang dikembangkan untuk PT Jalur Tengah Kreasindo (Storimax). Dokumen ini menjadi acuan utama dalam tahap perancangan, implementasi, dan pengujian perangkat lunak.

### 1.2 Ruang Lingkup

Sistem Informasi Manajemen _Agency_ Kreatif mencakup pengelolaan pengguna dengan otentikasi multi-peran, manajemen departemen, manajemen klien, manajemen proyek, manajemen tim proyek, manajemen pekerjaan dengan alur status, manajemen faktur dengan kalkulasi otomatis, unduh faktur dalam format PDF, manajemen portofolio, dasbor untuk setiap peran, area kru, portal klien, laporan kinerja, dan notifikasi _in-app_.

### 1.3 Definisi dan Akronim

| Istilah | Definisi |
|---------|----------|
| **SKPL** | Spesifikasi Kebutuhan Perangkat Lunak |
| **Storimax** | _Brand_ dari PT Jalur Tengah Kreasindo |
| **PIC** | _Person In Charge_ — penanggung jawab tim |
| **PPh** | Pajak Penghasilan (PPh Pasal 23 atas jasa) |
| **DP** | _Down Payment_ — uang muka |
| **CRUD** | _Create, Read, Update, Delete_ |
| **UUID** | _Universally Unique Identifier_ |
| **_Soft Delete_** | Penghapusan logis (data tidak dihapus secara permanen) |
| **_RBAC_** | _Role-Based Access Control_ |

---

## 2. DESKRIPSI UMUM SISTEM

### 2.1 Arsitektur Sistem

Sistem dikembangkan menggunakan arsitektur monolitik dengan pola arsitektur _Model-View-Controller_ (MVC) yang disediakan oleh kerangka kerja Laravel. Komunikasi antara pengguna dan sistem dilakukan melalui protokol HTTP dengan peramban web sebagai antarmuka. Basis data MySQL digunakan sebagai media penyimpanan data.

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PERAMBAN WEB                                │
│              Blade + Tailwind CSS v4 + Alpine.js                    │
└───────────────────────────────┬─────────────────────────────────────┘
                                │ HTTP Request
                                ↓
┌─────────────────────────────────────────────────────────────────────┐
│                   LARAVEL 12 (Backend)                               │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐           │
│  │    Route      │  │  Middleware    │  │  Controller   │           │
│  │  (5 berkas)   │  │  (3 berkas)    │  │  (18 berkas)  │           │
│  └───────────────┘  └───────────────┘  └───────────────┘           │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────────┐       │
│  │    Model      │  │  Enum (5)     │  │  View (35+ berka) │       │
│  │  (15 berkas)  │  │              │  │                   │       │
│  └───────────────┘  └───────────────┘  └───────────────────┘       │
└───────────────────────────────┬─────────────────────────────────────┘
                                │ Eloquent ORM
                                ↓
┌─────────────────────────────────────────────────────────────────────┐
│                        MYSQL 8 (Basis Data)                         │
│                15 tabel + 2 tabel tambahan                          │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Spesifikasi Perangkat Keras dan Lunak

#### a) Kebutuhan Perangkat Keras

| Komponen | Spesifikasi Minimal |
|----------|---------------------|
| Prosesor | Intel Core i3 atau setara |
| Memori | 4 GB RAM |
| Penyimpanan | 10 GB ruang kosong |
| Jaringan | Koneksi internet (untuk akses peramban) |

#### b) Kebutuhan Perangkat Lunak

| Komponen | Spesifikasi |
|----------|-------------|
| Sistem Operasi | Windows / Linux / macOS |
| Peramban Web | Chrome 90+ / Firefox 88+ / Edge 90+ |
| Server Web | Apache 2.4 (XAMPP) |
| PHP | 8.2.12 |
| Basis Data | MySQL 8 |
| _Node.js_ | 20.x (untuk Vite) |
| _Composer_ | 2.x |

---

## 3. KEBUTUHAN FUNGSIONAL

### 3.1 Modul Autentikasi (FR-01)

| FR-01-01 | Sistem menyediakan halaman _login_ dengan formulir surel dan kata sandi |
| FR-01-02 | Sistem memvalidasi kredensial menggunakan algoritma bcrypt |
| FR-01-03 | Sistem mengarahkan pengguna ke dasbor sesuai peran setelah berhasil _login_ |
| FR-01-04 | Sistem memeriksa status aktif pengguna dan menolak akses jika tidak aktif |
| FR-01-05 | Sistem menyediakan fungsi _logout_ yang membersihkan sesi |

### 3.2 Modul Departemen (FR-02)

| FR-02-01 | Administrator dapat membuat data departemen baru |
| FR-02-02 | Administrator dapat melihat daftar departemen |
| FR-02-03 | Administrator dapat mengubah data departemen |
| FR-02-04 | Administrator dapat menghapus departemen |

### 3.3 Modul Pengelolaan Pengguna (FR-03)

| FR-03-01 | Administrator dapat membuat pengguna baru |
| FR-03-02 | Administrator dapat melihat daftar seluruh pengguna |
| FR-03-03 | Administrator dapat mengubah data pengguna |
| FR-03-04 | Administrator dapat mengaktifkan atau menonaktifkan pengguna |
| FR-03-05 | Sistem menerapkan penghapusan lunak untuk data pengguna |

### 3.4 Modul Pengelolaan Klien (FR-04)

| FR-04-01 | Administrator dapat membuat data klien baru |
| FR-04-02 | Administrator dapat melihat daftar klien |
| FR-04-03 | Administrator dapat mengubah data klien |
| FR-04-04 | Administrator dapat menghapus klien dengan penghapusan lunak |

### 3.5 Modul Pengelolaan Proyek (FR-05)

| FR-05-01 | Administrator dapat membuat proyek baru |
| FR-05-02 | Sistem menghasilkan kode proyek secara otomatis: STX-YYYY-NNN |
| FR-05-03 | Administrator dapat melihat daftar seluruh proyek |
| FR-05-04 | Administrator dapat melihat detail proyek |
| FR-05-05 | Administrator dapat mengubah data proyek |
| FR-05-06 | Administrator dapat menghapus proyek dengan penghapusan lunak |
| FR-05-07 | Administrator dapat mengubah status proyek |

### 3.6 Modul Tim Proyek (FR-06)

| FR-06-01 | Administrator dapat membentuk tim dalam proyek |
| FR-06-02 | Administrator dapat menunjuk PIC per tim |
| FR-06-03 | Administrator dapat menambah atau menghapus anggota tim |
| FR-06-04 | Administrator dapat menghapus tim dari proyek |

### 3.7 Modul Pengelolaan Pekerjaan (FR-07)

| FR-07-01 | Administrator dapat membuat pekerjaan dalam proyek |
| FR-07-02 | Administrator dapat menugaskan pekerjaan kepada kru |
| FR-07-03 | Administrator dapat melihat daftar seluruh pekerjaan |
| FR-07-04 | Administrator dapat mengubah data pekerjaan |
| FR-07-05 | Administrator dapat menghapus pekerjaan dengan penghapusan lunak |
| FR-07-06 | Administrator dapat mengubah status pekerjaan |
| FR-07-07 | Sistem mengisi _started_at_ secara otomatis saat status menjadi _inprogress_ |
| FR-07-08 | Sistem mengisi _completed_at_ secara otomatis saat status menjadi _done_ |
| FR-07-09 | Sistem mencatat setiap perubahan status pada _job_logs_ |

### 3.8 Modul Pengelolaan Faktur (FR-08)

| FR-08-01 | Administrator dapat membuat faktur baru |
| FR-08-02 | Administrator dapat menambahkan beberapa item per faktur |
| FR-08-03 | Sistem menghitung subtotal, PPh, total, DP, dan sisa tagihan secara otomatis |
| FR-08-04 | Sistem menghasilkan nomor faktur secara otomatis: INV/STX/YYYY/NNN |
| FR-08-05 | Administrator dapat melihat daftar seluruh faktur |
| FR-08-06 | Administrator dapat melihat detail faktur |
| FR-08-07 | Administrator dapat mengubah status faktur |
| FR-08-08 | Sistem mencatat _timestamp_ sent_at, dp_paid_at, dan paid_at secara otomatis |
| FR-08-09 | Administrator dapat menghapus faktur dengan penghapusan lunak |
| FR-08-10 | Administrator dapat mengunduh faktur dalam format PDF |
| FR-08-11 | Klien dapat melihat faktur miliknya sendiri |
| FR-08-12 | Sistem menangani karakter "/" pada nama berkas unduhan |

### 3.9 Modul Pengelolaan Portofolio (FR-09)

| FR-09-01 | Administrator dapat membuat portofolio baru |
| FR-09-02 | Administrator dapat mengunggah gambar miniatur portofolio |
| FR-09-03 | Administrator dapat menambahkan tag pada portofolio |
| FR-09-04 | Administrator dapat mengatur visibilitas portofolio (publik/privat) |
| FR-09-05 | Administrator dapat melihat daftar seluruh portofolio |
| FR-09-06 | Klien dapat melihat portofolio yang berstatus publik |

### 3.10 Modul Dasbor (FR-10)

| FR-10-01 | Dasbor administrator menampilkan ringkasan proyek, pekerjaan, dan faktur |
| FR-10-02 | Dasbor kru menampilkan pekerjaan yang ditugaskan dan aktif |
| FR-10-03 | Dasbor klien menampilkan faktur dan proyek miliknya |

### 3.11 Modul Area Kru (FR-11)

| FR-11-01 | Kru dapat melihat daftar pekerjaan yang ditugaskan |
| FR-11-02 | Kru dapat melihat detail pekerjaan |
| FR-11-03 | Kru dapat memperbarui status pekerjaan miliknya |
| FR-11-04 | Kru dapat mengunggah lampiran pada pekerjaan |

### 3.12 Modul Portal Klien (FR-12)

| FR-12-01 | Klien dapat melihat daftar faktur miliknya |
| FR-12-02 | Klien dapat melihat detail faktur |
| FR-12-03 | Klien dapat melihat portofolio yang berstatus publik |

### 3.13 Modul Laporan (FR-13)

| FR-13-01 | Administrator dapat melihat rekap kinerja kru |
| FR-13-02 | Administrator dapat melihat detail kinerja per kru |
| FR-13-03 | Administrator dapat mengekspor laporan ke PDF |
| FR-13-04 | Administrator dapat mengekspor laporan ke CSV |

### 3.14 Modul Notifikasi (FR-14)

| FR-14-01 | Sistem mengirim notifikasi ke kru saat pekerjaan ditugaskan |
| FR-14-02 | Sistem mengirim notifikasi saat status pekerjaan berubah |
| FR-14-03 | Sistem mengirim notifikasi ke klien saat faktur dikirim |
| FR-14-04 | Sistem mengirim notifikasi ke administrator saat faktur dibayar |
| FR-14-05 | Pengguna dapat menandai notifikasi sebagai telah dibaca |
| FR-14-06 | Pengguna dapat menghapus notifikasi |

---

## 4. KEBUTUHAN NON-FUNGSIONAL

### 4.1 Kebutuhan Keamanan (NFR-01)

| NFR-01-01 | Seluruh kata sandi di-hash menggunakan algoritma bcrypt |
| NFR-01-02 | Seluruh _route_ kecuali halaman _login_ dilindungi oleh _middleware_ otentikasi |
| NFR-01-03 | Setiap akses divalidasi peran menggunakan _middleware_ CheckRole |
| NFR-01-04 | Perlindungan CSRF aktif di seluruh formulir |
| NFR-01-05 | Sesi disimpan dalam berkas _(file)_ pada sisi server |

### 4.2 Kebutuhan Performa (NFR-02)

| NFR-02-01 | Halaman harus dimuat dalam waktu kurang dari tiga detik |
| NFR-02-02 | Kueri basis data menggunakan indeks pada kolom yang sering diakses |
| NFR-02-03 | Konfigurasi dan _route_ di-cache untuk performa produksi |

### 4.3 Kebutuhan Keandalan (NFR-03)

| NFR-03-01 | Data penting menerapkan penghapusan lunak |
| NFR-03-02 | Seluruh kunci primer menggunakan UUID |
| NFR-03-03 | Setiap perubahan status pekerjaan tercatat dalam tabel log |

### 4.4 Kebutuhan Kegunaan (NFR-04)

| NFR-04-01 | Antarmuka sistem bersifat responsif |
| NFR-04-02 | Navigasi sistem bersifat konsisten |
| NFR-04-03 | Sistem menyediakan umpan balik visual untuk setiap tindakan |

---

## 5. KEBUTUHAN DATA

### 5.1 Entitas dan Atribut

Sistem menggunakan lima belas entitas utama sebagai berikut:

1. **departments** — menyimpan data departemen
2. **users** — menyimpan data pengguna (terkait departemen)
3. **clients** — menyimpan data klien (terkait pengguna)
4. **projects** — menyimpan data proyek (terkait klien dan pembuat)
5. **project_teams** — menyimpan data tim proyek
6. **project_team_members** — menyimpan data keanggotaan tim
7. **jobs** — menyimpan data pekerjaan
8. **job_logs** — menyimpan catatan perubahan status pekerjaan
9. **job_attachments** — menyimpan data lampiran pekerjaan
10. **invoices** — menyimpan data faktur
11. **invoice_items** — menyimpan data item faktur
12. **portfolios** — menyimpan data portofolio
13. **portfolio_tags** — menyimpan data tag portofolio
14. **notifications** — menyimpan data notifikasi
15. **activity_logs** — menyimpan data aktivitas pengguna

### 5.2 Tipe Data Enum

| Entitas | Nilai Enum |
|---------|------------|
| **User Role** | admin, atasan, crew, client |
| **Project Status** | draft, active, review, done, archived |
| **Job Status** | todo, inprogress, review, done |
| **Invoice Status** | draft, sent, dp_paid, paid, overdue |
| **Job Priority** | low, medium, high, urgent |

---

## 6. KEBUTUHAN ANTARMUKA

### 6.1 Struktur Tampilan

Sistem memiliki tiga jenis tata letak utama:

1. **Tata Letak Otentikasi:** Tampilan penuh dengan pemusatan di tengah layar, digunakan untuk halaman _login_.
2. **Tata Letak Aplikasi:** Tampilan dengan panel samping _(sidebar)_ di kiri, bilah atas _(topbar)_ di kanan atas, dan area konten di tengah. Digunakan untuk seluruh halaman setelah _login_.
3. **Tata Letak Cetak:** Tampilan khusus untuk dokumen PDF faktur dengan format dua kolom.

### 6.2 Tema Visual

Sistem menggunakan tema visual minimalis dan bersih dengan warna netral (abu-abu/putih) dan aksen biru (#2563eb). Tipografi menggunakan keluarga huruf Inter yang disediakan oleh Tailwind CSS.

---

## DAFTAR PUSTAKA

1. Basuki, A. (2021). _Analisis dan Perancangan Sistem Informasi Berbasis Web_. Jurnal Sistem Informasi, 8(1), 22–35.
2. Fowler, M. (2002). _Patterns of Enterprise Application Architecture_. Addison-Wesley.
3. IEEE. (1998). _IEEE Recommended Practice for Software Requirements Specifications_ (IEEE Std 830-1998). IEEE.
4. Kendall, K. E., & Kendall, J. E. (2014). _Systems Analysis and Design_ (9th ed.). Pearson.
5. Nugroho, B. (2019). _Pengembangan Aplikasi Web dengan Framework Laravel_. Penerbit Andi.

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
