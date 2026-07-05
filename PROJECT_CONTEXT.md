# PROJECT CONTEXT — Storimax Agency Admin System
# Paste file ini di awal setiap sesi chat baru dengan AI
# Update bagian [PROGRESS] dan [SESI TERAKHIR] setiap selesai sesi
# ════════════════════════════════════════════════════════════════


## IDENTITAS PROJECT
- Nama sistem  : Storimax Agency Admin System
- Perusahaan   : PT Jalur Tengah Kreasindo
- Tagline      : Story in Motion. Maxed to Perfection.
- Framework    : Laravel 12 (PHP 8.2.12)
- Database     : MySQL 8 via XAMPP
- Frontend     : Blade + Tailwind CSS v4 + Alpine.js
- PDF          : Laravel-DOMPDF (barryvdh/laravel-dompdf)
- Bundler      : Vite v7
- Local URL    : http://localhost/storimax/public
- Project path : C:\xampp\htdocs\storimax


## AKUN LOGIN DEMO
| Email                  | Password | Role   |
|------------------------|----------|--------|
| admin@storimax.id      | password | Admin  |
| atasan@storimax.id     | password | Atasan |
| budi@storimax.id       | password | Crew   |
| sari@storimax.id       | password | Crew   |
| andi@storimax.id       | password | Crew   |
| rizky@gmail.com        | password | Client |
| dewi@gmail.com         | password | Client |


## ROLE SYSTEM
- Admin  : Full akses CRUD semua fitur, operator harian
- Atasan : Read-only + approval invoice, pemantau/direktur
- Crew   : Akses job sendiri saja, update progress
- Client : View invoice & portofolio via client portal


## TECH STACK DETAIL
- SESSION_DRIVER=file (bukan database, ada issue dengan MySQL XAMPP)
- UUID sebagai primary key semua tabel
- Soft delete pada: users, clients, projects, jobs, portfolios, invoices
- PHP Enum untuk: UserRole, ProjectStatus, JobStatus, JobPriority, InvoiceStatus
- Controller langsung query Model (belum pakai Repository/Service pattern)
- Kode project otomatis: STX-YYYY-NNN
- Nomor invoice otomatis: INV/STX/YYYY/NNN (perhatikan ada karakter "/" — lihat KNOWN ISSUES)
- Job status flow: todo → inprogress → review → done (auto set started_at & completed_at)
- Invoice status flow: draft → sent → dp_paid → paid / overdue
- Setiap perubahan status job otomatis tercatat di job_logs (audit trail)


## DATA REAL DI DATABASE (per sesi terakhir)
### Total Data
- 7 users: 1 admin, 1 atasan, 3 crew, 2 client
- 2 clients: PT Maju Bersama (Rizky Pratama), Dewi Sartika
- 4 projects: 002 Branding UMKM (active), 003 Live Streaming Wedding (review), 004 Katalog Produk (done)
- 5 teams: Kreatif, Produksi, Pasca Produksi, Fotografi, huru hara
- 6 team members
- 8 jobs (status bervariasi: todo → inprogress → review → done)
- 4 invoices (1 draft, 1 sent, 2 dp_paid)
- 0 notifications (belum ada trigger)

### Detail Project
| Code | Project | Client | Status | Jobs | Tim | Invoice |
|------|---------|--------|--------|------|-----|---------|
| STX-2026-001 | Video Company Profil | Rizky | Draft | 1 | 1 | Draft |
| STX-2026-002 | Branding Paket UMKM | Dewi | Active | 3 | 1 | DP Paid |
| STX-2026-003 | Live Streaming Wedding | Rizky | Review | 3 | 2 | Sent |
| STX-2026-004 | Fotografi Katalog Produk | Dewi | Done | 1 | 1 | DP Paid |


