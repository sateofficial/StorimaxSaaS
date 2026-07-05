# HASIL DAN PEMBAHASAN
## Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Hasil dan Pembahasan |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Hasil Penelitian
   1.1. Sistem yang Dikembangkan
   1.2. Spesifikasi Teknis Hasil Implementasi
2. Pembahasan
   2.1. Arsitektur Sistem
   2.2. Struktur Basis Data
   2.3. Alur Status dan Logika Bisnis
   2.4. Sistem Autentikasi dan Otorisasi
   2.5. Mekanisme Penanganan CSRF pada Pengujian
   2.6. Pembuatan Faktur PDF
3. Temuan dan Solusi Permasalahan
4. Evaluasi Sistem

---

## 1. HASIL PENELITIAN

### 1.1 Sistem yang Dikembangkan

Penelitian ini menghasilkan Sistem Informasi Manajemen _Agency_ Kreatif berbasis web yang diberi nama **Storimax Agency Admin System**. Sistem dikembangkan menggunakan kerangka kerja Laravel 12 dengan basis data MySQL 8 dan antarmuka pengguna menggunakan Blade, Tailwind CSS v4, serta Alpine.js.

Sistem mencakup tigabelas modul fungsional yang terintegrasi dalam satu platform, meliputi:

1. **Modul Autentikasi** — _Login_ multi-peran dengan validasi bcrypt dan pengalihan sesuai peran.
2. **Modul Departemen** — CRUD data departemen (Foto, Video, Editing, Manajemen).
3. **Modul Pengelolaan Pengguna** — CRUD pengguna dengan pengaturan peran dan status aktif.
4. **Modul Pengelolaan Klien** — CRUD data klien dengan informasi perusahaan dan kontak.
5. **Modul Pengelolaan Proyek** — CRUD proyek dengan kode otomatis dan alur status.
6. **Modul Tim Proyek** — Pembentukan tim, penunjukan PIC, dan keanggotaan.
7. **Modul Pengelolaan Pekerjaan** — CRUD pekerjaan, penugasan kru, alur status, dan jejak audit.
8. **Modul Pengelolaan Faktur** — CRUD faktur dengan kalkulasi PPh dan DP otomatis, unduh PDF.
9. **Modul Pengelolaan Portofolio** — CRUD portofolio dengan unggah gambar dan pengaturan visibilitas.
10. **Modul Dasbor** — Ringkasan data sesuai peran pengguna.
11. **Modul Area Kru** — Daftar pekerjaan, pembaruan status, unggah lampiran.
12. **Modul Portal Klien** — Lihat faktur dan portofolio.
13. **Modul Laporan dan Notifikasi** — Rekap kinerja kru, ekspor PDF/CSV, notifikasi _in-app_.

### 1.2 Spesifikasi Teknis Hasil Implementasi

| Aspek | Spesifikasi |
|-------|-------------|
| Jumlah pengendali (_controller_) | 18 berkas |
| Jumlah model | 15 berkas |
| Jumlah tampilan (_view_) | 35+ berkas Blade |
| Jumlah _enum_ | 5 berkas |
| Jumlah tabel basis data | 15 tabel utama + 2 tabel tambahan |
| Jumlah _route_ | 40+ _endpoint_ |
| Jumlah kasus uji | 42 kasus (seluruhnya lulus) |

---

## 2. PEMBAHASAN

### 2.1 Arsitektur Sistem

Sistem dikembangkan menggunakan pola arsitektur _Model-View-Controller_ (MVC) yang disediakan oleh kerangka kerja Laravel. Pola MVC memisahkan logika aplikasi menjadi tiga komponen utama:

1. **Model** — Bertanggung jawab untuk mengelola data dan logika bisnis yang berinteraksi dengan basis data melalui Eloquent ORM.
2. **View** — Bertanggung jawab untuk menyajikan data kepada pengguna dalam bentuk halaman HTML yang dinamis menggunakan _template engine_ Blade.
3. **Controller** — Bertanggung jawab untuk menangani permintaan HTTP, memproses data melalui Model, dan mengembalikan respons melalui View.

