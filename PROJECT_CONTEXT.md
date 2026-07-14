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
- Font         : Inter (via Google Fonts)
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
- Admin  : Full akses CRUD semua fitur + Manajemen (Users, Clients)
- Atasan : Read-only — hanya bisa lihat data (GET), TIDAK bisa create/edit/hapus. Tidak bisa akses Users/Clients
- Crew   : Akses job sendiri saja, update progress, upload/delete file
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
- Font Inter: preconnect + stylesheet di layouts/app.blade.php & layouts/auth.blade.php
- Scrollbar custom: webkit-scrollbar styling di CSS


## FITUR PROFIL
- Route: /profile (GET, PUT), /profile/avatar (POST), /profile/password (PUT)
- Controller: `ProfileController` — show, update, updateAvatar, updatePassword
- View: `resources/views/profile/show.blade.php` — 4 section:
  1. Info akun (avatar + nama + role + email)
  2. Upload foto profil (file input)
  3. Edit profil (nama, email, telepon)
  4. Ganti password (current + new + confirm)
- Avatar disimpan di storage/app/public/avatars/
- Sidebar: user info di bagian bawah klikable → arah ke profil


## SIDEBAR — URUTAN MENU (untuk Admin)
1. Dashboard
2. **Manajemen** (Users, Clients) — hanya untuk Admin
3. Project (Projects, Jobs)
4. Keuangan (Invoice)
5. Konten (Portofolio, Laporan)

Untuk Atasan: sama tanpa Manajemen.
Untuk Crew: Dashboard, Pekerjaan (My Jobs).
Untuk Client: Dashboard, Invoice, Portofolio.


## ROUTES — STRUKTUR BARU
Routes dipisah jadi 2 grup middleware:

**Shared read-only (Admin + Atasan):** `role:admin,atasan`
- Semua route GET: dashboard, index, show, create, edit, pdf download, reports
- Static route (/create, /edit) didefinisikan SEBELUM parameterized (/{id}) untuk mencegah konflik

**Admin-only mutations:** `role:admin`
- Semua route POST/PUT/PATCH/DELETE: store, update, destroy, updateStatus, teams, members
- Users & Clients full CRUD hanya untuk admin

Catatan: InvoiceController TIDAK punya method `edit()` dan `update()` — invoice tidak bisa diedit setelah dibuat (by design).


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
│   ├── UserRole.php              ✅ selesai
│   ├── ProjectStatus.php         ✅ selesai
│   ├── JobStatus.php             ✅ selesai
│   ├── JobPriority.php           ✅ selesai
│   └── InvoiceStatus.php         ✅ selesai
├── Helpers/
│   └── NotificationHelper.php    ✅ selesai
├── Models/                       ✅ semua 14 model selesai (Department dihapus)
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php              ✅ selesai
│   │   ├── ProfileController.php               ✅ BARU — show, update, avatar, password
│   │   ├── Admin/DashboardController.php       ✅ selesai
│   │   ├── Admin/UserController.php            ✅ selesai
│   │   ├── Admin/ClientController.php          ✅ selesai
│   │   ├── Admin/ProjectController.php         ✅ selesai
│   │   ├── Admin/ProjectTeamController.php     ✅ selesai
│   │   ├── Admin/JobController.php             ✅ selesai
│   │   ├── Admin/InvoiceController.php         ✅ selesai (CRUD + PDF + status flow)
│   │   ├── Admin/PortfolioController.php       ✅ selesai
│   │   ├── Admin/ReportController.php          ✅ selesai (tanpa department)
│   │   ├── Crew/DashboardController.php        ✅ selesai
│   │   ├── Crew/JobController.php              ✅ selesai
│   │   ├── Crew/ProgressController.php         ✅ selesai
│   │   ├── Client/DashboardController.php      ✅ selesai
│   │   ├── Client/InvoiceController.php        ✅ selesai
│   │   └── Client/PortfolioController.php      ✅ selesai
│   └── Middleware/
│       ├── CheckRole.php      ✅ selesai (multi-role)
│       └── LogActivity.php    ✅ selesai

