# METODOLOGI PENELITIAN
## Metode Waterfall — Sistem Informasi Manajemen Agency Kreatif
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Metodologi Penelitian |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Metode** | _Waterfall_ (Model Sekuensial Linear) |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Pendahuluan
   1.1. Landasan Teori Metode Waterfall
   1.2. Alasan Pemilihan Metode
2. Tahapan Penelitian
   2.1. Analisis Kebutuhan
   2.2. Desain Sistem
   2.3. Implementasi
   2.4. Pengujian
   2.5. Deployment
   2.6. Pemeliharaan
3. Alat dan Bahan Penelitian
4. Jadwal Penelitian

---

## 1. PENDAHULUAN

### 1.1 Landasan Teori Metode Waterfall

Metode _Waterfall_ merupakan salah satu model pengembangan perangkat lunak yang termasuk dalam kategori _Software Development Life Cycle_ (SDLC). Model ini pertama kali diperkenalkan oleh Winston W. Royce pada tahun 1970 dan kemudian dikembangkan lebih lanjut oleh berbagai ahli rekayasa perangkat lunak. _Waterfall_ dinamakan demikian karena pendekatannya yang sekuensial dan linear, di mana setiap tahapan harus diselesaikan sepenuhnya sebelum melanjutkan ke tahapan berikutnya, menyerupai aliran air terjun (Pressman, 2015).

Menurut Sommerville (2016), model _Waterfall_ memiliki lima tahapan utama, yaitu:
1. Analisis dan definisi kebutuhan
2. Desain sistem dan perangkat lunak
3. Implementasi dan pengujian unit
4. Integrasi dan pengujian sistem
5. Operasi dan pemeliharaan

Model ini sangat cocok diterapkan pada proyek dengan kebutuhan yang telah terdefinisi dengan jelas dan stabil, serta memiliki ruang lingkup yang terbatas (Pressman, 2015).

### 1.2 Alasan Pemilihan Metode

Pemilihan metode _Waterfall_ dalam penelitian ini didasarkan pada beberapa pertimbangan sebagai berikut:

| Faktor | Penjelasan |
|--------|------------|
| **Kebutuhan yang jelas** | Seluruh modul sistem telah terdefinisi secara lengkap sejak awal penelitian, mencakup tigabelas modul fungsional. |
| **Skala proyek** | Proyek berskala kecil hingga menengah yang dikerjakan oleh satu orang peneliti dengan satu pemangku kepentingan. |
| **Minimal perubahan** | Tidak terdapat ekspektasi perubahan kebutuhan yang signifikan di tengah proses pengembangan. |
| **Dokumentasi lengkap** | Setiap tahapan menghasilkan dokumentasi formal yang dapat dijadikan acuan dan bahan evaluasi. |
| **Target waktu** | Batas waktu penyelesaian proyek telah ditentukan dan dapat diprediksi sejak awal. |

---

## 2. TAHAPAN PENELITIAN

Penelitian ini dilaksanakan dalam enam tahapan yang mengacu pada model _Waterfall_ yang dimodifikasi sesuai kebutuhan proyek.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ 1. ANALISIS KEBUTUHAN (Requirements Analysis)                              │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Mengidentifikasi kebutuhan pengguna, menganalisis proses bisnis, │    │
│    │  menyusun PRD dan SRS                                             │    │
│    └───────────────────────────────────────────────────────────────────┘    │
│                                    ↓                                        │
│ 2. DESAIN SISTEM (System Design)                                           │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Merancang basis data, struktur route, arsitektur kode, dan       │    │
│    │  antarmuka pengguna                                               │    │
│    └───────────────────────────────────────────────────────────────────┘    │
│                                    ↓                                        │
│ 3. IMPLEMENTASI (Implementation)                                           │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Menulis kode program: skeleton (setup Laravel, auth, layout)     │    │
│    │  dilanjutkan dengan pengembangan 13 modul fungsional              │    │
│    └───────────────────────────────────────────────────────────────────┘    │
│                                    ↓                                        │
│ 4. PENGUJIAN (Testing)                                                     │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Melakukan pengujian unit (42 kasus uji), pengujian manual,       │    │
│    │  dan verifikasi fungsionalitas sistem secara menyeluruh           │    │
│    └───────────────────────────────────────────────────────────────────┘    │
│                                    ↓                                        │
│ 5. DEPLOYMENT (Deployment)                                                 │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Menyebarkan sistem pada lingkungan produksi (XAMPP),             │    │
│    │  melakukan optimasi cache, dan mengisi data awal                  │    │
│    └───────────────────────────────────────────────────────────────────┘    │
│                                    ↓                                        │
│ 6. PEMELIHARAAN (Maintenance)                                              │
│    ┌───────────────────────────────────────────────────────────────────┐    │
│    │  Melakukan perbaikan bug (PDF GD fix), optimasi performa,         │    │
│    │  dan pemutakhiran dokumentasi sistem                             │    │
│    └───────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.1 Analisis Kebutuhan