Pemilihan arsitektur MVC didasarkan pada kemampuannya dalam memisahkan concern sehingga memudahkan pemeliharaan dan pengembangan sistem (Fowler, 2002). Laravel sebagai kerangka kerja yang mengimplementasikan pola MVC telah terbukti efektif dalam pengembangan aplikasi web skala menengah (Stauffer, 2023).

### 2.2 Struktur Basis Data

Basis data sistem dirancang dengan lima belas tabel utama yang saling berelasi. Beberapa keputusan desain basis data yang signifikan meliputi:

#### a) Penggunaan UUID sebagai Kunci Primer
Seluruh tabel menggunakan UUID (_Universally Unique Identifier_) sebagai kunci primer. Keputusan ini didasarkan pada pertimbangan keamanan (UUID tidak dapat ditebak urutannya) dan portabilitas (UUID tetap unik meskipun data dipindahkan antar basis data). Hal ini sesuai dengan rekomendasi Laravel untuk aplikasi yang memerlukan keamanan data lebih tinggi.

#### b) Penerapan Penghapusan Lunak (Soft Delete)
Enam entitas utama (users, clients, projects, jobs, invoices, portfolios) menerapkan penghapusan lunak. Pendekatan ini memastikan bahwa data tidak benar-benar dihapus dari basis data, melainkan hanya ditandai dengan _timestamp_ `deleted_at`. Hal ini bermanfaat untuk pemulihan data dan keperluan audit.

#### c) Relasi User-Client
Setiap klien memiliki akun pengguna dengan peran (_role_) _client_. Relasi satu-ke-satu antara tabel `users` dan `clients` ini memungkinkan klien untuk mengakses portal klien menggunakan kredensial _login_ yang sama dengan pengguna lainnya.

#### d) Relasi Invoice-Project
Setiap faktur terikat pada satu proyek (relasi satu-ke-satu). Keputusan ini diambil karena pada tahap pengembangan saat ini, kebutuhan bisnis belum memerlukan satu faktur untuk mencakup beberapa proyek. Untuk mendukung kebutuhan multi-proyek di masa mendatang, dapat ditambahkan tabel pivot `invoice_projects`.

### 2.3 Alur Status dan Logika Bisnis

#### a) Alur Status Proyek
```
DRAFT → ACTIVE → REVIEW → DONE → ARCHIVED
```
Proyek yang baru dibuat secara otomatis berstatus _draft_. Administrator dapat mengubah status sesuai perkembangan proyek. Status _review_ menandakan bahwa proyek sedang dalam tahap peninjauan oleh klien.

#### b) Alur Status Pekerjaan
```
TODO → INPROGRESS → REVIEW → DONE
```
Setiap perubahan status pekerjaan dicatat dalam tabel `job_logs` yang menyimpan informasi pengubah, status lama, status baru, dan catatan. Mekanisme ini berfungsi sebagai jejak audit _(audit trail)_ yang memungkinkan administrator untuk melacak riwayat perubahan status setiap pekerjaan. _Timestamp_ `started_at` diisi secara otomatis oleh sistem ketika pekerjaan memasuki status _inprogress_, sedangkan `completed_at` diisi secara otomatis ketika pekerjaan mencapai status _done_.

#### c) Alur Status Faktur
```
DRAFT → SENT → DP_PAID → PAID
              ↓         ↓
           OVERDUE   OVERDUE
```
Kalkulasi faktur dilakukan secara otomatis oleh sistem berdasarkan data item yang dimasukkan oleh administrator. Rumus kalkulasi adalah sebagai berikut:

```
subtotal    = Σ (price × (100 - disc_percent) / 100) untuk setiap item
pph_amount  = subtotal × pph_rate / 100
total       = subtotal - pph_amount
remaining   = total - dp_paid
```