resources/views/
├── layouts/
│   ├── app.blade.php          ✅ selesai (Inter font + scrollbar)
│   └── auth.blade.php         ✅ selesai (Inter font)
├── components/
│   ├── sidebar.blade.php      ✅ selesai (Manajemen di atas, link profil)
│   ├── topbar.blade.php       ✅ selesai
│   └── ui/nav-item.blade.php  ✅ selesai
├── auth/login.blade.php       ✅ selesai
├── profile/show.blade.php     ✅ BARU — halaman profil tiap akun
├── admin/
│   ├── dashboard/index.blade.php   ✅ selesai
│   ├── users/{index,edit}.blade.php ✅ selesai (tanpa department)
│   ├── clients/{index,edit}.blade.php ✅ selesai
│   ├── projects/...                 ✅ selesai
│   ├── jobs/...                     ✅ selesai
│   ├── invoices/...                 ✅ selesai
│   ├── portfolios/...               ✅ selesai
│   └── reports/...                  ✅ selesai (tanpa department)
├── crew/                            ✅ selesai
├── client/                          ✅ selesai
└── notifications/index.blade.php   ✅ selesai
```


## DATABASE — 15 TABEL (Department dihapus)
```
✅ users, clients, projects, project_teams, project_team_members
✅ jobs, job_logs, job_attachments
✅ invoices, invoice_items
✅ portfolios, portfolio_tags
✅ notifications, activity_logs
✅ sessions, cache
```

**Perubahan:** Department dan kolom `department_id` di users telah dihapus melalui migration `2026_07_13_000000_remove_departments`.


## KONVENSI KODING
- Controller langsung query Model (belum pakai Repository/Service pattern)
- View pakai @extends('layouts.app') untuk semua halaman admin
- Flash message: session('success') dan session('error')
- Semua form pakai @csrf, method spoofing @method('PUT'/'DELETE')
- Konfirmasi hapus: onsubmit="return confirm(...)"
- Style: clean minimalis, referensi Linear/Notion
- Font: Inter (400, 500, 600, 700, 800) via Google Fonts
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
- PDF pakai Barryvdh\DomPDF\Facade\Pdf, view terpisah di admin/invoices/pdf.blade.php (HTML+inline CSS)
- Default bank: BCA - 0191040839 a.n PT JALUR TENGAH KREASINDO


## ⚠️ TODO / PERLU DIPERBAIKI NANTI
1. Invoice belum bisa diedit setelah dibuat (by design — invoice = dokumen final)
2. PDF invoice logo pakai base64 data URI (bypass GD dependency)
3. Avatar upload butuh `php artisan storage:link` untuk symlink


## ISU YANG DIKETAHUI & SOLUSI
1. SESSION_DRIVER harus file (bukan database) — XAMPP MySQL tidak support performance_schema
2. Tabel cache & sessions dibuat manual via artisan (bukan dari migration awal)
3. Laravel 12 default SQLite — harus diubah manual ke MySQL di .env
4. Tailwind v4 tidak pakai tailwind.config.js — config via @import di CSS
5. npm harus dijalankan di CMD (bukan PowerShell) — execution policy Windows
6. Sidebar & topbar harus wrap @auth untuk mencegah null error di halaman login
7. Enum hanya UserRole.php yang terbuat via artisan — sisanya dibuat manual
8. make:enum tidak tersedia di Laravel 12 — buat file PHP manual
9. **Nama file download tidak boleh mengandung "/"** — invoice_number format INV/STX/YYYY/NNN
    harus di-replace dulu jadi "-" sebelum dipakai sebagai filename PDF.
10. View PDF (DOMPDF) tidak bisa pakai class Tailwind — harus inline CSS atau <style> tag biasa
11. **Route ordering** — static route (/create, /edit) harus SEBELUM parameterized (/{id})


## COMMAND BERGUNA
```bash
npm run dev                          # jalankan Vite
npm run build                        # build production
php artisan migrate:fresh --seed     # reset + seed database
php artisan storage:link             # symlink untuk upload avatar/file
php artisan optimize:clear           # clear semua cache
php artisan route:clear              # clear route cache setelah edit routes
php artisan route:list --name=admin  # cek route admin
php artisan view:clear               # clear compiled views
php artisan view:cache               # cache compiled views
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
Tanggal  : 13 Juli 2026 (sesi 7)
Fokus    : Null Safety & Cascade Delete, Route Notifikasi Fix, PRD, Audit, Client Cascade