Tahap analisis kebutuhan bertujuan untuk mengidentifikasi dan mendokumentasikan kebutuhan pengguna serta kebutuhan sistem secara lengkap. Kegiatan yang dilakukan pada tahap ini meliputi:

1. **Identifikasi pemangku kepentingan** — Mengidentifikasi empat kategori pengguna: administrator, atasan, kru, dan klien.
2. **Analisis proses bisnis** — Menganalisis alur kerja manajemen proyek, penelusuran pekerjaan, dan pembuatan faktur yang sedang berjalan di perusahaan.
3. **Identifikasi kebutuhan fungsional** — Mendefinisikan tigabelas modul fungsional yang diperlukan.
4. **Identifikasi kebutuhan non-fungsional** — Mendefinisikan kebutuhan keamanan, performa, keandalan, dan kegunaan.
5. **Prioritas fitur** — Mengelompokkan fitur ke dalam tiga kategori prioritas: P1 (wajib), P2 (penting), dan P3 (tambahan).

**Luaran (Output) Tahap Analisis Kebutuhan:**
- Dokumen Analisis Kebutuhan Produk (PRD)
- Dokumen Spesifikasi Kebutuhan Perangkat Lunak (SRS)
- Daftar modul fungsional
- Spesifikasi peran pengguna

### 2.2 Desain Sistem

Tahap desain sistem bertujuan untuk merancang arsitektur sistem, basis data, dan antarmuka pengguna berdasarkan kebutuhan yang telah didefinisikan sebelumnya. Kegiatan yang dilakukan meliputi:

1. **Desain basis data** — Merancang struktur tabel, relasi antar tabel, tipe data, dan indeks untuk lima belas tabel utama dan dua tabel tambahan.
2. **Desain _route_** — Merancang struktur URL dan pembagian _route_ ke dalam lima berkas: `web.php`, `auth.php`, `admin.php`, `crew.php`, dan `client.php`.
3. **Desain tata letak** — Merancang tata letak otentikasi (halaman _login_) dan tata letak aplikasi (panel samping, bilah atas, area konten).
4. **Desain tema visual** — Menentukan skema warna, tipografi, dan gaya antarmuka minimalis.
5. **Desain otorisasi** — Merancang sistem kendali akses berbasis peran menggunakan _middleware_ CheckRole.

**Luaran (Output) Tahap Desain:**
- Diagram basis data (15 tabel)
- Struktur _route_
- Desain antarmuka (_wireframe_)

### 2.3 Implementasi

Tahap implementasi merupakan tahap penulisan kode program berdasarkan desain yang telah dirancang. Implementasi dibagi menjadi dua sub-tahap:

#### a) _Skeleton_ (Kerangka Dasar)
Sub-tahap ini mencakup:
- Setup kerangka kerja Laravel 12
- Konfigurasi basis data MySQL
- Implementasi sistem otentikasi (login, logout, redirect)
- Pembuatan tata letak aplikasi (panel samping, bilah atas)
- Pembuatan tata letak otentikasi

#### b) Pengembangan Modul
Sub-tahap ini mencakup pengembangan tigabelas modul fungsional:

| No | Modul | Status |
|:--:|-------|:------:|
| 1 | Autentikasi | ✓ Selesai |
| 2 | Departemen | ✓ Selesai |
| 3 | Pengelolaan Pengguna | ✓ Selesai |
| 4 | Pengelolaan Klien | ✓ Selesai |
| 5 | Pengelolaan Proyek | ✓ Selesai |
| 6 | Tim Proyek | ✓ Selesai |
| 7 | Pengelolaan Pekerjaan | ✓ Selesai |
| 8 | Pengelolaan Faktur | ✓ Selesai |
| 9 | Pengelolaan Portofolio | ✓ Selesai |
| 10 | Dasbor | ✓ Selesai |
| 11 | Area Kru | ✓ Selesai |
| 12 | Portal Klien | ✓ Selesai |
| 13 | Laporan & Notifikasi | ✓ Selesai |

**Luaran (Output) Tahap Implementasi:**
- Kode sumber sistem lengkap
- 18 berkas pengendali (_controller_)
- 15 berkas model
- 35+ berkas tampilan (_view_)
- 5 berkas _enum_

### 2.4 Pengujian

Tahap pengujian bertujuan untuk memverifikasi bahwa sistem berfungsi sesuai dengan spesifikasi yang telah ditetapkan. Pengujian dilakukan dalam dua kategori:

#### a) Pengujian Unit (Unit Testing)
Pengujian unit dilakukan menggunakan kerangka kerja PHPUnit dengan pendekatan sebagai berikut:
- Setiap kasus uji menggunakan _trait_ `RefreshDatabase` untuk mereset basis data sebelum setiap pengujian.
- Setiap permintaan mutasi (POST, PUT, PATCH, DELETE) menggunakan _helper_ `withCsrf()` untuk menyertakan token CSRF tanpa menonaktifkan _middleware_.

