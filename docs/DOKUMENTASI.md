# DOKUMENTASI SISTEM
## Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Dokumentasi Sistem |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Arsitektur Sistem
   1.1. Arsitektur Umum
   1.2. Struktur Direktori
2. Panduan Instalasi
   2.1. Prasyarat
   2.2. Langkah Instalasi
   2.3. Konfigurasi Lingkungan
   2.4. Konfigurasi Server Web
3. Antarmuka Route
   3.1. Route Autentikasi
   3.2. Route Administrator
   3.3. Route Kru
   3.4. Route Klien
4. Struktur Basis Data
   4.1. Diagram Relasi Entitas
   4.2. Spesifikasi Tabel
5. Referensi Enum
6. Middleware
   6.1. CheckRole
   6.2. LogActivity
7. Sistem Notifikasi

---

## 1. ARSITEKTUR SISTEM

### 1.1 Arsitektur Umum

Sistem Informasi Manajemen _Agency_ Kreatif dikembangkan menggunakan arsitektur monolitik dengan pola _Model-View-Controller_ (MVC) yang disediakan oleh kerangka kerja Laravel 12. Komunikasi antara pengguna dan sistem dilakukan melalui protokol HTTP dengan peramban web sebagai antarmuka.

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    PERAMBAN WEB (Browser)                               │
│              Antarmuka: Blade + Tailwind CSS v4 + Alpine.js             │
└───────────────────────────────┬─────────────────────────────────────────┘
                                │ Permintaan HTTP
                                ↓
┌─────────────────────────────────────────────────────────────────────────┐
│                        LARAVEL 12                                       │
│                                                                         │
│  1. Routes (5 berkas)      →  2. Middleware (3 berkas)                  │
│     • web.php                    • auth (otentikasi)                    │
│     • auth.php                  • CheckRole (otorisasi)                 │
│     • admin.php                 • LogActivity (pencatatan)              │
│     • crew.php                                                        │
│     • client.php                                                       │
│                                    ↓                                    │
│  3. Controller (18 berkas)  →  4. Model (15 berkas)                    │
│     • Admin/  (11)              • Eloquent ORM                          │
│     • Auth/   (1)               • Relasi, Scope, Accessor              │
│     • Crew/   (3)                                                     │
│     • Client/ (3)               →  5. View (35+ berkas Blade)         │
│                                    • layouts/, components/             │
│                                    • admin/, crew/, client/            │
└───────────────────────────────┬─────────────────────────────────────────┘
                                │ Eloquent ORM
                                ↓
┌─────────────────────────────────────────────────────────────────────────┐
│                         MYSQL 8                                         │
│                 15 tabel utama + 2 tabel tambahan                       │
│                 Seluruh tabel menggunakan UUID sebagai kunci primer     │
└─────────────────────────────────────────────────────────────────────────┘
```

### 1.2 Struktur Direktori

```
C:\xampp\htdocs\storimax
│
├── app/                          # Kode aplikasi inti
│   ├── Enums/                    # PHP Enum (5 berkas)
│   │   ├── UserRole.php          # admin, atasan, crew, client
│   │   ├── ProjectStatus.php     # draft, active, review, done, archived
│   │   ├── JobStatus.php         # todo, inprogress, review, done
│   │   ├── JobPriority.php       # low, medium, high, urgent
│   │   └── InvoiceStatus.php     # draft, sent, dp_paid, paid, overdue
│   │
│   ├── Helpers/                  # Fungsi pembantu
│   │   └── NotificationHelper.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php    # Kelas dasar pengendali
│   │   │   ├── NotificationController.php
│   │   │   ├── Admin/            # 11 pengendali administrator
│   │   │   ├── Auth/             # 1 pengendali autentikasi
│   │   │   ├── Crew/             # 3 pengendali kru
│   │   │   └── Client/           # 3 pengendali klien
│   │   │
│   │   └── Middleware/           # Middleware kustom
│   │       ├── CheckRole.php     # Otorisasi berbasis peran
│   │       └── LogActivity.php   # Pencatatan aktivitas
│   │
│   ├── Models/                   # 15 model Eloquent
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/                    # Berkas bootstrap aplikasi
├── config/                       # Konfigurasi aplikasi
├── database/
│   ├── migrations/               # 17 berkas migrasi
│   ├── seeders/                  # 4 berkas seeder
│   └── factories/                # UserFactory
│
├── docs/                         # Dokumentasi laporan (7 berkas)
│
├── resources/views/              # Template Blade (35+ berkas)
│   ├── layouts/                  # app.blade.php, auth.blade.php
│   ├── components/               # sidebar, topbar, nav-item
│   ├── auth/                     # login.blade.php
│   ├── admin/                    # dashboard, projects, jobs, invoices, dll
│   ├── crew/                     # dashboard, jobs
│   └── client/                   # dashboard, invoices, portfolios
│
├── routes/                       # Definisi route
│   ├── web.php                   # Route utama
│   ├── auth.php                  # Route autentikasi
│   ├── admin.php                 # Route administrator
│   ├── crew.php                  # Route kru
│   └── client.php                # Route klien
│
├── storage/                      # Berkas sesi, log, cache
├── tests/                        # Pengujian unit
│   └── Feature/Admin/            # 3 berkas pengujian (42 kasus)
│
├── public/                       # Berkas publik (index.php, aset)
│   └── images/logo.png           # Logo perusahaan
│
├── .env                          # Konfigurasi lingkungan
├── composer.json                 # Dependensi PHP
├── package.json                  # Dependensi Node.js
└── vite.config.js                # Konfigurasi Vite
```

---

## 2. PANDUAN INSTALASI

### 2.1 Prasyarat

| Perangkat Lunak | Versi Minimal | Fungsi |
|-----------------|---------------|--------|
| PHP | 8.2.12 | Bahasa pemrograman |
| MySQL | 8.0 | Sistem manajemen basis data |
| Composer | 2.x | Manajer dependensi PHP |
| Node.js | 20.x | Lingkungan eksekusi JavaScript |
| NPM | 10.x | Manajer paket Node.js |

### 2.2 Langkah Instalasi

```bash
# 1. Menyalin proyek ke direktori server web
#    Contoh: C:\xampp\htdocs\storimax