### Ringkasan Sesi 7:
- **📄 PRD (Product Requirements Document):** Dibuat file `docs/PRD.md` — dokumen lengkap sistem (15 seksi)
- **🔍 Audit Sistem:** Dibuat file `docs/AUDIT.md` — 3 critical, 6 medium, 4 low issues + 20 rekomendasi fitur
- **📧 Email Client Dihilangkan:** `$client->user->email` dihapus dari tampilan daftar client (privacy)
- **🔄 Cascade Delete Client:** `Client@booted()` — otomatis cascade soft-delete:
  ```
  Client (hapus)
  ├── Projects → (trigger cascade Project) → Jobs+Attachments+Logs + Invoices+Items + Portfolios
  └── User login (akun client ikut terhapus)
  ```
- **🔧 Fix CRIT-01:** `ProgressController@updateStatus` — akses `$job->project` sekarang null-safe
- **🔧 Fix CRIT-03:** Crew tidak bisa reset job status ke "todo" lagi (validation: `in:inprogress,review,done`)
- **⚠️ Isu Kritis — Null Project Error:** Ketika project dihapus (soft-delete), jobs & invoices yang orphaned menyebabkan error `Attempt to read property "name" on null` di 10+ file view.
- **Fix Route Notifikasi:** Route `notifications.read` diubah dari `PATCH` jadi `GET|PATCH` karena `<a>` tag pakai GET
- **Cascade Delete Project:** `Project@booted()` — cascade soft-delete jobs+invoices+portfolios
- **Null-Safety di 15+ View:** Semua akses `$job->project`, `$invoice->project`, `$invoice->client` di-guard
- **Fix JobController@destroy:** Redirect ke jobs index jika project null
- **Fix Blade Cache:** `php artisan view:clear` — perubahan view tidak kelihatan karena cache
- **Fix Operator Precedence:** `.` > `??` di PHP — ternary sebagai solusi

### File yang Diubah/Dibuat:
| File | Perubahan |
|------|-----------|
| `docs/PRD.md` | **BARU** — Product Requirements Document (15 seksi) |
| `docs/AUDIT.md` | **BARU** — Laporan audit sistem + rekomendasi fitur |
| `app/Models/Client.php` | **BARU** — Cascade delete: projects + user |
| `app/Models/Project.php` | Cascade delete event: jobs+invoices+portfolios+relasi anak |
| `app/Http/Controllers/Admin/ClientController.php` | Destroy: cascade via model, hapus blocking check |
| `app/Http/Controllers/Crew/ProgressController.php` | Fix CRIT-01 (null project) + CRIT-03 (remove todo) |
| `resources/views/admin/clients/index.blade.php` | Hapus email dari tampilan daftar client |
| `routes/web.php` | Route `notifications.read` → `match(['GET','PATCH'])` |
| `app/Http/Controllers/Admin/JobController.php` | Destroy: null-check project before redirect |
| `app/Http/Controllers/Admin/InvoiceController.php` | Notifikasi + PDF: null-safe client |
| 11 view files (jobs, invoices, portfolios) | Null-safe `?->` operator |

### Cascade Delete Chains:
```
Project (hapus)
├── Jobs → JobAttachments.del + JobLogs.del + Job.del
├── Invoices → InvoiceItems.del + Invoice.del
└── Portfolios.del

Client (hapus)
├── Projects → (trigger chain di atas)
└── User login (akun client)
```

### Learnings:
- **Blade Cache Silent Bug:** `php artisan view:clear` setelah edit view
- **Operator Precedence PHP:** `.` > `??` — gunakan ternary
- **Cascade delete manual** di Laravel soft-delete
- **Cascade chains** bisa saling memanggil: Client → Project → Job → log+attachment

### Status Final Project:
- **Unit Test:** ✅ 44 tests — 139 assertions, 0 failures
- **Vite Build:** ✅ Sukses
- **Dokumentasi:** ✅ PRD.md + AUDIT.md

## PROJECT STATUS — ✅ FINAL — SEMUA FITUR AMAN

**Ingat:** selalu update PROJECT_CONTEXT.md setiap selesai sesi
**Ingat:** jalankan `php artisan migrate:fresh --seed` jika data dummy hilang
**Ingat:** restart Apache setelah ubah php.ini
**Ingat:** jalankan `php artisan storage:link` untuk upload avatar/file
