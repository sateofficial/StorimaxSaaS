# ANALISIS KEBUTUHAN PRODUK
## (Product Requirements Document)
### Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Analisis Kebutuhan Produk |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## ABSTRAK

_Penelitian ini bertujuan untuk merancang dan membangun Sistem Informasi Manajemen Agency Kreatif pada PT Jalur Tengah Kreasindo (Storimax). Permasalahan utama yang dihadapi oleh perusahaan adalah pengelolaan proyek, penugasan pekerjaan _(job assignment)_, pembuatan faktur _(invoicing)_, serta komunikasi antara administrator, kru, dan klien yang masih dilakukan secara manual melalui pencatatan fisik, pesan instan, dan spreadsheet. Penelitian ini menggunakan metode _Waterfall_ yang terdiri atas enam tahapan, yaitu analisis kebutuhan, desain sistem, implementasi, pengujian, penyebaran, dan pemeliharaan. Hasil penelitian berupa sistem berbasis web dengan tigabelas modul fungsional yang mencakup autentikasi, manajemen proyek, manajemen pekerjaan, faktur digital, portal klien, dan sistem pelaporan. Sistem berhasil diuji melalui 42 kasus uji unit dengan tingkat keberhasilan 100%._

**Kata Kunci:** sistem informasi manajemen, agency kreatif, Laravel, waterfall, invoice digital

---

## DAFTAR ISI

1. Pendahuluan
   1.1. Latar Belakang
   1.2. Rumusan Masalah
   1.3. Tujuan Penelitian
   1.4. Manfaat Penelitian
   1.5. Batasan Penelitian
2. Gambaran Umum Produk
   2.1. Pengguna Produk
   2.2. Kebutuhan Fungsional
   2.3. Kebutuhan Non-Fungsional
   2.4. Prioritas Fitur
3. Alur Kerja Sistem
4. Spesifikasi Teknis
5. Metrik Keberhasilan

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang

Perkembangan teknologi informasi telah membawa perubahan signifikan dalam pengelolaan bisnis di berbagai sektor industri. Sektor industri kreatif, khususnya _agency_ yang bergerak di bidang fotografi, videografi, dan _branding_, memerlukan sistem informasi yang terintegrasi untuk mengelola seluruh siklus kerja — mulai dari penerimaan proyek, penugasan kru, pemantauan kemajuan pekerjaan, hingga proses penagihan dan pembayaran.

PT Jalur Tengah Kreasindo, yang dikenal dengan _brand_ Storimax, merupakan perusahaan yang bergerak di bidang jasa kreatif dengan tagline _"Story in Motion. Maxed to Perfection."_ Sebagai _agency_ yang terus berkembang, perusahaan menghadapi sejumlah permasalahan dalam pengelolaan operasional, antara lain:

1. **Inefisiensi pencatatan** — data proyek dan klien tersebar di berbagai media (pesan instan, _spreadsheet_, catatan fisik) yang menyulitkan pelacakan.
2. **Kurangnya transparansi** — klien tidak memiliki akses untuk memantau perkembangan proyek secara langsung.
3. **Kesulitan pemantauan** — administrator mengalami kesulitan dalam memonitor kemajuan pekerjaan setiap kru secara _real-time_.
4. **Rentan kesalahan manusia** — kalkulasi faktur masih dilakukan secara manual yang berpotensi menimbulkan kesalahan perhitungan.

Berdasarkan permasalahan tersebut, penelitian ini mengusulkan pembangunan Sistem Informasi Manajemen _Agency_ Kreatif berbasis web yang dapat mengintegrasikan seluruh proses bisnis perusahaan dalam satu platform terpadu.

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah sebagai berikut:

1. Bagaimana merancang dan membangun Sistem Informasi Manajemen _Agency_ Kreatif yang dapat mengintegrasikan pengelolaan proyek, penugasan pekerjaan, faktur digital, dan komunikasi antar pemangku kepentingan?
2. Bagaimana menerapkan metode _Waterfall_ dalam pengembangan sistem informasi manajemen _agency_ kreatif?
3. Bagaimana mengimplementasikan sistem berbasis peran _(role-based access control)_ yang membedakan akses antara administrator, atasan, kru, dan klien?