#### b) Pengujian Manual
Pengujian manual dilakukan dengan mengakses sistem melalui peramban web untuk memverifikasi:
- Fungsi _login_ dan _logout_ untuk setiap peran
- Operasi CRUD pada setiap modul
- Alur status pekerjaan dan faktur
- Unduh faktur PDF
- Otorisasi peran

**Luaran (Output) Tahap Pengujian:**
- 42 kasus uji unit (ProjectControllerTest 11, InvoiceControllerTest 14, JobControllerTest 17)
- Laporan hasil pengujian
- Dokumentasi _known issues_ dan solusi

### 2.5 Deployment (Penyebaran)

Tahap _deployment_ bertujuan untuk menyebarkan sistem pada lingkungan produksi. Kegiatan yang dilakukan meliputi:

1. **Persiapan lingkungan** — Mengonfigurasi server XAMPP dengan PHP 8.2 dan MySQL 8.
2. **Migrasi basis data** — Menjalankan `php artisan migrate:fresh --seed` untuk membuat struktur tabel dan mengisi data awal.
3. **Optimasi** — Menjalankan `php artisan config:cache` dan `php artisan route:cache` untuk meningkatkan performa.
4. **Konfigurasi PHP** — Mengaktifkan ekstensi GD pada `php.ini` untuk mendukung fungsi grafis.

**Luaran (Output) Tahap Deployment:**
- Sistem siap diakses pada lingkungan produksi
- Data dummy untuk demonstrasi

### 2.6 Pemeliharaan (Maintenance)

Tahap pemeliharaan bertujuan untuk melakukan perbaikan dan peningkatan sistem berdasarkan temuan selama pengujian dan penggunaan. Kegiatan yang dilakukan meliputi:

1. **Perbaikan PDF GD** — Mengganti metode pemuatan logo faktur dari berkas fisik menjadi _base64 data URI_ untuk mengatasi ketergantungan pada ekstensi GD.
2. **Stabilitas sesi** — Mengonfigurasi _driver_ sesi menjadi berkas _(file)_ untuk menghindari masalah kompatibilitas dengan MySQL.
3. **Pemutakhiran dokumentasi** — Memperbarui dokumentasi sistem sesuai dengan perubahan yang dilakukan.

---

## 3. ALAT DAN BAHAN PENELITIAN

### 3.1 Perangkat Lunak

| Perangkat Lunak | Kegunaan | Versi |
|-----------------|----------|-------|
| Laravel | Kerangka kerja pengembangan _backend_ | 12 |
| Visual Studio Code | Editor kode | Terbaru |
| XAMPP | Server lokal (Apache + MySQL + PHP) | 8.2.12 |
| MySQL | Sistem manajemen basis data | 8 |
| Composer | Manajer dependensi PHP | 2.x |
| Node.js & NPM | Manajer paket JavaScript | 20.x / 10.x |
| Vite | Pembundel aset (_asset bundler_) | 7 |
| Git | Sistem kendali versi | Terbaru |
| PHPUnit | Kerangka kerja pengujian | 11.5.50 |
| DOMPDF | Pustaka pembuatan PDF | 3.1 (barryvdh/laravel-dompdf) |

### 3.2 Perangkat Keras

| Komponen | Spesifikasi |
|----------|-------------|
| Prosesor | Intel Core i5 |
| Memori | 8 GB RAM |
| Penyimpanan | SSD 256 GB |

---

## 4. JADWAL PENELITIAN

| Tahapan | Durasi | Kegiatan |
|---------|:------:|----------|
| Analisis Kebutuhan | 3 hari | Identifikasi kebutuhan, penyusunan PRD & SRS |
| Desain Sistem | 3 hari | Perancangan basis data, _route_, antarmuka |
| Implementasi (Skeleton) | 3 hari | Setup Laravel, auth, layout |
| Implementasi (Modul) | 7 hari | Pengembangan 13 modul fungsional |
| Pengujian | 3 hari | Unit test, manual test, perbaikan bug |
| Deployment | 1 hari | Penyebaran sistem, optimasi |
| Pemeliharaan | 2 hari (±1) | Perbaikan PDF, stabilitas, dokumentasi |

---

## DAFTAR PUSTAKA

1. Pressman, R. S. (2015). _Software Engineering: A Practitioner's Approach_ (8th ed.). McGraw-Hill Education.
2. Royce, W. W. (1970). Managing the Development of Large Software Systems. _Proceedings of IEEE WESCON_, 1–9.
3. Sommerville, I. (2016). _Software Engineering_ (10th ed.). Pearson Education.
4. Sukamto, R. A., & Shalahuddin, M. (2018). _Rekayasa Perangkat Lunak: Terstruktur dan Berorientasi Objek_. Penerbit Informatika.
5. Wulandari, D. (2021). Penerapan Metode Waterfall dalam Pengembangan Sistem Informasi Manajemen. _Jurnal Teknologi Informasi_, 15(2), 78–89.

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