## STRUKTUR FOLDER PENTING
```
app/
├── Enums/
│   ├── UserRole.php          ✅ selesai
│   ├── ProjectStatus.php     ✅ selesai (dibuat manual)
│   ├── JobStatus.php         ✅ selesai (dibuat manual)
│   ├── JobPriority.php       ✅ selesai (dibuat manual)
│   └── InvoiceStatus.php     ✅ selesai (dibuat manual)
├── Models/                   ✅ semua 15 model selesai
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php              ✅ selesai
│   │   ├── Admin/DashboardController.php        ✅ selesai (sudah ambil data real dari DB)
│   │   ├── Admin/DepartmentController.php       ✅ selesai
│   │   ├── Admin/UserController.php             ✅ selesai
│   │   ├── Admin/ClientController.php           ✅ selesai
│   │   ├── Admin/ProjectController.php          ✅ selesai
│   │   ├── Admin/ProjectTeamController.php      ✅ selesai
│   │   ├── Admin/JobController.php              ✅ selesai
│   │   ├── Admin/InvoiceController.php          ✅ selesai (CRUD + PDF + status flow)
│   │   ├── Admin/PortfolioController.php        ✅ selesai (CRUD + togglePublik + upload thumbnail)
│   │   ├── Admin/ReportController.php           ✅ selesai (rekap crew + export PDF/CSV)
│   │   ├── Crew/DashboardController.php         ✅ selesai (data real dari DB)
│   │   ├── Crew/JobController.php               ✅ selesai (index/show, scoped ke job sendiri)
│   │   ├── Crew/ProgressController.php          ✅ selesai (updateStatus + upload/delete attachment)
│   │   ├── Client/DashboardController.php       ✅ selesai (data real dari DB)
│   │   ├── Client/InvoiceController.php         ✅ selesai (index/show, scoped ke client sendiri)
│   │   └── Client/PortfolioController.php       ✅ selesai (index/show publik only)
│   └── Middleware/
│       ├── CheckRole.php      ✅ selesai
│       └── LogActivity.php    ✅ selesai

resources/views/
├── layouts/
│   ├── app.blade.php          ✅ selesai
│   └── auth.blade.php         ✅ selesai (TIDAK ada @include sidebar di sini)
├── components/
│   ├── sidebar.blade.php      ✅ selesai (ada @auth wrapper)
│   ├── topbar.blade.php       ✅ selesai (ada @auth wrapper)
│   └── ui/
│       └── nav-item.blade.php ✅ selesai
├── auth/login.blade.php       ✅ selesai
├── admin/
│   ├── dashboard/index.blade.php        ✅ selesai (data real dari DB)
│   ├── departments/index.blade.php      ✅ selesai
│   ├── users/{index,edit}.blade.php     ✅ selesai
│   ├── clients/{index,edit}.blade.php   ✅ selesai
│   ├── projects/
│   │   ├── index.blade.php              ✅ selesai
│   │   ├── create.blade.php             ✅ selesai
│   │   ├── edit.blade.php               ✅ selesai
│   │   └── show.blade.php               ✅ selesai (multi-tim + PIC + jobs)
│   ├── jobs/
│   │   ├── index.blade.php              ✅ selesai
│   │   ├── create.blade.php             ✅ selesai
│   │   ├── edit.blade.php               ✅ selesai
│   │   └── show.blade.php               ✅ selesai (update status + activity log)
│   ├── invoices/
│   │   ├── index.blade.php              ✅ selesai (filter status)
│   │   ├── create.blade.php             ✅ selesai (dynamic items pakai Alpine.js)
│   │   ├── show.blade.php               ✅ selesai (preview format Storimax)
│   │   └── pdf.blade.php                ✅ selesai (redesigned — layout profesional 2 kolom + DejaVu Sans)
│   ├── portfolios/
│   │   ├── index.blade.php              ✅ selesai (filter publik/privat + thumbnail)
│   │   ├── create.blade.php             ✅ selesai (form + upload thumbnail + tags)
│   │   ├── edit.blade.php               ✅ selesai (form + preview thumbnail + tags)
│   │   └── show.blade.php               ✅ selesai (detail + toggle publik)
│   └── reports/
│       ├── index.blade.php              ✅ selesai (summary cards + tabel performa crew + rekap departemen)
│       ├── crew.blade.php               ✅ selesai (detail crew + stat cards + progress bar + riwayat job)
│       └── pdf.blade.php                ✅ selesai (template PDF untuk export)
├── crew/
│   ├── dashboard/index.blade.php        ✅ selesai (data real + layout app)
│   └── jobs/
│       ├── index.blade.php              ✅ selesai (filter status + list)
│       └── show.blade.php               ✅ selesai (detail + update status + activity log + upload file)
├── client/
│   ├── dashboard/index.blade.php        ✅ selesai (data real + layout app)
│   ├── invoices/
│   │   ├── index.blade.php              ✅ selesai
│   │   └── show.blade.php               ✅ selesai (preview invoice)
│   └── portfolios/
│       ├── index.blade.php              ✅ selesai (card grid + thumbnail)
│       └── show.blade.php               ✅ selesai (detail + tags)
```


## DATABASE — 15 TABEL + 2 TAMBAHAN
Semua tabel sudah dipakai aktif kecuali: job_attachments, portfolio_tags, notifications (belum ada UI-nya)
```
✅ departments, users, clients
✅ projects, project_teams, project_team_members
✅ jobs, job_logs, job_attachments ✅ (sekarang bisa upload via Crew Portal)
✅ invoices, invoice_items
✅ portfolios, portfolio_tags ✅ (sekarang ada UI CRUD + toggle publik)
✅ notifications, activity_logs
✅ sessions, cache
```


