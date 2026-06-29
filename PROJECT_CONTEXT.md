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
- Kode project otomatis: STX-YYYY-NNN (contoh: STX-2026-001)


## STRUKTUR FOLDER PENTING
```
app/
├── Enums/
│   ├── UserRole.php          ✅ selesai
│   ├── ProjectStatus.php     ✅ selesai (dibuat manual, bukan via artisan)
│   ├── JobStatus.php         ✅ selesai (dibuat manual, bukan via artisan)
│   ├── JobPriority.php       ✅ selesai (dibuat manual, bukan via artisan)
│   └── InvoiceStatus.php     ✅ selesai (dibuat manual, bukan via artisan)
├── Models/                   ✅ semua 15 model selesai
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php              ✅ selesai
│   │   ├── Admin/DashboardController.php        ✅ selesai
│   │   ├── Admin/DepartmentController.php       ✅ selesai
│   │   ├── Admin/UserController.php             ✅ selesai
│   │   ├── Admin/ClientController.php           ✅ selesai
│   │   ├── Admin/ProjectController.php          ✅ selesai
│   │   ├── Admin/ProjectTeamController.php      ✅ selesai
│   │   ├── Admin/JobController.php              🔲 belum diisi
│   │   ├── Admin/InvoiceController.php          🔲 belum diisi
│   │   ├── Admin/PortfolioController.php        🔲 belum diisi
│   │   ├── Admin/ReportController.php           🔲 belum diisi
│   │   ├── Crew/DashboardController.php         ✅ selesai (sementara)
│   │   ├── Crew/JobController.php               🔲 belum diisi
│   │   ├── Crew/ProgressController.php          🔲 belum diisi
│   │   ├── Client/DashboardController.php       ✅ selesai (sementara)
│   │   ├── Client/InvoiceController.php         🔲 belum diisi
│   │   └── Client/PortfolioController.php       🔲 belum diisi
│   └── Middleware/
│       ├── CheckRole.php      ✅ selesai
│       └── LogActivity.php    ✅ selesai

resources/views/
├── layouts/
│   ├── app.blade.php          ✅ selesai
│   └── auth.blade.php         ✅ selesai
├── components/
│   ├── sidebar.blade.php      ✅ selesai (ada @auth wrapper)
│   ├── topbar.blade.php       ✅ selesai (ada @auth wrapper)
│   └── ui/
│       └── nav-item.blade.php ✅ selesai
├── auth/
│   └── login.blade.php        ✅ selesai
├── admin/
│   ├── dashboard/index.blade.php        ✅ selesai
│   ├── departments/index.blade.php      ✅ selesai
│   ├── users/
│   │   ├── index.blade.php              ✅ selesai
│   │   └── edit.blade.php               ✅ selesai
│   ├── clients/
│   │   ├── index.blade.php              ✅ selesai
│   │   └── edit.blade.php               ✅ selesai
│   ├── projects/
│   │   ├── index.blade.php              ✅ selesai (filter status)
│   │   ├── create.blade.php             ✅ selesai
│   │   ├── edit.blade.php               ✅ selesai
│   │   └── show.blade.php               ✅ selesai (multi-tim + PIC + jobs)
│   ├── jobs/                            🔲 belum dibuat
│   ├── invoices/                        🔲 belum dibuat
│   ├── portfolios/                      🔲 belum dibuat
│   └── reports/                         🔲 belum dibuat
├── crew/
│   ├── dashboard/index.blade.php        ✅ selesai (sementara)
│   └── jobs/                            🔲 belum dibuat
└── client/
    ├── dashboard/index.blade.php        ✅ selesai (sementara)
    ├── invoices/                        🔲 belum dibuat
    └── portfolios/                      🔲 belum dibuat
```


## DATABASE — 15 TABEL + 2 TAMBAHAN
```
✅ departments
✅ users
✅ clients
✅ projects             (kode otomatis: STX-YYYY-NNN)
✅ project_teams        (nama tim + pic_user_id)
✅ project_team_members (pivot crew ↔ tim)
✅ jobs
✅ job_logs
✅ job_attachments
✅ invoices
✅ invoice_items
✅ portfolios
✅ portfolio_tags
✅ notifications
✅ activity_logs
✅ sessions             (ditambah via php artisan session:table)
✅ cache                (ditambah via php artisan cache:table)
```


## ROUTES
- Total : 78 routes + 4 route tim (projects.teams.store/destroy, teams.members.store/destroy)
- Files : routes/web.php, routes/auth.php, routes/admin.php, routes/crew.php, routes/client.php
- Prefix admin  : /admin — middleware: auth, role:admin,atasan, log.activity
- Prefix crew   : /crew  — middleware: auth, role:crew, log.activity
- Prefix client : /client — middleware: auth, role:client

### Route tambahan di admin.php (di luar resource):
```php
// Project Teams
Route::post('projects/{project}/teams', [ProjectTeamController::class, 'store'])->name('projects.teams.store');
Route::delete('projects/{project}/teams/{team}', [ProjectTeamController::class, 'destroy'])->name('projects.teams.destroy');
Route::post('projects/{project}/teams/{team}/members', [ProjectTeamController::class, 'addMember'])->name('projects.teams.members.store');
Route::delete('projects/{project}/teams/{team}/members/{member}', [ProjectTeamController::class, 'removeMember'])->name('projects.teams.members.destroy');
```