### 1.3 Tujuan Penelitian

Tujuan dari penelitian ini adalah sebagai berikut:

1. Menghasilkan Sistem Informasi Manajemen _Agency_ Kreatif yang mencakup tigabelas modul fungsional untuk mendukung operasional PT Jalur Tengah Kreasindo.
2. Mendigitalisasi pencatatan dan pengelolaan proyek, pekerjaan, faktur, serta portofolio dalam satu sistem terpusat.
3. Menyediakan mekanisme otentikasi dan otorisasi berbasis peran untuk mengakomodasi empat jenis pengguna, yaitu administrator, atasan, kru, dan klien.
4. Menyediakan sistem pelaporan yang dapat digunakan oleh manajemen untuk memantau kinerja kru dan proyek.

### 1.4 Manfaat Penelitian

Manfaat yang diharapkan dari penelitian ini adalah:

1. **Bagi Perusahaan:** Meningkatkan efisiensi operasional, akurasi kalkulasi faktur, dan transparansi informasi kepada klien.
2. **Bagi Kru:** Mempermudah akses terhadap daftar pekerjaan yang ditugaskan serta pencatatan kemajuan pekerjaan.
3. **Bagi Klien:** Memberikan akses untuk melihat faktur dan portofolio secara _online_.
4. **Bagi Pengembangan Ilmu:** Menjadi referensi bagi penelitian serupa di bidang pengembangan sistem informasi manajemen jasa kreatif.

### 1.5 Batasan Penelitian

Penelitian ini memiliki batasan-batasan sebagai berikut:

1. **Platform:** Sistem dikembangkan sebagai aplikasi web _(web-based application)_ menggunakan kerangka kerja Laravel 12.
2. **Lingkup Perusahaan:** Sistem hanya diperuntukkan bagi PT Jalur Tengah Kreasindo dan belum mendukung multi-perusahaan.
3. **Pembayaran:** Sistem belum terintegrasi dengan _payment gateway_; verifikasi pembayaran masih dilakukan secara manual.
4. **Notifikasi:** Notifikasi yang tersedia terbatas pada notifikasi _in-app_ dan belum menjangkau notifikasi melalui surel maupun pesan singkat.
5. **Akses:** Sistem hanya dapat diakses melalui peramban web dan belum tersedia dalam bentuk aplikasi bergerak _(mobile app)_.
6. **Lingkungan Pengembangan:** Sistem berjalan pada lingkungan XAMPP dengan basis data MySQL.

---

## 2. GAMBARAN UMUM PRODUK

### 2.1 Pengguna Produk

Sistem ini dirancang untuk melayani empat kategori pengguna dengan hak akses yang berbeda:

| Peran | Deskripsi | Jumlah (Estimasi) |
|-------|-----------|------------------|
| **Administrator** | Operator harian yang mengelola seluruh data, termasuk proyek, pekerjaan, kru, faktur, dan portofolio | 2–5 orang |
| **Atasan** | Pimpinan atau direktur yang memantau laporan dan kinerja secara _read-only_ | 1–3 orang |
| **Kru** | Fotografer, videografer, dan _editor_ yang mengerjakan pekerjaan sesuai penugasan | 5–20 orang |
| **Klien** | Pelanggan _agency_ yang dapat melihat faktur dan portofolio | 10–50+ orang |

### 2.2 Kebutuhan Fungsional

Kebutuhan fungsional sistem didefinisikan dalam tigabelas modul utama sebagai berikut:

#### a) Modul Autentikasi (FR-01)
Sistem menyediakan mekanisme _login_ dengan kredensial surel dan kata sandi, validasi menggunakan bcrypt, pengalihan halaman sesuai peran pengguna, serta fungsi _logout_.

#### b) Modul Departemen (FR-02)
Administrator dapat melakukan CRUD _(Create, Read, Update, Delete)_ pada data departemen seperti Foto, Video, Editing, dan Manajemen.

#### c) Modul Pengelolaan Pengguna (FR-03)
Administrator dapat mengelola data pengguna, mengaktifkan atau menonaktifkan akun, serta menetapkan peran dan departemen.