# 2. Menginstal dependensi PHP
composer install

# 3. Menyalin berkas konfigurasi lingkungan
copy .env.example .env
# atau: cp .env.example .env (Linux/macOS)

# 4. Membangkitkan kunci aplikasi
php artisan key:generate

# 5. Konfigurasi basis data pada berkas .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=storimax
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Membuat basis data di MySQL
#    mysql -u root -p -e "CREATE DATABASE storimax;"

# 7. Menjalankan migrasi dan seeder
php artisan migrate:fresh --seed

# 8. Menginstal dan membangun aset frontend
npm install
npm run build
# Untuk pengembangan:
npm run dev

# 9. Membuat tautan simbolis penyimpanan
php artisan storage:link
```

### 2.3 Konfigurasi Lingkungan

Berkas `.env` berisi konfigurasi lingkungan yang diperlukan oleh sistem:

```ini
APP_NAME=Storimax
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/storimax/public

# Driver sesi: gunakan "file" (bukan "database")
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Konfigurasi basis data
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=storimax
DB_USERNAME=root
DB_PASSWORD=
```

### 2.4 Konfigurasi Server Web

Beberapa pengaturan yang perlu diperhatikan pada lingkungan XAMPP:

1. **Mengaktifkan Apache dan MySQL** melalui XAMPP Control Panel.
2. **Ekstensi PHP yang diperlukan:** openssl, pdo_mysql, mbstring, xml, bcmath, json, fileinfo.
3. **Ekstensi GD:** Hapus komentar `;extension=gd` pada berkas `C:\xampp\php\php.ini` jika diperlukan untuk fungsi grafis selain PDF.
4. **DocumentRoot:** Secara default mengarah ke `C:\xampp\htdocs`. Sistem diakses melalui `http://localhost/storimax/public`.

---

## 3. ANTARMUKA ROUTE

### 3.1 Route Autentikasi

| Metode HTTP | URL | Nama Route | Middleware |
|-------------|-----|------------|-----------|
| GET | `/login` | `login` | guest |
| POST | `/login` | `login.post` | guest |
| POST | `/logout` | `logout` | auth |

### 3.2 Route Administrator

**Grup:** `/admin/*`
**Middleware:** `auth`, `role:admin,atasan`, `log.activity`

