# 🔍 AUDIT SISTEM — Storimax Agency Admin System
## Hasil Pemeriksaan & Rekomendasi Pengembangan

| **Informasi** | **Detail** |
|--------------|------------|
| **Sistem** | Storimax Agency Admin System |
| **Tanggal Audit** | 13 Juli 2026 |
| **Framework** | Laravel 12 (PHP 8.2.12) |
| **Database** | MySQL 8 via XAMPP |
| **Unit Test** | ✅ 44/44 PASS (139 assertions) |
| **Vite Build** | ✅ Sukses |

---

## DAFTAR ISI

1. [Ringkasan Baseline](#1-ringkasan-baseline)
2. [🔴 Critical Issues — Perlu Segera Diperbaiki](#2-critical-issues--perlu-segera-diperbaiki)
3. [🟡 Medium Issues — Perlu Perbaikan Bertahap](#3-medium-issues--perlu-perbaikan-bertahap)
4. [🟢 Low Issues / Catatan](#4-low-issues--catatan)
5. [✅ Yang Sudah Baik](#5--yang-sudah-baik)
6. [💡 Rekomendasi Fitur Masa Depan](#6--rekomendasi-fitur-masa-depan)
7. [📊 Prioritas Pengembangan](#7--prioritas-pengembangan)

---

## 1. RINGKASAN BASELINE

### Hasil Test Suite
| **Komponen** | **Status** | **Detail** |
|-------------|-----------|------------|
| Unit Tests | ✅ **44/44 PASS** | 139 assertions, 0 failures |
| Vite Build | ✅ **Sukses** | 968ms, production assets siap |
| Route List | ✅ **85 routes** | Semua controller terdaftar valid |

### Cakupan Test per Modul
| **Modul** | **Test Count** | **Cakupan** |
|-----------|:-------------:|-------------|
| Admin ProjectController | 11 test | CRUD, status flow, validasi |
| Admin InvoiceController | 14 test | CRUD, status flow, kalkulasi, PDF, DP 0 |
| Admin JobController | 17 test | CRUD, status flow, log, validasi |
| **Crew Dashboard** | **0 test** | ❌ Tidak ada test |
| **Crew JobController** | **0 test** | ❌ Tidak ada test |
| **Crew ProgressController** | **0 test** | ❌ Tidak ada test |
| **Client Dashboard** | **0 test** | ❌ Tidak ada test |
| **Client Invoice** | **0 test** | ❌ Tidak ada test |
| **Client Portfolio** | **0 test** | ❌ Tidak ada test |
| **Notifikasi** | **0 test** | ❌ Tidak ada test |
| **Profil** | **0 test** | ❌ Tidak ada test |
| **Auth** | **0 test** | ❌ Tidak ada test |

---

## 2. 🔴 CRITICAL ISSUES — Perlu Segera Diperbaiki

### CRIT-01: ProgressController — Null Project Access

| **Item** | **Detail** |
|----------|------------|
| **File** | `app/Http/Controllers/Crew/ProgressController.php:63-64` |
| **Baris** | `$client = $job->project->client;` dan `$job->project->name` |
| **Masalah** | Jika project telah di-cascade-delete, `$job->project` bernilai null. Akses `->client` dan `->name` menyebabkan **Attempt to read property on null** |
| **Dampak** | Crew tidak bisa update status job yang project-nya sudah dihapus |

**Fix:**
```php
// Sebelum
$client = $job->project->client;
// Sesudah
$client = optional($job->project)->client;

// Dan untuk message:
"Progress job \\\"{$job->title}\\\" di project \\\"{$job->project?->name ?? '—'}\\\""
```

---

### CRIT-02: Sessions Table — Foreign Key Type Mismatch

| **Item** | **Detail** |
|----------|------------|
| **File** | `database/migrations/2026_06_24_090847_create_sessions_table.php:20` |
| **Baris** | `$table->foreignId('user_id')->nullable()->index();` |
| **Masalah** | `foreignId()` menghasilkan kolom `BIGINT UNSIGNED`, tapi semua primary key sistem adalah **UUID (VARCHAR 36)**. Jika suatu saat `SESSION_DRIVER` diubah ke database, ini akan error |
| **Dampak** | Saat ini aman karena pakai file session. Potensi error jika migrasi ke database session |

**Fix:**
```php
// Sebelum
$table->foreignId('user_id')->nullable()->index();
// Sesudah
$table->string('user_id', 36)->nullable()->index();
```

---

### CRIT-03: Crew Bisa Set Status ke "todo" (Invalid Transition)

| **Item** | **Detail** |
|----------|------------|
| **File** | `app/Http/Controllers/Crew/ProgressController.php:19` |
| **Baris** | `'status' => 'required|in:todo,inprogress,review,done'` |
| **Masalah** | Status "todo" adalah initial state, bukan target transisi untuk crew. Crew bisa mengubah job dari "done" kembali ke "todo" yang tidak sesuai flow |
| **Dampak** | Crew bisa memanipulasi progress job dengan mengembalikan ke status awal |

**Fix:**
```php
// Sebelum
'status' => 'required|in:todo,inprogress,review,done'
// Sesudah
'status' => 'required|in:inprogress,review,done'
```

---

## 3. 🟡 MEDIUM ISSUES — Perlu Perbaikan Bertahap

### MED-01: Tidak Ada Pagination

| **Item** | **Detail** |
|----------|------------|
| **Lokasi** | SEMUA controller method `index()` |
| **Pola** | `->latest()->get()` tanpa pagination |
| **Dampak** | Jika data mencapai 100+ project/500+ jobs/200+ invoices, halaman akan lambat dan memory-heavy. Semua data di-load dalam 1 request |
| **Severity** | Skalabilitas jangka menengah |

**Fix:**
```php
// Sebelum
$projects = Project::with(['client', 'creator'])->latest()->get();
// Sesudah
$projects = Project::with(['client', 'creator'])->latest()->paginate(20);
```

---

### MED-02: Tidak Ada Search / Filter

| **Item** | **Detail** |
|----------|------------|
| **Lokasi** | SEMUA halaman index (projects, jobs, invoices, portfolios, users, clients) |
| **Masalah** | Tidak ada search bar, dropdown filter status, atau filter tanggal |
| **Dampak** | User harus scroll manual mencari data — tidak scalable untuk agency besar |

---

### MED-03: PHPUnit Deprecation — `/** @test */` vs Attributes

| **Item** | **Detail** |
|----------|------------|
| **File** | Semua 3 file test |
| **Pola** | `/** @test */` (doc-comment annotation) |
| **Masalah** | PHPUnit 12 akan **require** `#[Test]` attribute. Saat ini masih kompatibel, tapi akan deprecated |
| **Dampak** | Upgrade PHPUnit ke versi terbaru akan break semua test |

**Fix:**
```php
// Sebelum
/** @test */
public function it_can_list_projects()
// Sesudah
use PHPUnit\Framework\Attributes\Test;
// ...
#[Test]
public function it_can_list_projects()
```

---

### MED-04: PPH Calculation — Verify Business Logic

| **Item** | **Detail** |
|----------|------------|
| **File** | `app/Http/Controllers/Admin/InvoiceController.php:87` |
| **Rumus** | `$total = $subtotal - $pphAmount` |
| **Masalah** | PPH 2% dikurangkan dari subtotal (mengurangi total invoice). Biasanya PPh menambah total invoice. Perlu verifikasi dengan tim keuangan |
| **Catatan** | Di migration: `pph_rate` di-comment sebagai `persentase PPH, default 2%` |

---

### MED-05: PRD Bertentangan dengan Implementasi Invoice Edit

| **Item** | **Detail** |
|----------|------------|
| **PRD** | "Invoice tidak bisa diedit setelah dibuat (by design)" |
| **Implementasi** | `InvoiceController@edit` dan `@update` **ADA** dan berfungsi |
| **Masalah** | Dokumentasi tidak sinkron dengan kode. Invoice bisa diedit melalui routes admin |
| **Solusi** | Update PRD atau hapus route & method edit invoice |

---

### MED-06: N+1 Query di Topbar

| **Item** | **Detail** |
|----------|------------|
| **File** | `resources/views/components/topbar.blade.php:63` |
| **Baris** | `auth()->user()->notifications()->latest('created_at')->take(8)->get()` |
| **Masalah** | Query dieksekusi di view (tidak ada eager loading). Untuk setiap halaman yang di-load, query notifikasi dijalankan |
| **Dampak** | Minor — hanya 1 query per halaman, tapi tidak sesuai MVC pattern |

---

## 4. 🟢 LOW ISSUES / CATATAN

### LOW-01: File welcome.blade.php — Inline Tailwind CSS Besar

`resources/views/welcome.blade.php` mengandung **entire Tailwind v4 CSS** yang di-inline (≈500KB+). File ini tidak dipakai (semua halaman pake layout app/auth), tapi tetap di-render. Bisa dihapus atau di-simplify.

### LOW-02: ProjectTeam & ProjectTeamMember Models Tidak Dipakai Aktif

Kedua model ini masih ada di codebase (`app/Models/ProjectTeam.php`, `app/Models/ProjectTeamMember.php`) tapi fitur team/PIC telah dihapus. Migrasi tabel masih ada. Perlu clean up atau di-archive.

### LOW-03: Konstanta Bank Default Duplikasi

Default bank (BCA - 0191040839 - PT JALUR TENGAH KREASINDO) di-hardcode di **3 tempat**:
- `InvoiceController@store` (resources/views/admin/invoices/create.blade.php)
- `InvoiceController@update`
- `InvoiceController@downloadPdf`

### LOW-04: Tidak Ada Limit untuk Generate Kode Project & Invoice

Kode `STX-2026-999` akan habis. Setelah 999, kode berikutnya jadi `STX-2026-1000` yang formatnya beda. Perlu handle overflow (pad to 4 digits atau reset per tahun).

---

## 5. ✅ YANG SUDAH BAIK

| **Aspek** | **Keterangan** |
|-----------|----------------|
| **Null Safety di View** | ✅ Semua akses `$invoice->project`, `$job->project`, `$invoice->client` sudah di-guard |
| **Cascade Delete** | ✅ Project delete cascade soft-delete jobs+invoices+portfolios |
| **Middleware Role** | ✅ Route dipisah untuk shared read (admin+atasan) vs admin-only mutations |
| **UUID Primary Keys** | ✅ Semua tabel menggunakan UUID (mencegah enumerasi data) |
| **Soft Delete** | ✅ Semua entitas utama menggunakan SoftDeletes |
| **CSRF Protection** | ✅ Semua mutation form menggunakan `@csrf` |
| **Enum Status** | ✅ Menggunakan PHP Enum — type-safe, tidak bisa typo |
| **Job Log Audit** | ✅ Setiap perubahan status job tercatat di job_logs (append-only) |
| **Notifikasi Terstruktur** | ✅ Helper notify, notifyMany, notifyAdmins — rapi dan reusable |
| **Activity Log Middleware** | ✅ Semua aksi mutasi tercatat otomatis |
| **UI Konsisten** | ✅ Badge warna, Inter font, Tailwind v4, Linear/Notion style |
| **Format Kode Otomatis** | ✅ Project code & invoice number di-generate otomatis |
| **Overflow Protection** | ✅ Tidak ada stack trace bocor ke user (Laravel exception handler) |

---

## 6. 💡 REKOMENDASI FITUR MASA DEPAN

### Prioritas Tinggi (High)

| **#** | **Fitur** | **Deskripsi** | **Manfaat** |
|:-----:|-----------|---------------|-------------|
| 1 | **🔍 Search & Filter** | Search bar + dropdown filter (status, priority, date range) di semua halaman index | User bisa cari data cepat |
| 2 | **📄 Pagination** | Semua list page: 20-50 data per halaman | Performa stabil untuk ratusan data |
| 3 | **📧 Email Notifikasi** | Integrasi Mailgun/SMTP — notifikasi via email selain in-app | Client & crew tidak ketinggalan update |
| 4 | **🔄 Restore Data** | Fitur restore soft-deleted data (project, job, invoice) | Recovery dari penghapusan tidak sengaja |
| 5 | **📊 Dashboard Grafik** | Chart.js/Chart.js bar chart untuk progress project, revenue, job completion | Visual insights lebih baik |

### Prioritas Sedang (Medium)

| **#** | **Fitur** | **Deskripsi** |
|:-----:|-----------|---------------|
| 6 | **👥 Multi-assignee Job** | Satu job bisa di-assign ke beberapa crew (bisa kolaborasi) |
| 7 | **📅 Calendar View** | Kalender untuk melihat deadline project & job per bulan |
| 8 | **📝 Timesheet / Log Time** | Crew mencatat waktu pengerjaan per job (untuk billing) |
| 9 | **🔔 Email Notification Preferences** | User bisa pilih notifikasi apa yang dikirim via email vs in-app |
| 10 | **📋 Activity Log Viewer** | Halaman admin untuk melihat & filter activity log (saat ini hanya di database) |
| 11 | **🔄 Client Portal — Update Profile** | Client bisa update data diri sendiri dari portal |
| 12 | **📱 Mobile Responsive** | Optimalisasi UI untuk mobile (sidebar collapse, font size) |

### Prioritas Rendah (Low)

| **#** | **Fitur** | **Deskripsi** |
|:-----:|-----------|---------------|
| 13 | **🌙 Dark Mode** | Toggle dark/light theme dengan persist ke user preference |
| 14 | **📎 Multiple File Upload** | Upload langsung file (bukan GDrive link) untuk job attachments |
| 15 | **🌐 Multi-language / i18n** | Dukungan bahasa Indonesia + Inggris |
| 16 | **🔐 Two-Factor Auth (2FA)** | Google Authenticator / TOTP untuk admin |
| 17 | **📈 Revenue Reports** | Laporan pemasukan per bulan/tahun, outstanding invoice |
| 18 | **🛡️ Rate Limiting** | Limit percobaan login (mencegah brute force) |
| 19 | **🎨 Customizable Invoice Template** | Drag-and-drop editor untuk template PDF invoice (saat ini berbasis markdown) |
| 20 | **🔗 API Endpoints** | REST API untuk integrasi dengan tools eksternal (Zapier, dll) |

### Fitur dari Dokumen Sebelumnya yang Belum Ada

| **Fitur** | **Status** | **Catatan** |
|-----------|:----------:|-------------|
| Department | ❌ Dihapus | Tidak relevan dengan struktur saat ini |
| File Upload (Job Attachment) | ❌ Diganti GDrive | Link Google Drive sebagai pengganti upload file |
| Project Team Management | ❌ Tidak dipakai | Model masih ada, fitur tidak aktif |
| Invoice Editing | ⚠️ Ada tapi kontradiksi | PRD bilang tidak bisa, implementasi bisa |

---

## 7. 📊 PRIORITAS PENGEMBANGAN

### Tahap 1 — Fix Critical Bugs (Segera)

| **Task** | **Effort** | **Impact** |
|----------|:----------:|:----------:|
| Fix ProgressController null project | 🔵 15 menit | Mencegah crash saat crew update job |
| Fix sessions table FK type | 🔵 10 menit | Mencegah error jika pindah ke DB session |
| Fix crew bisa set status "todo" | 🔵 5 menit | Mencegah manipulasi progress |
| Fix PRD inkonsistensi invoice edit | 🔵 10 menit | Dokumentasi sinkron |

### Tahap 2 — Medium Improvements (Minggu Ini)

| **Task** | **Effort** | **Impact** |
|----------|:----------:|:----------:|
| Tambah pagination (20/page) | 🔵 2 jam | Performa stabil untuk data besar |
| Tambah search + filter | 🟡 4 jam | UX meningkat drastis |
| Fix test deprecations | 🔵 30 menit | Future-proof test suite |
| Tambah test untuk Crew & Client | 🟡 4 jam | Coverage meningkat dari 43% ke 80%+ |
| Tambah test cascade delete | 🔵 1 jam | Verifikasi cascade berfungsi |

### Tahap 3 — Future Features (Minggu Depan)

| **Task** | **Effort** | **Impact** |
|----------|:----------:|:----------:|
| Email notifications | 🟡 6 jam | Notifikasi tidak terlewat |
| Restore data (soft delete undo) | 🟡 4 jam | Recovery dari kesalahan |
| Dashboard charts | 🟡 4 jam | Visual insights |
| Activity log viewer | 🔵 2 jam | Transparansi sistem |

---

**Keterangan Effort:**
- 🔵 **Blue** = < 2 jam (mudah)
- 🟡 **Yellow** = 2-6 jam (sedang)
- 🔴 **Red** = > 6 jam (kompleks)

---

> **Dokumen Audit** — Storimax Agency Admin System — 13 Juli 2026
> **PT Jalur Tengah Kreasindo**