#### d) Modul Pengelolaan Klien (FR-04)
Administrator dapat mengelola data klien yang mencakup nama perusahaan, kontak person, nomor telepon, dan alamat.

#### e) Modul Pengelolaan Proyek (FR-05)
Administrator dapat mengelola proyek dengan sistem kode otomatis berformat STX-YYYY-NNN serta alur status: _draft_, _active_, _review_, _done_, dan _archived_.

#### f) Modul Tim Proyek (FR-06)
Administrator dapat membentuk tim dalam proyek, menunjuk penanggung jawab _(Person In Charge)_ per tim, serta mengelola keanggotaan tim.

#### g) Modul Pengelolaan Pekerjaan (FR-07)
Administrator dapat mengelola pekerjaan dalam proyek, menugaskan pekerjaan kepada kru, serta memantau alur status: _todo_, _inprogress_, _review_, dan _done_.

#### h) Modul Pengelolaan Faktur (FR-08)
Administrator dapat membuat faktur dengan kalkulasi otomatis untuk Pajak Penghasilan (PPh), uang muka _(down payment)_, dan total tagihan, serta mengunduh faktur dalam format PDF.

#### i) Modul Pengelolaan Portofolio (FR-09)
Administrator dapat mengelola portofolio perusahaan, mengunggah gambar miniatur _(thumbnail)_, menambahkan tag, serta mengatur visibilitas publik atau privat.

#### j) Modul Dasbor (FR-10)
Sistem menyediakan dasbor yang menampilkan ringkasan data sesuai dengan peran pengguna masing-masing.

#### k) Modul Area Kru (FR-11)
Kru dapat melihat daftar pekerjaan yang ditugaskan, memperbarui status pekerjaan, dan mengunggah lampiran.

#### l) Modul Portal Klien (FR-12)
Klien dapat melihat faktur dan portofolio yang tersedia untuk akun mereka.

#### m) Modul Laporan (FR-13)
Administrator dapat melihat rekap kinerja kru, detail kinerja per kru, serta mengekspor laporan dalam format PDF dan CSV.

#### n) Modul Notifikasi (FR-14)
Sistem mengirimkan notifikasi _in-app_ kepada pengguna terkait peristiwa tertentu, seperti penugasan pekerjaan baru, perubahan status pekerjaan, dan penerbitan faktur.

### 2.3 Kebutuhan Non-Fungsional

Kebutuhan non-fungsional sistem meliputi:

1. **Keamanan:** Seluruh kata sandi di-hash menggunakan bcrypt, setiap akses ke halaman yang memerlukan otentikasi dilindungi oleh _middleware_, otorisasi peran menggunakan _middleware_ CheckRole, dan perlindungan CSRF aktif pada seluruh formulir.
2. **Performa:** Halaman sistem harus dimuat dalam waktu kurang dari tiga detik, dan kueri basis data harus menggunakan indeks yang sesuai.
3. **Keandalan:** Seluruh data penting menggunakan UUID sebagai kunci primer _(primary key)_ dan menerapkan penghapusan lunak _(soft delete)_ untuk mencegah kehilangan data permanen.
4. **Kegunaan:** Antarmuka sistem dibangun menggunakan Tailwind CSS yang responsif, dengan navigasi yang konsisten melalui panel samping _(sidebar)_ dan bilah atas _(topbar)_, serta memberikan umpan balik visual melalui pesan kilas _(flash message)_.

### 2.4 Prioritas Fitur

| Prioritas | Keterangan | Jumlah Modul |
|-----------|------------|:------------:|
| **P1 (Wajib)** | Fitur inti yang harus tersedia untuk operasional dasar | 10 modul |
| **P2 (Penting)** | Fitur pendukung yang meningkatkan fungsionalitas sistem | 3 modul |
| **P3 (Tambahan)** | Fitur yang dapat dikembangkan pada tahap selanjutnya | 1 modul |

---

## 3. ALUR KERJA SISTEM

### 3.1 Alur Manajemen Proyek

Alur kerja utama sistem dimulai dari pendaftaran klien, pembuatan proyek, penugasan pekerjaan, pemantauan kemajuan, hingga penerbitan faktur dan pelunasan pembayaran.