| Metode HTTP | URL | Nama Route |
|-------------|-----|------------|
| GET | `/admin/dashboard` | `admin.dashboard` |
| GET, POST | `/admin/users` | `admin.users.index`, `admin.users.store` |
| GET | `/admin/users/{user}` | `admin.users.show` |
| GET, PUT | `/admin/users/{user}/edit` | `admin.users.edit`, `admin.users.update` |
| DELETE | `/admin/users/{user}` | `admin.users.destroy` |
| PATCH | `/admin/users/{user}/toggle-active` | `admin.users.toggle-active` |
| GET, POST | `/admin/departments` | `admin.departments.index`, `admin.departments.store` |
| GET, POST | `/admin/clients` | `admin.clients.index`, `admin.clients.store` |
| GET, POST | `/admin/projects` | `admin.projects.index`, `admin.projects.store` |
| PUT, DELETE | `/admin/projects/{project}` | `admin.projects.update`, `admin.projects.destroy` |
| PATCH | `/admin/projects/{project}/status` | `admin.projects.update-status` |
| POST | `/admin/projects/{project}/teams` | `admin.projects.teams.store` |
| DELETE | `/admin/projects/{project}/teams/{team}` | `admin.projects.teams.destroy` |
| POST | `/admin/projects/{project}/teams/{team}/members` | `admin.projects.teams.members.store` |
| DELETE | `/admin/.../members/{member}` | `admin.projects.teams.members.destroy` |
| GET | `/admin/jobs` | `admin.jobs.index` |
| GET, POST | `/admin/projects/{project}/jobs/create` | `admin.projects.jobs.create`, `admin.projects.jobs.store` |
| GET, PUT, DELETE | `/admin/jobs/{job}` | `admin.jobs.show`, `admin.jobs.update`, `admin.jobs.destroy` |
| PATCH | `/admin/jobs/{job}/status` | `admin.jobs.update-status` |
| GET, POST | `/admin/invoices` | `admin.invoices.index`, `admin.invoices.store` |
| PATCH | `/admin/invoices/{invoice}/status` | `admin.invoices.update-status` |
| GET | `/admin/invoices/{invoice}/pdf` | `admin.invoices.pdf` |
| GET, POST | `/admin/portfolios` | `admin.portfolios.index`, `admin.portfolios.store` |
| PATCH | `/admin/portfolios/{portfolio}/toggle-public` | `admin.portfolios.toggle-public` |
| GET | `/admin/reports` | `admin.reports.index` |
| GET | `/admin/reports/crew/{user}` | `admin.reports.crew` |
| GET | `/admin/reports/export/pdf` | `admin.reports.export.pdf` |
| GET | `/admin/reports/export/excel` | `admin.reports.export.excel` |

### 3.3 Route Kru

**Grup:** `/crew/*`
**Middleware:** `auth`, `role:crew`, `log.activity`

| Metode HTTP | URL | Nama Route |
|-------------|-----|------------|
| GET | `/crew/dashboard` | `crew.dashboard` |
| GET | `/crew/jobs` | `crew.jobs.index` |
| GET | `/crew/jobs/{job}` | `crew.jobs.show` |
| PATCH | `/crew/jobs/{job}/status` | `crew.jobs.update-status` |
| POST | `/crew/jobs/{job}/attachments` | `crew.jobs.attachments.upload` |
| DELETE | `/crew/jobs/{job}/attachments/{attachment}` | `crew.jobs.attachments.delete` |

### 3.4 Route Klien

**Grup:** `/client/*`
**Middleware:** `auth`, `role:client`

| Metode HTTP | URL | Nama Route |
|-------------|-----|------------|
| GET | `/client/dashboard` | `client.dashboard` |
| GET | `/client/invoices` | `client.invoices.index` |
| GET | `/client/invoices/{invoice}` | `client.invoices.show` |
| GET | `/client/portfolios` | `client.portfolios.index` |
| GET | `/client/portfolios/{portfolio}` | `client.portfolios.show` |

---

## 4. STRUKTUR BASIS DATA

### 4.1 Diagram Relasi Entitas

```
departments
    │
    ├──< users
    │       │
    │       ├──< clients (1:1)
    │       │
    │       ├──< projects (created_by)
    │       │
    │       ├──< project_team_members
    │       │
    │       ├──< jobs (assigned_to, created_by)
    │       │
    │       ├──< job_logs
    │       │
    │       ├──< invoices (created_by)
    │       │
    │       ├──< notifications
    │       │
    │       └──< activity_logs
    │
    └──< project_teams (pic_user_id)

clients
    │
    ├──< projects
    └──< invoices

projects
    │
    ├──< project_teams
    │       │
    │       └──< project_team_members
    │
    ├──< jobs
    │       │
    │       ├──< job_logs
    │       └──< job_attachments
    │
    └──< invoices
            │
            └──< invoice_items

portfolios
    │
    └──< portfolio_tags
```