## ROUTES — CATATAN PENTING
Route jobs TIDAK pakai `Route::resource(...)->shallow()` — semua manual satu-satu di `routes/admin.php`.
Route invoices PAKAI `Route::resource('invoices', InvoiceController::class)` standar + 2 route tambahan:
```php
Route::resource('invoices', InvoiceController::class);
Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
```
Catatan: InvoiceController TIDAK punya method `edit()` dan `update()` — invoice tidak bisa diedit setelah dibuat
(by design, karena invoice adalah dokumen resmi). Kalau nanti perlu, tambahkan manual.


## PROGRESS MODUL
```
Phase 1 — Perencanaan     ✅ 100% selesai
Phase 2 — Setup Laravel   ✅ 100% selesai
Phase 3 — Skeleton        ✅ 100% selesai

Phase 4 — Development Modul:
  ✅ Auth
  ✅ Layout utama (sidebar, topbar, nav-item)
  ✅ Department Management
  ✅ User Management
  ✅ Client Management
  ✅ Project Management (multi-tim + PIC)
  ✅ Job Management (status flow + activity log)
  ✅ Dashboard (data real dari database, bukan placeholder lagi)
  ✅ Invoice Management (CRUD + kalkulasi PPH/DP + PDF download)
  ✅ Portfolio Management (CRUD + toggle publik + upload thumbnail)
  ✅ Crew Area (dashboard data real, job list, update status, upload/delete file)
  ✅ Client Portal (dashboard data real, invoice view, portfolio view)
  ✅ Report (rekap per crew + detail crew + export PDF + export CSV)
  ✅ Notifikasi In-App

Phase 5 — Testing & Polish ✅ selesai (PDF redesign + cache optimization + validasi semua route + UI polish)
```


## KONVENSI KODING
- Controller langsung query Model (belum pakai Repository/Service pattern)
- View pakai @extends('layouts.app') untuk semua halaman admin
- Flash message: session('success') dan session('error')
- Semua form pakai @csrf, method spoofing @method('PUT'/'DELETE')
- Konfirmasi hapus: onsubmit="return confirm(...)"
- Style: clean minimalis, referensi Linear/Notion
- Warna badge role     : Admin=biru, Atasan=ungu, Crew=hijau, Client=oranye
- Warna badge status project : draft=gray, active=blue, review=yellow, done=green, archived=red
- Warna badge status job     : todo=gray, inprogress=blue, review=yellow, done=green
- Warna badge status invoice : draft=gray, sent=blue, dp_paid=yellow, paid=green, overdue=red
- Form dengan item dinamis (seperti invoice items) pakai Alpine.js x-data dengan array items[]
- Nomor unik (invoice_number, project code) di-generate di Controller saat store(), format:
    Project → STX-YYYY-NNN
    Invoice → INV/STX/YYYY/NNN


## INVOICE — DETAIL TEKNIS
- Kalkulasi: subtotal (sum semua item setelah disc) → PPH% → total → DP → remaining
- Setiap item: price, disc_percent, disc_amount (otomatis dihitung), total
- Status sent/dp_paid/paid otomatis set timestamp terkait (sent_at, dp_paid_at, paid_at)
- Saat status → dp_paid, field dp_paid otomatis diisi = dp_amount
- Saat status → paid, field dp_paid otomatis diisi = total (lunas penuh)
- PDF pakai Barryvdh\DomPDF\Facade\Pdf, view terpisah di admin/invoices/pdf.blade.php (HTML+inline CSS, BUKAN Tailwind karena DOMPDF tidak support)
- Default bank: BCA - 0191040839 a.n PT JALUR TENGAH KREASINDO (bisa diisi manual saat create)


## ⚠️ TODO / PERLU DIPERBAIKI NANTI
1. Invoice belum bisa diedit setelah dibuat (tidak ada method edit/update) — perlu didiskusikan
   apakah ini diperlukan atau memang by design (invoice = dokumen final)
2. PDF invoice logo pakai base64 data URI (bypass GD dependency) — file_get_contents tanpa GD
   Kalau logo diganti, tidak perlu update template — otomatis kebaca dari public/images/logo.png
3. PDF invoice ukuran ~2 MB (base64 overhead) — bisa dioptimasi nanti dengan logo lebih kecil