```
Klien Mendaftar → Administrator Membuat Data Klien
    → Administrator Membuat Proyek (DRAFT)
        → Administrator Menambahkan Tim & PIC
            → Administrator Membuat Pekerjaan & Menugaskan ke Kru
                → Kru Memperbarui Status Pekerjaan (TODO → INPROGRESS → REVIEW → DONE)
                    → Administrator Membuat Faktur (DRAFT)
                        → Faktur Dikirim ke Klien (SENT)
                            → Klien Membayar Uang Muka (DP_PAID)
                                → Pelunasan (PAID)
```

### 3.2 Alur Status Pekerjaan

```
TODO ──→ INPROGRESS ──→ REVIEW ──→ DONE
```

Setiap perubahan status pekerjaan dicatat dalam tabel _job_logs_ sebagai jejak audit _(audit trail)_. Status _started_at_ diisi secara otomatis ketika pekerjaan memasuki status _inprogress_, dan _completed_at_ diisi secara otomatis ketika pekerjaan mencapai status _done_.

### 3.3 Alur Status Faktur

```
DRAFT ──→ SENT ──→ DP_PAID ──→ PAID
              │          │
              └── OVERDUE┘
```

Saat faktur dikirim, _timestamp sent_at_ dicatat. Saat uang muka dibayarkan, _timestamp dp_paid_at_ dicatat dan nilai _dp_paid_ diisi sebesar _dp_amount_. Saat faktur lunas, _timestamp paid_at_ dicatat dan nilai _dp_paid_ diisi sebesar nilai total faktur.

---

## 4. SPESIFIKASI TEKNIS

### 4.1 Tumpukan Teknologi

| Lapisan | Teknologi | Versi |
|---------|-----------|-------|
| Kerangka Kerja _Backend_ | Laravel | 12 (PHP 8.2.12) |
| Kerangka Kerja _Frontend_ | Blade + Tailwind CSS | v4 |
| Interaktivitas | Alpine.js | v3 |
| Basis Data | MySQL | 8 |
| Pembundel _(Bundler)_ | Vite | v7 |
| Mesin PDF | barryvdh/laravel-dompdf | ^3.1 |
| Pengujian | PHPUnit | ^11.5.50 |

### 4.2 Struktur Basis Data

Sistem menggunakan lima belas tabel utama dan dua tabel tambahan:

- **15 Tabel Utama:** departments, users, clients, projects, project_teams, project_team_members, jobs, job_logs, job_attachments, invoices, invoice_items, portfolios, portfolio_tags, notifications, activity_logs.
- **2 Tabel Tambahan:** sessions, cache.

Seluruh tabel menggunakan UUID sebagai kunci primer untuk meningkatkan keamanan dan mencegah penebakan urutan data.

---

## 5. METRIK KEBERHASILAN

| Metrik | Target | Hasil |
|--------|:-----:|:-----:|
| Seluruh CRUD modul berfungsi | 100% | ✅ |
| Alur status pekerjaan dan faktur berjalan sesuai | Terverifikasi | ✅ |
| Kalkulasi faktur (PPh, DP, total) akurat | Terverifikasi | ✅ |
| Otorisasi peran berfungsi | Terverifikasi | ✅ |
| Faktur PDF dapat diunduh | Berfungsi | ✅ |
| Unit _test coverage_ (3 pengendali utama) | 42 kasus | ✅ 42/42 lulus |

---

## DAFTAR PUSTAKA

1. Basori, A. (2020). _Pengembangan Sistem Informasi Berbasis Web dengan Metode Waterfall_. Jurnal Teknik Informatika, 12(2), 45–56.
2. Pressman, R. S. (2015). _Software Engineering: A Practitioner's Approach_ (8th ed.). McGraw-Hill Education.
3. Stauffer, M. (2023). _Laravel: Up & Running: A Framework for Building Modern PHP Apps_ (3rd ed.). O'Reilly Media.
4. Sommerville, I. (2016). _Software Engineering_ (10th ed.). Pearson Education.
5. Wicaksono, Y. (2022). _Rekayasa Perangkat Lunak dengan Metode Waterfall_. Penerbit Informatika.

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