### 4.2 Spesifikasi Tabel Utama

#### departments
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| name | VARCHAR(100) | Nama departemen |
| slug | VARCHAR(100) | Slug unik untuk URL |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu pembaruan |

#### users
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| department_id | UUID (FK) | Kunci asing ke departments (nullable) |
| name | VARCHAR(150) | Nama lengkap |
| email | VARCHAR(150) | Surel (unik) |
| password | VARCHAR | Kata sandi (bcrypt) |
| role | ENUM | admin, atasan, crew, client |
| phone | VARCHAR(20) | Nomor telepon (nullable) |
| is_active | BOOLEAN | Status aktif (default true) |
| deleted_at | TIMESTAMP | Penghapusan lunak (nullable) |

#### clients
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| user_id | UUID (FK) | Kunci asing ke users |
| company_name | VARCHAR(200) | Nama perusahaan |
| contact_name | VARCHAR(150) | Nama kontak |
| phone | VARCHAR(20) | Nomor telepon |
| address | TEXT | Alamat |
| deleted_at | TIMESTAMP | Penghapusan lunak (nullable) |

#### projects
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| client_id | UUID (FK) | Kunci asing ke clients |
| created_by | UUID (FK) | Kunci asing ke users |
| name | VARCHAR(200) | Nama proyek |
| code | VARCHAR(20) | Kode proyek (unik, STX-YYYY-NNN) |
| status | ENUM | draft, active, review, done, archived |
| priority | ENUM | low, medium, high, urgent |
| deleted_at | TIMESTAMP | Penghapusan lunak (nullable) |

#### jobs
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| project_id | UUID (FK) | Kunci asing ke projects |
| assigned_to | UUID (FK) | Kunci asing ke users (nullable) |
| title | VARCHAR(200) | Judul pekerjaan |
| status | ENUM | todo, inprogress, review, done |
| priority | ENUM | low, medium, high, urgent |
| started_at | TIMESTAMP | Diisi otomatis saat inprogress |
| completed_at | TIMESTAMP | Diisi otomatis saat done |
| deleted_at | TIMESTAMP | Penghapusan lunak (nullable) |

#### invoices
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| project_id | UUID (FK) | Kunci asing ke projects |
| client_id | UUID (FK) | Kunci asing ke clients |
| invoice_number | VARCHAR(50) | Nomor faktur (unik, INV/STX/YYYY/NNN) |
| subtotal | DECIMAL(15,2) | Total sebelum PPh |
| pph_rate | DECIMAL(5,2) | Persentase PPh (default 2%) |
| pph_amount | DECIMAL(15,2) | Nominal PPh |
| total | DECIMAL(15,2) | Total setelah PPh |
| dp_amount | DECIMAL(15,2) | Jumlah uang muka |
| dp_paid | DECIMAL(15,2) | Uang muka yang telah dibayar |
| remaining | DECIMAL(15,2) | Sisa tagihan |
| status | ENUM | draft, sent, dp_paid, paid, overdue |
| sent_at | TIMESTAMP | Waktu pengiriman (nullable) |
| dp_paid_at | TIMESTAMP | Waktu pembayaran DP (nullable) |
| paid_at | TIMESTAMP | Waktu pelunasan (nullable) |
| deleted_at | TIMESTAMP | Penghapusan lunak (nullable) |

#### project_teams
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| project_id | UUID (FK) | Kunci asing ke projects |
| team_name | VARCHAR(100) | Nama tim |
| pic_user_id | UUID (FK) | Kunci asing ke users (PIC, nullable) |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu pembaruan |

#### project_team_members
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| project_team_id | UUID (FK) | Kunci asing ke project_teams |
| user_id | UUID (FK) | Kunci asing ke users |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu pembaruan |

#### job_logs
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | UUID | Kunci primer |
| job_id | UUID (FK) | Kunci asing ke jobs |
| user_id | UUID (FK) | Kunci asing ke users (pengubah) |
| old_status | VARCHAR(20) | Status sebelum perubahan |
| new_status | VARCHAR(20) | Status setelah perubahan |
| note | TEXT | Catatan perubahan (nullable) |
| created_at | TIMESTAMP | Waktu pencatatan |

---

## 5. REFERENSI ENUM

### UserRole
```
ADMIN   → 'admin'    — Akses penuh seluruh modul
ATASAN  → 'atasan'   — Akses baca dan persetujuan
CREW    → 'crew'     — Akses pekerjaan sendiri
CLIENT  → 'client'   — Akses faktur dan portofolio
```