Saat status faktur berubah, sistem secara otomatis mencatat _timestamp_ yang sesuai:
- `sent_at` — dicatat saat faktur dikirim ke klien
- `dp_paid_at` — dicatat saat uang muka dibayarkan, dan `dp_paid` diisi sebesar `dp_amount`
- `paid_at` — dicatat saat faktur lunas, dan `dp_paid` diisi sebesar `total`

### 2.4 Sistem Autentikasi dan Otorisasi

Sistem menerapkan otentikasi berbasis sesi dengan dua lapisan _middleware_:

1. **Middleware `auth`** — Memastikan bahwa pengguna telah _login_ sebelum dapat mengakses halaman yang dilindungi.
2. **Middleware `CheckRole`** — Memvalidasi peran pengguna untuk memastikan bahwa pengguna hanya dapat mengakses halaman yang sesuai dengan perannya.

Middleware `CheckRole` menerima parameter berupa daftar peran yang diizinkan. Jika peran pengguna tidak termasuk dalam daftar, sistem mengembalikan respons HTTP 403 (Forbidden). Implementasi ini sejalan dengan konsep _Role-Based Access Control_ (RBAC) yang direkomendasikan dalam pengembangan sistem informasi multi-pengguna (Sandhu et al., 1996).

### 2.5 Mekanisme Penanganan CSRF pada Pengujian

Salah satu tantangan dalam pengujian sistem adalah penanganan token CSRF. Laravel secara default menerapkan perlindungan CSRF pada seluruh permintaan mutasi (POST, PUT, PATCH, DELETE). Dalam pengujian unit, token CSRF harus disertakan pada setiap permintaan.

Pendekatan yang digunakan dalam penelitian ini adalah membuat metode _helper_ `withCsrf()` yang memulai sesi dan menghasilkan token CSRF yang valid:

```php
private function withCsrf(array $data): array
{
    $this->app['session']->start();
    return array_merge(['_token' => csrf_token()], $data);
}
```

Pendekatan ini dipilih karena tidak menonaktifkan _middleware_ secara keseluruhan (yang akan menyebabkan tampilan tidak dapat di-render dengan benar karena kehilangan variabel `$errors` dari `ShareErrorsFromSession`), namun tetap dapat menyertakan token CSRF yang valid pada setiap permintaan.

### 2.6 Pembuatan Faktur PDF

Sistem menggunakan pustaka DOMPDF (barryvdh/laravel-dompdf) untuk menghasilkan faktur dalam format PDF. Tantangan utama yang dihadapi adalah ketergantungan DOMPDF pada ekstensi PHP GD untuk memproses berkas gambar.

Solusi yang diterapkan adalah mengonversi logo perusahaan menjadi _base64 data URI_ langsung di dalam _template_ Blade:

```blade
@if(file_exists(public_path('images/logo.png')))
<img src="data:image/png;base64,{{ base64_encode(
    file_get_contents(public_path('images/logo.png'))
) }}" alt="Logo Storimax" class="logo-img">
@else
<span style="font-size:20px;font-weight:800;color:#2563eb;letter-spacing:1px;">
    STORIMAX
</span>
@endif
```

Fungsi `file_get_contents()` tidak memerlukan ekstensi GD (berbeda dengan `imagecreatefrompng()` dan fungsi grafis lainnya). Pendekatan ini berhasil menghasilkan file PDF tanpa kesalahan GD. Konsekuensi dari pendekatan ini adalah peningkatan ukuran berkas PDF dari sekitar 860 KB menjadi sekitar 2 MB akibat _overhead_ encoding base64.

---

## 3. TEMUAN DAN SOLUSI PERMASALAHAN

### 3.1 Sesi dengan MySQL

**Temuan:** _Driver_ sesi _database_ pada Laravel tidak berfungsi optimal dengan MySQL 8 pada lingkungan XAMPP karena masalah dukungan `performance_schema`.

**Solusi:** _Driver_ sesi diubah dari _database_ menjadi _file_ pada konfigurasi `.env`:
```ini
SESSION_DRIVER=file
```

### 3.2 Nama Berkas Unduhan Faktur