## PROGRESS MODUL
```
Phase 1 — Perencanaan     ✅ 100% selesai
Phase 2 — Setup Laravel   ✅ 100% selesai
Phase 3 — Skeleton        ✅ 100% selesai

Phase 4 — Development Modul:
  ✅ Auth (login, logout, redirect per role)
  ✅ Layout utama (sidebar, topbar, nav-item)
  ✅ Department Management (CRUD)
  ✅ User Management (CRUD + toggle aktif + badge role)
  ✅ Client Management (CRUD)
  ✅ Project Management (CRUD + multi-tim + PIC + filter status)
  🔲 Job Management        ← NEXT
  🔲 Progress Tracker (crew update status + upload file)
  🔲 Invoice (generate PDF format Storimax)
  🔲 Portfolio (toggle publik, client portal)
  🔲 Report (rekap per crew, export)
  🔲 Notifikasi In-App
  🔲 Client Portal (invoice + portfolio view)

Phase 5 — Testing & Polish 🔲 belum dimulai
```


## KONVENSI KODING
- Controller langsung query Model (belum pakai Repository/Service pattern)
- View pakai @extends('layouts.app') untuk semua halaman admin
- Flash message: session('success') dan session('error')
- Komponen UI: x-ui.nav-item
- Semua form pakai @csrf, method spoofing @method('PUT'/'DELETE')
- Konfirmasi hapus: onsubmit="return confirm(...)"
- Style: clean minimalis, referensi Linear/Notion
- Warna badge role : Admin=biru, Atasan=ungu, Crew=hijau, Client=oranye
- Warna badge status project:
    draft    → bg-gray-100 text-gray-600
    active   → bg-blue-50 text-blue-700
    review   → bg-yellow-50 text-yellow-700
    done     → bg-green-50 text-green-700
    archived → bg-red-50 text-red-600
- Warna badge status job:
    todo       → bg-gray-100 text-gray-600
    inprogress → bg-blue-50 text-blue-700
    review     → bg-yellow-50 text-yellow-700
    done       → bg-green-50 text-green-700


## INVOICE FORMAT (Storimax)
Sesuai template fisik PT Jalur Tengah Kreasindo:
- Header  : Nama Client, Kontak, Akun Instagram, Alamat/Instansi, Tgl Sesi
- Items   : Jenis Layanan | Deskripsi | Harga | Disc% | Total Disc | Total
- Summary : Subtotal → PPH 2% → Total → DP → Pelunasan
- Footer  : BCA - 0191040839 a.n PT JALUR TENGAH KREASINDO
- Status  : draft → sent → dp_paid → paid / overdue


## ISU YANG DIKETAHUI & SOLUSI
1. SESSION_DRIVER harus file (bukan database) — XAMPP MySQL tidak support query performance_schema
2. Tabel cache dan sessions perlu dibuat manual via artisan (bukan dari migration awal)
3. Laravel 12 default pakai SQLite — harus diubah manual ke MySQL di .env
4. Tailwind v4 tidak pakai tailwind.config.js — config via @import di CSS
5. npm harus dijalankan di CMD (bukan PowerShell) — execution policy Windows
6. Sidebar dan topbar harus wrap dengan @auth untuk mencegah null error saat halaman login
7. Enum hanya UserRole.php yang terbuat via artisan — sisanya dibuat manual di app/Enums/
8. make:enum tidak tersedia di Laravel 12 secara default — buat file PHP manual


## COMMAND BERGUNA
```bash
# Development
npm run dev                       # jalankan Vite (wajib saat development)

# Cache & clear
php artisan optimize:clear        # clear semua cache sekaligus
php artisan view:clear            # clear compiled views
php artisan config:clear          # clear config cache
composer dump-autoload            # reload autoload (pakai jika class not found)

# Database
php artisan migrate:status        # cek status migration
php artisan migrate:fresh --seed  # reset DB + seed ulang (hati-hati!)
php artisan db:seed               # jalankan seeder tanpa reset

# Tinker (testing cepat)
php artisan tinker
>>> App\Models\User::all(['name', 'email', 'role']);
>>> App\Models\Project::with('client')->get(['name', 'code', 'status']);
>>> Auth::attempt(['email' => 'admin@storimax.id', 'password' => 'password']);
```


## CARA PAKAI FILE INI
Saat mulai sesi baru:
1. Buka file ini di VSCode
2. Ctrl+A → Ctrl+C
3. Paste ke chat AI di awal percakapan
4. Tulis request kamu setelah paste

Setelah selesai sesi:
- Ketik "Update PROJECT_CONTEXT.md" → AI generate file baru
- Download dan replace file lama di C:\xampp\htdocs\storimax\


## SESI TERAKHIR — Update ini setiap selesai sesi
Tanggal  : 28 Juni 2026
Selesai  : Project Management (CRUD + multi-tim + PIC + filter status + kode otomatis)
Next     : Job Management (create job, assign ke crew, status flow)
Catatan  :
  - Enum selain UserRole harus dibuat manual (artisan make:enum tidak reliable)
  - Setelah buat file baru, selalu jalankan: composer dump-autoload + php artisan optimize:clear
  - Project pertama berhasil dibuat: STX-2026-001 "Video Company Profil"
  - Fitur tambah tim + PIC + anggota sudah berjalan sempurna