### ProjectStatus
```
DRAFT    → 'draft'     — Proyek baru dibuat
ACTIVE   → 'active'    — Proyek sedang dikerjakan
REVIEW   → 'review'    — Proyek dalam peninjauan
DONE     → 'done'      — Proyek selesai
ARCHIVED → 'archived'  — Proyek diarsipkan
```

### JobStatus
```
TODO       → 'todo'        — Pekerjaan belum dimulai
INPROGRESS → 'inprogress'  — Pekerjaan sedang dikerjakan
REVIEW     → 'review'      — Pekerjaan dalam peninjauan
DONE       → 'done'        — Pekerjaan selesai
```

### InvoiceStatus
```
DRAFT   → 'draft'     — Faktur baru dibuat
SENT    → 'sent'      — Faktur telah dikirim ke klien
DP_PAID → 'dp_paid'   — Uang muka telah dibayar
PAID    → 'paid'      — Faktur lunas
OVERDUE → 'overdue'   — Faktur melewati jatuh tempo
```

### JobPriority
```
LOW    → 'low'     — Prioritas rendah
MEDIUM → 'medium'  — Prioritas sedang
HIGH   → 'high'    — Prioritas tinggi
URGENT → 'urgent'  — Prioritas darurat
```

---

## 6. MIDDLEWARE

### 6.1 CheckRole

Middleware `CheckRole` berfungsi untuk memvalidasi peran pengguna sebelum mengakses suatu halaman. Middleware ini menerima parameter berupa daftar peran yang diizinkan.

```php
// Contoh penggunaan pada route:
Route::middleware(['role:admin,atasan'])->group(function () {
    // Hanya admin dan atasan yang dapat mengakses
});

Route::middleware(['role:crew'])->group(function () {
    // Hanya kru yang dapat mengakses
});

Route::middleware(['role:client'])->group(function () {
    // Hanya klien yang dapat mengakses
});

// Respons yang dikembalikan jika peran tidak sesuai:
// HTTP 403 Forbidden — "Kamu tidak memiliki akses ke halaman ini."
```

### 6.2 LogActivity

Middleware `LogActivity` berfungsi untuk mencatat aktivitas pengguna ke dalam tabel `activity_logs`. Middleware ini mencatat informasi seperti pengguna, _route_ yang diakses, metode HTTP, alamat IP, dan _user agent_.

```php
// Penggunaan:
Route::middleware(['log.activity'])->group(function () {
    // Semua aktivitas di grup ini akan dicatat
});
```

---

## 7. SISTEM NOTIFIKASI

### 7.1 Fungsi Pembantu (Helper)

Sistem notifikasi menggunakan kelas `NotificationHelper` yang menyediakan tiga metode:

1. **`notify()`** — Mengirim notifikasi kepada satu pengguna.
2. **`notifyMany()`** — Mengirim notifikasi kepada beberapa pengguna sekaligus.
3. **`notifyAdmins()`** — Mengirim notifikasi kepada seluruh administrator dan atasan.

### 7.2 Pemicu Notifikasi Otomatis

| Peristiwa | Tipe Notifikasi | Penerima |
|-----------|----------------|----------|
| Pekerjaan ditugaskan ke kru | `job_assigned` | Kru yang ditugaskan |
| Status pekerjaan diubah oleh admin | `job_status_*` | Kru yang ditugaskan |
| Pekerjaan mencapai status review/done oleh kru | `job_review`, `job_done` | Seluruh admin dan atasan |
| Faktur dikirim ke klien | `invoice_sent` | Klien |
| Faktur dp_paid/paid | `invoice_dp_paid`, `invoice_paid` | Seluruh admin dan atasan |
| Portofolio diterbitkan | `portfolio_published` | Seluruh admin dan atasan |

---

## DAFTAR PUSTAKA

1. Laravel. (2026). _Laravel 12 Documentation_. Retrieved from https://laravel.com/docs/12
2. Tailwind Labs. (2026). _Tailwind CSS v4 Documentation_. Retrieved from https://tailwindcss.com/docs
3. Alpine.js. (2026). _Alpine.js Documentation_. Retrieved from https://alpinejs.dev/docs
4. Connolly, T., & Begg, C. (2015). _Database Systems: A Practical Approach to Design, Implementation, and Management_ (6th ed.). Pearson.
5. Fowler, M. (2002). _Patterns of Enterprise Application Architecture_. Addison-Wesley.

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