**Temuan:** Nama berkas unduhan faktur yang menggunakan format `INV/STX/2026/001.pdf` mengandung karakter "/" yang tidak diperbolehkan dalam nama berkas di sistem operasi Windows.

**Solusi:** Karakter "/" diganti dengan "-" pada nama berkas unduhan:
```php
$filename = str_replace('/', '-', $invoice->invoice_number) . '.pdf';
// Hasil: INV-STX-2026-001.pdf
```

### 3.3 Ekstensi GD untuk PDF

**Temuan:** Pustaka DOMPDF memerlukan ekstensi GD untuk memproses berkas gambar logo. Ekstensi GD tidak aktif pada lingkungan CLI PHP di XAMPP.

**Solusi:** Menggunakan _base64 data URI_ untuk menyematkan logo langsung dalam _template_ PDF, sehingga tidak memerlukan ekstensi GD.

### 3.4 Perubahan Status Pekerjaan Invalid

**Temuan:** Validasi status pekerjaan hanya menerima nilai yang terdefinisi dalam _enum_ `JobStatus` (todo, inprogress, review, done).

**Solusi:** Menerapkan validasi pada lapisan _controller_ menggunakan aturan `in:`:
```php
$request->validate([
    'status' => 'required|in:todo,inprogress,review,done',
]);
```

---

## 4. EVALUASI SISTEM

### 4.1 Pemenuhan Kebutuhan Fungsional

Berdasarkan hasil pengujian yang telah dilakukan, sistem berhasil memenuhi seluruh kebutuhan fungsional yang telah ditetapkan dalam dokumen SRS. Seluruh tigabelas modul fungsional telah berjalan sesuai dengan spesifikasi, mencakup autentikasi, manajemen departemen, pengelolaan pengguna, pengelolaan klien, pengelolaan proyek, tim proyek, pengelolaan pekerjaan, pengelolaan faktur, pengelolaan portofolio, dasbor, area kru, portal klien, serta laporan dan notifikasi.

### 4.2 Pemenuhan Kebutuhan Non-Fungsional

| Kebutuhan Non-Fungsional | Target | Realisasi |
|--------------------------|:-----:|:---------:|
| Keamanan (CSRF, bcrypt, RBAC) | Terpenuhi | ✓ Terpenuhi |
| Performa (muat < 3 detik) | Terpenuhi | ✓ Terpenuhi |
| Keandalan (UUID, soft delete) | Terpenuhi | ✓ Terpenuhi |
| Kegunaan (responsif, konsisten) | Terpenuhi | ✓ Terpenuhi |

### 4.3 Keterbatasan dan Pengembangan Selanjutnya

Beberapa aspek yang masih dapat ditingkatkan pada pengembangan selanjutnya meliputi:

1. **Integrasi payment gateway** — Pembayaran masih diverifikasi secara manual oleh administrator.
2. **Notifikasi melalui surel** — Saat ini notifikasi hanya terbatas pada _in-app notification_.
3. **Optimalisasi ukuran PDF** — Berkas PDF faktur masih ~2 MB akibat _overhead_ base64.
4. **Fitur edit faktur** — Faktur belum dapat diedit setelah dibuat (by design sebagai dokumen resmi).

---

## DAFTAR PUSTAKA

1. Fowler, M. (2002). _Patterns of Enterprise Application Architecture_. Addison-Wesley.
2. Sandhu, R. S., Coyne, E. J., Feinstein, H. L., & Youman, C. E. (1996). Role-Based Access Control Models. _IEEE Computer_, 29(2), 38–47.
3. Stauffer, M. (2023). _Laravel: Up & Running: A Framework for Building Modern PHP Apps_ (3rd ed.). O'Reilly Media.
4. W3C. (2014). _Data URI Scheme_. Retrieved from https://www.w3.org/TR/data-uri/
5. Basuki, A. (2021). Analisis dan Perancangan Sistem Informasi Berbasis Web. _Jurnal Sistem Informasi_, 8(1), 22–35.
6. Otwell, T. (2023). _Laravel: The PHP Framework for Web Artisans_. Laravel LLC.

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