## ISU YANG DIKETAHUI & SOLUSI
1. SESSION_DRIVER harus file (bukan database) — XAMPP MySQL tidak support performance_schema
2. Tabel cache & sessions dibuat manual via artisan (bukan dari migration awal)
3. Laravel 12 default SQLite — harus diubah manual ke MySQL di .env
4. Tailwind v4 tidak pakai tailwind.config.js — config via @import di CSS
5. npm harus dijalankan di CMD (bukan PowerShell) — execution policy Windows
6. Sidebar & topbar harus wrap @auth untuk mencegah null error di halaman login
7. Enum hanya UserRole.php yang terbuat via artisan — sisanya dibuat manual
8. make:enum tidak tersedia di Laravel 12 — buat file PHP manual
9. JANGAN pakai Route::resource('projects.jobs', ...)->shallow() — bikin nama route rancu
10. Setelah bug aneh sehabis banyak edit file: php artisan optimize:clear + view:clear
11. **Nama file download tidak boleh mengandung "/"** — invoice_number format INV/STX/YYYY/NNN
    harus di-replace dulu jadi "-" sebelum dipakai sebagai filename PDF.
    Solusi yang dipakai: str_replace('/', '-', $invoice->invoice_number) . '.pdf'
12. View PDF (DOMPDF) tidak bisa pakai class Tailwind — harus inline CSS atau <style> tag biasa


## COMMAND BERGUNA
```bash
npm run dev                       # jalankan Vite (wajib saat development)
php artisan optimize:clear        # clear semua cache sekaligus
php artisan config:cache          # cache config untuk performa
php artisan route:cache           # cache routes
php artisan view:clear            # clear compiled views
php artisan route:clear           # clear route cache (setelah edit routes/*.php)
composer dump-autoload            # reload autoload (jika class not found)
php artisan route:list --name=invoices   # cek route spesifik
php artisan migrate:status
php artisan storage:link           # symlink storage (untuk upload file)
php artisan db:seed --class=DummyDataSeeder  # seed data dummy project + invoice
php artisan tinker
```


## NOTIFIKASI IN-APP — DETAIL TEKNIS
- Helper: `App\Helpers\NotificationHelper` dengan method `notify()`, `notifyMany()`, `notifyAdmins()`
- Model: `App\Models\Notification` (UUID, timestamps manual, soft delete tidak dipakai)
- Trigger otomatis pada event:
  - Job assigned → notif ke crew (`job_assigned`)
  - Job status update oleh admin → notif ke crew (`job_status_*`)
  - Job status review/done oleh crew → notif ke semua admin (`job_review`, `job_done`)
  - Invoice sent → notif ke client (`invoice_sent`)
  - Invoice dp_paid/paid → notif ke semua admin (`invoice_dp_paid`, `invoice_paid`)
  - Portfolio published → notif ke semua admin (`portfolio_published`)
- Dropdown topbar: 8 notif terbaru, bisa klik untuk mark as read + redirect
- Halaman `/notifications`: semua notifikasi, mark all as read, hapus, pagination


## CARA PAKAI FILE INI
Saat mulai sesi baru: copy semua isi file ini → paste ke chat AI → tulis request.
Setelah selesai sesi: ketik "Update PROJECT_CONTEXT.md" → download → replace file lama.


## SESI TERAKHIR — Update ini setiap selesai sesi
Tanggal  : 5 Juli 2026 (sesi 5)
Fokus    : Final Confirmation & Stabilization

### Ringkasan Sesi 5:
- Login user dikonfirmasi berfungsi setelah database di-reseed
- Semua fitur stabil — user menyatakan "sudah bisa login" dan sistem siap digunakan
- PDF invoice: base64 data URI telah menggantikan GD dependency, berfungsi penuh
- Database di-reseed dengan data dummy terbaru
- Semua 42 unit test passing

### Status Final Project:
- **Phase 1-3:** ✅ Selesai (Perencanaan, Setup, Skeleton)
- **Phase 4 (13 Modul Development):** ✅ Selesai
- **Phase 5 (Testing & Polish):** ✅ Selesai
- **Unit Test:** ✅ 42 tests — ProjectControllerTest (11), InvoiceControllerTest (13), JobControllerTest (17)
- **PDF Invoice:** ✅ Bypass GD dengan base64 data URI (dinamis, fallback teks)
- **Login:** ✅ Berfungsi normal
- **Database:** ✅ Fresh seed dengan 7 users, 2 clients, 4 projects, 8 jobs, 4 invoices, 5 teams

## PROJECT STATUS — ✅ FINAL — SEMUA FITUR AMAN

**Ingat:** selalu update PROJECT_CONTEXT.md setiap selesai sesi
**Ingat:** jalankan `php artisan migrate:fresh --seed` jika data dummy hilang
**Ingat:** restart Apache setelah ubah php.ini
**Ingat:** jalankan `php artisan config:cache` + `route:cache` setelah deploy
