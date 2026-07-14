# 📋 PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| **Dokumen** | Product Requirements Document |
|-------------|------------------------------|
| **Sistem** | Storimax Agency Admin System |
| **Perusahaan** | PT Jalur Tengah Kreasindo |
| **Tagline** | Story in Motion. Maxed to Perfection. |
| **Versi** | 1.0 |
| **Tanggal** | 13 Juli 2026 |
| **Framework** | Laravel 12 (PHP 8.2.12) |
| **Database** | MySQL 8 via XAMPP |
| **Frontend** | Blade + Tailwind CSS v4 + Alpine.js |

---

## DAFTAR ISI

1. [Executive Summary](#1-executive-summary)
2. [Tujuan dan Lingkup](#2-tujuan-dan-lingkup)
3. [Aktor / Pengguna Sistem](#3-aktor--pengguna-sistem)
4. [Fungsional Requirements](#4-fungsional-requirements)
5. [Non-Fungsional Requirements](#5-non-fungsional-requirements)
6. [Role Access Matrix](#6-role-access-matrix)
7. [Use Case Diagram](#7-use-case-diagram)
8. [Entity Relationship Diagram (ERD)](#8-entity-relationship-diagram-erd)
9. [MVC Architecture](#9-mvc-architecture)
10. [Wireframe / Struktur Halaman](#10-wireframe--struktur-halaman)
11. [Status Flow](#11-status-flow)
12. [Route Structure](#12-route-structure)
13. [Tech Stack Detail](#13-tech-stack-detail)
14. [Notifikasi Sistem](#14-notifikasi-sistem)
15. [Kendala dan Catatan Teknis](#15-kendala-dan-catatan-teknis)

---

## 1. EXECUTIVE SUMMARY

Storimax Agency Admin System adalah sistem informasi manajemen berbasis web untuk agensi kreatif PT Jalur Tengah Kreasindo. Sistem ini menangani seluruh siklus kerja agensi: dari pendaftaran klien, pembuatan proyek, penugasan pekerjaan (jobs) ke kru, pelacakan progress, pembuatan invoice, hingga publikasi portofolio.

Sistem mendukung **4 peran pengguna**: Admin, Atasan, Crew, dan Client — masing-masing dengan hak akses berbeda. Database menggunakan **soft-delete** di semua entitas utama untuk keamanan data, dan **UUID** sebagai primary key untuk menghindari enumerasi data.

---

## 2. TUJUAN DAN LINGKUP

### 2.1 Tujuan
1. Digitalisasi seluruh alur kerja agensi kreatif
2. Monitoring real-time progress proyek dan job
3. Manajemen invoice dan pembayaran terintegrasi
4. Portal klien untuk melihat invoice dan portofolio
5. Laporan performa crew berbasis data

### 2.2 Lingkup
- ✅ Manajemen User (Admin & Crew internal)
- ✅ Manajemen Client (data klien + akun login)
- ✅ Manajemen Project (CRUD + status flow)
- ✅ Manajemen Job / Pekerjaan (CRUD + status flow + timeline)
- ✅ Manajemen Invoice (CRUD + status flow + PDF + kalkulasi otomatis)
- ✅ Manajemen Portofolio (publikasi + visibilitas client)
- ✅ Dashboard Admin & Atasan (statistik + progress)
- ✅ Dashboard Crew (job sendiri + update progress)
- ✅ Dashboard Client (invoice + portofolio)
- ✅ Laporan Performa Crew (PDF + CSV export)
- ✅ Sistem Notifikasi In-App
- ✅ Activity Log / Audit Trail
- ✅ Profil Pengguna (avatar, edit identitas, ganti password)
- ❌ Department (telah dihapus dari sistem)

---

## 3. AKTOR / PENGGUNA SISTEM

| **Aktor** | **Role** | **Deskripsi** |
|-----------|----------|---------------|
| **Admin** | `admin` | Full akses seluruh fitur. Mengelola user, client, project, job, invoice, portofolio, laporan. |
| **Atasan** | `atasan` | Read-only. Hanya bisa melihat data (GET), tidak bisa membuat/mengubah/menghapus apa pun. Tidak bisa akses manajemen Users & Clients. |
| **Crew** | `crew` | Akses job yang ditugaskan ke dirinya sendiri. Bisa update progress status job. Melihat dashboard pribadi. |
| **Client** | `client` | Melihat invoice miliknya, melihat portofolio publik. Memiliki akun login sendiri. |

### 3.1 Akun Demo

| **Email** | **Password** | **Role** |
|-----------|-------------|----------|
| admin@storimax.id | password | Admin |
| atasan@storimax.id | password | Atasan |
| budi@storimax.id | password | Crew |
| sari@storimax.id | password | Crew |
| andi@storimax.id | password | Crew |
| rizky@gmail.com | password | Client |
| dewi@gmail.com | password | Client |

---

## 4. FUNGSIONAL REQUIREMENTS

### 4.1 Modul Autentikasi (FR-01)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-01.1 | Pengguna dapat login dengan email dan password |
| FR-01.2 | Sistem memvalidasi kredensial dan mengarahkan ke dashboard sesuai role |
| FR-01.3 | Sistem menolak login jika akun tidak aktif (`is_active = false`) |
| FR-01.4 | Pengguna dapat logout |
| FR-01.5 | Opsi "Remember Me" untuk sesi lebih lama |

### 4.2 Modul Manajemen User (FR-02) — Admin Only
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-02.1 | Admin dapat melihat daftar semua user (non-client) |
| FR-02.2 | Admin dapat menambah user baru (role: admin, atasan, crew) |
| FR-02.3 | Admin dapat mengedit data user (nama, email, role, telepon, password) |
| FR-02.4 | Admin dapat menghapus user (soft-delete), tidak bisa hapus akun sendiri |
| FR-02.5 | Admin dapat mengaktifkan/menonaktifkan user |

### 4.3 Modul Manajemen Client (FR-03) — Admin Only
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-03.1 | Admin dapat melihat daftar semua client |
| FR-03.2 | Admin dapat menambah client baru (otomatis buat akun login) |
| FR-03.3 | Admin dapat mengedit data client (contact, perusahaan, kontak, instagram, alamat) |
| FR-03.4 | Admin dapat menghapus client (soft-delete, hanya jika tidak punya project) |

### 4.4 Modul Manajemen Project (FR-04)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-04.1 | Admin dapat melihat daftar semua project |
| FR-04.2 | Admin dapat membuat project baru dengan kode otomatis (STX-YYYY-NNN) |
| FR-04.3 | Admin dapat melihat detail project (info client, jobs, teams) |
| FR-04.4 | Admin dapat mengedit data project |
| FR-04.5 | Admin dapat menghapus project (cascade soft-delete ke jobs, invoices, portfolios) |
| FR-04.6 | Admin dapat mengubah status project: draft → active → review → done → archived |
| FR-04.7 | Atasan dapat melihat project (read-only) |

### 4.5 Modul Manajemen Job / Pekerjaan (FR-05)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-05.1 | Admin dapat melihat daftar semua job |
| FR-05.2 | Admin dapat membuat job baru di dalam project |
| FR-05.3 | Admin dapat mengedit data job (title, assignee, priority, deadline, notes, gdrive_link) |
| FR-05.4 | Admin dapat menghapus job (soft-delete) |
| FR-05.5 | Admin dapat mengubah status job: todo → inprogress → review → done |
| FR-05.6 | Admin & Crew dapat menambah catatan saat update status |
| FR-05.7 | Sistem otomatis mencatat `started_at` saat status → inprogress |
| FR-05.8 | Sistem otomatis mencatat `completed_at` saat status → done |
| FR-05.9 | Setiap perubahan status tercatat di tabel `job_logs` (audit trail) |
| FR-05.10 | Crew hanya bisa melihat & update job miliknya sendiri |
| FR-05.11 | Crew bisa update status job dari dashboard crew |

### 4.6 Modul Manajemen Invoice (FR-06)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-06.1 | Admin dapat melihat daftar semua invoice |
| FR-06.2 | Admin dapat membuat invoice baru dengan nomor otomatis (INV/STX/YYYY/NNN) |
| FR-06.3 | Admin dapat menambahkan item invoice dinamis (layanan, harga, diskon) |
| FR-06.4 | Sistem otomatis menghitung: subtotal → PPH% → total → DP → remaining |
| FR-06.5 | Admin dapat mengubah status invoice: draft → sent → dp_paid → paid / overdue |
| FR-06.6 | Sistem otomatis mencatat timestamp saat perubahan status (sent_at, dp_paid_at, paid_at) |
| FR-06.7 | Admin dapat mendownload invoice sebagai PDF (via DOMPDF) |
| FR-06.8 | Client dapat melihat invoice miliknya sendiri di portal client |
| FR-06.9 | Invoice tidak bisa diedit setelah dibuat (by design — dokumen final) |

### 4.7 Modul Manajemen Portofolio (FR-07)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-07.1 | Admin dapat melihat daftar semua portofolio |
| FR-07.2 | Admin dapat membuat portofolio baru (terkait project, thumbnail, kategori, tags) |
| FR-07.3 | Admin dapat mengedit portofolio |
| FR-07.4 | Admin dapat menghapus portofolio (soft-delete) |
| FR-07.5 | Admin dapat toggle publikasi portofolio (public/private) |
| FR-07.6 | Client dapat melihat portofolio publik di portal client |

### 4.8 Modul Laporan (FR-08)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-08.1 | Admin & Atasan dapat melihat laporan ringkasan (total crew, project aktif, job completion rate, pending invoices) |
| FR-08.2 | Admin & Atasan dapat melihat detail performa per crew |
| FR-08.3 | Admin & Atasan dapat mengexport laporan sebagai PDF |
| FR-08.4 | Admin & Atasan dapat mengexport laporan sebagai CSV |

### 4.9 Modul Dashboard (FR-09)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-09.1 | Admin & Atasan melihat dashboard dengan statistik global |
| FR-09.2 | Dashboard Admin menampilkan project terbaru dengan progress bar |
| FR-09.3 | Crew melihat dashboard pribadi (total job, active, done, recent jobs) |
| FR-09.4 | Client melihat dashboard pribadi (total invoice, total paid, project count, portofolio count) |

### 4.10 Modul Notifikasi (FR-10)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-10.1 | Sistem mengirim notifikasi in-app pada event tertentu |
| FR-10.2 | Topbar menampilkan dropdown 8 notifikasi terbaru dengan badge unread count |
| FR-10.3 | Klik notifikasi → mark as read + redirect ke halaman terkait |
| FR-10.4 | Halaman `/notifications` menampilkan semua notifikasi dengan pagination |
| FR-10.5 | User dapat menandai semua notifikasi sebagai dibaca |
| FR-10.6 | User dapat menghapus notifikasi |

### 4.11 Modul Profil (FR-11)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-11.1 | Semua user dapat melihat profil sendiri |
| FR-11.2 | User dapat mengupload foto profil (avatar) |
| FR-11.3 | User dapat mengedit nama, email, telepon |
| FR-11.4 | User dapat mengganti password |

### 4.12 Modul Activity Log (FR-12)
| **ID** | **Deskripsi** |
|--------|---------------|
| FR-12.1 | Middleware `LogActivity` mencatat setiap aksi mutasi (POST/PUT/PATCH/DELETE) |
| FR-12.2 | Log mencatat: user, module, action, payload, IP, user agent |
| FR-12.3 | Payload dibatasi maksimal 500 karakter per value untuk efisiensi |

---

## 5. NON-FUNGSIONAL REQUIREMENTS

| **ID** | **Kategori** | **Deskripsi** |
|--------|-------------|---------------|
| NFR-01 | **Keamanan** | Semua password di-hash menggunakan bcrypt via Laravel |
| NFR-02 | **Keamanan** | Middleware role-based: `CheckRole` memvalidasi akses per route |
| NFR-03 | **Keamanan** | UUID sebagai primary key (mencegah enumerasi ID) |
| NFR-04 | **Keamanan** | CSRF protection di semua form |
| NFR-05 | **Keamanan** | Soft-delete di semua entitas utama (data tidak benar-benar hilang) |
| NFR-06 | **Kinerja** | Halaman dashboard memuat < 3 detik untuk 100+ project |
| NFR-07 | **Kinerja** | PDF invoice menggunakan DOMPDF (tanpa ekstensi GD) |
| NFR-08 | **Usability** | UI clean minimalis, referensi Linear/Notion |
| NFR-09 | **Usability** | Font Inter (400-800) via Google Fonts |
| NFR-10 | **Usability** | Badge warna untuk status & role (konsisten di seluruh sistem) |
| NFR-11 | **Reliabilitas** | Session driver menggunakan file (bukan database — issue MySQL XAMPP) |
| NFR-12 | **Reliabilitas** | Cascade delete via model events (bukan foreign key cascade) |
| NFR-13 | **Maintainability** | Controller langsung query Model (belum pakai Repository/Service pattern) |
| NFR-14 | **Maintainability** | PHP Enum untuk semua status dan role |
| NFR-15 | **Kompatibilitas** | Laravel 12 + PHP 8.2.12 + MySQL 8 |
| NFR-16 | **Kompatibilitas** | Tailwind CSS v4 (tanpa tailwind.config.js — config via @import CSS) |

---

## 6. ROLE ACCESS MATRIX

### 6.1 CRUD Matrix

| **Modul** | **Admin** | **Atasan** | **Crew** | **Client** |
|-----------|:---------:|:----------:|:--------:|:----------:|
| **Dashboard** | C✅ R✅ U✅ D✅ | R✅ | R✅ | R✅ |
| **Users** | CRUD | ❌ | ❌ | ❌ |
| **Clients** | CRUD | ❌ | ❌ | ❌ (lihat data sendiri) |
| **Projects** | CRUD | R | ❌ | ❌ (lihat via invoice) |
| **Jobs** | CRUD + update status | R | R + update status (milik sendiri) | ❌ |
| **Invoices** | CRUD + update status + PDF | R | ❌ | R (milik sendiri) |
| **Portfolios** | CRUD + toggle public | R | ❌ | R (public only) |
| **Reports** | R + export PDF/CSV | R + export PDF/CSV | ❌ | ❌ |
| **Notifications** | R + mark read + delete | R + mark read + delete | R + mark read + delete | R + mark read + delete |
| **Profile** | Edit sendiri | Edit sendiri | Edit sendiri | Edit sendiri |
| **Activity Log** | Otomatis (middleware) | Otomatis | Otomatis | Otomatis |

**Keterangan:**
- ✅ = Full akses
- R = Read-only (GET)
- ❌ = Tidak punya akses

### 6.2 Halaman per Role

#### Admin
Dashboard → Users → Clients → Projects → Jobs → Invoices → Portfolios → Reports → Notifications → Profile

#### Atasan
Dashboard → Projects → Jobs → Invoices → Portfolios → Reports → Notifications → Profile

#### Crew
Dashboard → My Jobs → Notifications → Profile

#### Client
Dashboard → Invoices → Portfolios → Notifications → Profile

---

## 7. USE CASE DIAGRAM

### 7.1 Aktor dan Use Case

#### Aktor: Admin
```
┌──────────────────────────────────────────────────────────────┐
│                        ADMIN                                  │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │ Login / Logout                                           │ │
│  │ Kelola Users (CRUD + toggle active)                      │ │
│  │ Kelola Clients (CRUD + buat akun otomatis)               │ │
│  │ Kelola Projects (CRUD + update status)                   │ │
│  │ Kelola Jobs (CRUD + update status + assign crew)         │ │
│  │ Kelola Invoices (CRUD + update status + download PDF)    │ │
│  │ Kelola Portfolios (CRUD + toggle public)                 │ │
│  │ Lihat & Export Laporan (PDF, CSV)                        │ │
│  │ Lihat & Kelola Notifikasi                                │ │
│  │ Kelola Profil Sendiri                                    │ │
│  └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

#### Aktor: Atasan
```
┌──────────────────────────────────────────────────────────────┐
│                        ATASAN                                 │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │ Login / Logout                                           │ │
│  │ Lihat Dashboard (statistik global)                       │ │
│  │ Lihat Projects (read-only)                               │ │
│  │ Lihat Jobs (read-only)                                   │ │
│  │ Lihat Invoices (read-only)                               │ │
│  │ Lihat Portfolios (read-only)                             │ │
│  │ Lihat & Export Laporan (PDF, CSV)                        │ │
│  │ Lihat & Kelola Notifikasi                                │ │
│  │ Kelola Profil Sendiri                                    │ │
│  └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

#### Aktor: Crew
```
┌──────────────────────────────────────────────────────────────┐
│                        CREW                                   │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │ Login / Logout                                           │ │
│  │ Lihat Dashboard Pribadi                                  │ │
│  │ Lihat Jobs Milik Sendiri                                 │ │
│  │ Update Status Job (todo → inprogress → review → done)    │ │
│  │ Lihat & Kelola Notifikasi                                │ │
│  │ Kelola Profil Sendiri                                    │ │
│  └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

#### Aktor: Client
```
┌──────────────────────────────────────────────────────────────┐
│                        CLIENT                                 │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │ Login / Logout                                           │ │
│  │ Lihat Dashboard Pribadi                                  │ │
│  │ Lihat Invoice Milik Sendiri                              │ │
│  │ Lihat Portofolio Publik                                  │ │
│  │ Lihat & Kelola Notifikasi                                │ │
│  │ Kelola Profil Sendiri                                    │ │
│  └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 7.2 Use Case Spesifikasi

#### UC-01: Login
| **Item** | **Deskripsi** |
|----------|---------------|
| **Aktor** | Semua role |
| **Pre-condition** | User memiliki akun aktif |
| **Post-condition** | User diarahkan ke dashboard sesuai role |
| **Skenario** | 1. User membuka /login<br>2. Memasukkan email + password<br>3. Sistem validasi<br>4. Jika akun tidak aktif → tolak<br>5. Redirect ke dashboard sesuai role |
| **Exceptions** | Email/password salah → kembali ke login dengan pesan error |

#### UC-02: Buat Project
| **Item** | **Deskripsi** |
|----------|---------------|
| **Aktor** | Admin |
| **Pre-condition** | Client sudah terdaftar di sistem |
| **Post-condition** | Project baru dengan kode STX-YYYY-NNN tersimpan |
| **Skenario** | 1. Admin klik "Buat Project"<br>2. Pilih client + isi form<br>3. Sistem generate kode otomatis<br>4. Project tersimpan dengan status DRAFT |
| **Exceptions** | Client tidak dipilih → validasi gagal |

#### UC-03: Update Status Job
| **Item** | **Deskripsi** |
|----------|---------------|
| **Aktor** | Admin / Crew |
| **Pre-condition** | Job sudah ada dan ditugaskan ke crew |
| **Post-condition** | Status berubah, tercatat di job_logs, notifikasi terkirim |
| **Skenario** | 1. Admin/Crew klik tombol status<br>2. Pilih status baru + catatan opsional<br>3. Sistem update status<br>4. Jika → inprogress: set started_at<br>5. Jika → done: set completed_at<br>6. Simpan log perubahan<br>7. Kirim notifikasi ke pihak terkait |
| **Transitions** | todo → inprogress, todo → review, todo → done<br>inprogress → review, inprogress → done<br>review → done, review → inprogress<br>done → inprogress |

#### UC-04: Kirim Invoice ke Client
| **Item** | **Deskripsi** |
|----------|---------------|
| **Aktor** | Admin |
| **Pre-condition** | Invoice sudah dibuat dengan status DRAFT |
| **Post-condition** | Status → SENT, notifikasi ke client |
| **Skenario** | 1. Admin ubah status invoice ke "sent"<br>2. Sistem set sent_at = now()<br>3. Sistem kirim notifikasi ke client<br>4. Client bisa lihat invoice di portal |

#### UC-05: Hapus Project (Cascade)
| **Item** | **Deskripsi** |
|----------|---------------|
| **Aktor** | Admin |
| **Pre-condition** | Project ada di sistem |
| **Post-condition** | Project + jobs + invoices + portfolios ikut soft-delete |
| **Skenario** | 1. Admin klik "Hapus Project"<br>2. Konfirmasi penghapusan<br>3. Sistem cascade soft-delete:<br>   - Jobs → attachments + logs ikut hapus<br>   - Invoices → items ikut hapus<br>   - Portfolios ikut hapus<br>4. Redirect ke daftar project |

---

## 8. ENTITY RELATIONSHIP DIAGRAM (ERD)

### 8.1 Struktur Tabel

```
┌─────────────────────────────────────────────────────────────────────┐
│                              TABEL DI DATABASE                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────┐                                                        │
│  │  users   │─────────┐          ┌──────────────────┐               │
│  │ (UUID)   │         │          │  project_teams   │               │
│  └────┬─────┘         │          │  (UUID)          │               │
│       │               │          └───────┬──────────┘               │
│       │ 1:1           │ 1:N              │ 1:N                      │
│       ↓               ↓                  ↓                          │
│  ┌──────────┐   ┌──────────┐       ┌──────────────────┐             │
│  │ clients  │──→│ projects │──→    │ project_team_    │             │
│  │ (UUID)   │   │ (UUID)   │       │ members (UUID)   │             │
│  └──────────┘   └────┬─────┘       └──────────────────┘             │
│       │               │                                              │
│       │ 1:N           │ 1:N         ┌──────────────────┐             │
│       │               ├────────────→│     jobs         │             │
│       │               │             │    (UUID)        │             │
│       │               │             └────────┬─────────┘             │
│       │               │                      │ 1:N                  │
│       │               │              ┌───────┴─────────┐             │
│       │               │              │  job_logs       │             │
│       │               │              │  (UUID)         │             │
│       │               │              └─────────────────┘             │
│       │               │              ┌─────────────────┐             │
│       │               │              │ job_attachments │             │
│       │               │              │ (UUID)          │             │
│       │               │              └─────────────────┘             │
│       │               │                                              │
│       │               │ 1:N         ┌──────────────────┐             │
│       │               ├────────────→│    invoices      │             │
│       │               │             │    (UUID)        │             │
│       │               │             └────────┬─────────┘             │
│       │               │                      │ 1:N                  │
│       │               │              ┌───────┴─────────┐             │
│       │               │              │  invoice_items  │             │
│       │               │              │  (UUID)         │             │
│       │               │              └─────────────────┘             │
│       │               │                                              │
│       │               │ 1:N         ┌──────────────────┐             │
│       │               ├────────────→│   portfolios     │             │
│       │               │             │   (UUID)         │             │
│       │               │             └────────┬─────────┘             │
│       │               │                      │ 1:N                  │
│       │               │              ┌───────┴─────────┐             │
│       │               │              │ portfolio_tags  │             │
│       │               │              │ (UUID)          │             │
│       │               │              └─────────────────┘             │
│       │               │                                              │
│       │               │                                              │
│       │ 1:N           │              ┌──────────────────┐             │
│       ├───────────────┘              │  notifications  │             │
│       │                              │  (UUID)          │             │
│       │                              └──────────────────┘             │
│       │              ┌──────────────────┐                             │
│       │              │  activity_logs  │                             │
│       │              │  (UUID)         │                             │
│       │              └──────────────────┘                             │
│       │              ┌──────────────────┐                             │
│       └──────────────│    sessions     │                             │
│                      └──────────────────┘                             │
│                      ┌──────────────────┐                             │
│                      │     cache       │                             │
│                      └──────────────────┘                             │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Detail Relasi per Tabel

| **#** | **Tabel** | **PK** | **Soft Delete** | **Relasi** |
|:-----:|-----------|--------|:---------------:|------------|
| 1 | **users** | UUID | ✅ | 1:1 → clients<br>1:N → jobs (assigned_to)<br>1:N → job_logs<br>1:N → notifications<br>1:N → activity_logs<br>1:N → projects (created_by) |
| 2 | **clients** | UUID | ✅ | 1:1 ← users<br>1:N → projects<br>1:N → invoices |
| 3 | **projects** | UUID | ✅ | N:1 ← clients<br>N:1 ← users (created_by)<br>1:N → jobs<br>1:N → invoices<br>1:N → portfolios<br>1:N → project_teams |
| 4 | **project_teams** | UUID | ❌ | N:1 ← projects<br>1:N → project_team_members |
| 5 | **project_team_members** | UUID | ❌ | N:1 ← project_teams<br>N:1 ← users |
| 6 | **jobs** | UUID | ✅ | N:1 ← projects<br>N:1 ← users (assigned_to)<br>N:1 ← users (created_by)<br>1:N → job_logs<br>1:N → job_attachments |
| 7 | **job_logs** | UUID | ❌ | N:1 ← jobs<br>N:1 ← users |
| 8 | **job_attachments** | UUID | ❌ | N:1 ← jobs<br>N:1 ← users (uploaded_by) |
| 9 | **invoices** | UUID | ✅ | N:1 ← projects<br>N:1 ← clients<br>N:1 ← users (created_by)<br>1:N → invoice_items |
| 10 | **invoice_items** | UUID | ❌ | N:1 ← invoices |
| 11 | **portfolios** | UUID | ✅ | N:1 ← projects<br>N:1 ← users (created_by)<br>1:N → portfolio_tags |
| 12 | **portfolio_tags** | UUID | ❌ | N:1 ← portfolios |
| 13 | **notifications** | UUID | ❌ | N:1 ← users |
| 14 | **activity_logs** | UUID | ❌ | N:1 ← users (nullable) |
| 15 | **sessions** | string | ❌ | - |
| 16 | **cache** | string | ❌ | - |

### 8.3 Skema Relasi (Textual)

```
users (1) ────────── (1) clients
users (1) ────────── (N) jobs [assigned_to]
users (1) ────────── (N) job_logs
users (1) ────────── (N) notifications
users (1) ────────── (N) activity_logs
users (1) ────────── (N) projects [created_by]

clients (1) ───────── (N) projects
clients (1) ───────── (N) invoices

projects (1) ──────── (N) jobs
projects (1) ──────── (N) invoices
projects (1) ──────── (N) portfolios
projects (1) ──────── (N) project_teams

project_teams (1) ──── (N) project_team_members
users (1) ──────────── (N) project_team_members

jobs (1) ───────────── (N) job_logs
jobs (1) ───────────── (N) job_attachments

invoices (1) ───────── (N) invoice_items

portfolios (1) ─────── (N) portfolio_tags
```

### 8.4 Constraints Penting

| **Constraint** | **Deskripsi** |
|----------------|---------------|
| `clients.user_id` | UNIQUE — satu user hanya bisa jadi satu client |
| `project_team_members (project_team_id, user_id)` | UNIQUE — crew tidak bisa double di tim yang sama |
| `portfolio_tags (portfolio_id, tag)` | UNIQUE — tag tidak duplikat per portfolio |
| `projects.code` | UNIQUE — kode project STX-YYYY-NNN tidak duplikat |
| `invoices.invoice_number` | UNIQUE — nomor invoice tidak duplikat |
| `users.email` | UNIQUE — email login tidak duplikat |

---

## 9. MVC ARCHITECTURE

### 9.1 Arsitektur MVC

```
┌─────────────────────────────────────────────────────────────────────┐
│                    LARAVEL MVC ARCHITECTURE                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│   ┌──────────┐     ┌────────────────┐     ┌──────────┐              │
│   │  ROUTES  │────→│  CONTROLLERS   │────→│  VIEWS   │              │
│   │ web.php  │     │  (16 files)    │     │ (40+ file)│             │
│   │ admin.php│     │                │     │ .blade.php│              │
│   │ crew.php │     │ • Admin (10)   │     └──────────┘              │
│   │client.php│     │ • Crew (3)     │           │                   │
│   │ auth.php │     │ • Client (3)   │           │                   │
│   └──────────┘     │ • Auth (1)     │           │                   │
│         │          │ • Shared (2)   │           │                   │
│         │          └────────┬───────┘           │                   │
│         │                   │                   │                   │
│         │         ┌────────┴───────┐            │                   │
│         │         │    MODELS      │            │                   │
│         │         │  (14 files)    │            │                   │
│         │         └────────────────┘            │                   │
│         │                   │                   │                   │
│         └───────────────────┼───────────────────┘                   │
│                             │                                       │
│                    ┌────────┴────────┐                               │
│                    │   MIDDLEWARE     │                              │
│                    │ • CheckRole      │                              │
│                    │ • LogActivity    │                              │
│                    │ • auth (Laravel) │                              │
│                    └─────────────────┘                               │
│                                                                      │
│   ┌──────────┐    ┌──────────┐    ┌──────────┐                      │
│   │  ENUMS   │    │ HELPERS  │    │ SEEDERS  │                      │
│   │ (5 file) │    │ (2 file) │    │ (4 file) │                      │
│   └──────────┘    └──────────┘    └──────────┘                      │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 9.2 Struktur File

```
app/
├── Enums/
│   ├── UserRole.php          // admin, atasan, crew, client
│   ├── ProjectStatus.php     // draft, active, review, done, archived
│   ├── JobStatus.php         // todo, inprogress, review, done
│   ├── JobPriority.php       // low, medium, high, urgent
│   └── InvoiceStatus.php     // draft, sent, dp_paid, paid, overdue
│
├── Helpers/
│   ├── NotificationHelper.php  // notify(), notifyMany(), notifyAdmins()
│   └── MarkdownHelper.php      // parse() untuk rendering PDF
│
├── Models/
│   ├── User.php           // HasUuids, SoftDeletes, Authenticatable
│   ├── Client.php         // HasUuids, SoftDeletes
│   ├── Project.php        // HasUuids, SoftDeletes, booted() cascade delete
│   ├── Job.php            // HasUuids, SoftDeletes
│   ├── JobLog.php         // HasUuids, $timestamps = false
│   ├── JobAttachment.php  // HasUuids
│   ├── Invoice.php        // HasUuids, SoftDeletes
│   ├── InvoiceItem.php    // HasUuids
│   ├── InvoiceTemplate.php // HasUuids
│   ├── Portfolio.php      // HasUuids, SoftDeletes
│   ├── PortfolioTag.php   // HasUuids
│   ├── ProjectTeam.php    // HasUuids [tidak dipakai aktif]
│   ├── ProjectTeamMember.php // HasUuids [tidak dipakai aktif]
│   ├── Notification.php   // HasUuids, $timestamps = false
│   └── ActivityLog.php    // HasUuids, $timestamps = false
│
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php              // Base controller
│   │   ├── ProfileController.php       // show, update, updateAvatar, updatePassword
│   │   ├── NotificationController.php  // index, markAsRead, markAllAsRead, destroy, unreadCount
│   │   ├── Auth/
│   │   │   └── AuthController.php      // showLogin, login, logout
│   │   ├── Admin/
│   │   │   ├── DashboardController.php  // index (statistik + progress)
│   │   │   ├── UserController.php       // index, store, edit, update, destroy, toggleActive
│   │   │   ├── ClientController.php     // index, store, edit, update, destroy
│   │   │   ├── ProjectController.php    // index, create, store, show, edit, update, destroy, updateStatus
│   │   │   ├── JobController.php        // index, create, store, show, edit, update, destroy, updateStatus
│   │   │   ├── InvoiceController.php    // index, create, store, show, edit, update, updateStatus, destroy, downloadPdf
│   │   │   ├── PortfolioController.php  // index, create, store, show, edit, update, destroy, togglePublic
│   │   │   └── ReportController.php     // index, crew, exportPdf, exportExcel
│   │   ├── Crew/
│   │   │   ├── DashboardController.php  // index (statistik pribadi)
│   │   │   ├── JobController.php        // index, show (milik sendiri)
│   │   │   └── ProgressController.php   // updateStatus
│   │   └── Client/
│   │       ├── DashboardController.php  // index (statistik pribadi)
│   │       ├── InvoiceController.php    // index, show (milik sendiri)
│   │       └── PortfolioController.php  // index, show (public only)
│   │
│   └── Middleware/
│       ├── CheckRole.php      // Validasi role: admin,atasan,crew,client
│       └── LogActivity.php    // Catat aksi mutasi ke activity_logs
│
resources/views/
├── layouts/
│   ├── app.blade.php          // Layout utama (sidebar + topbar + content)
│   └── auth.blade.php         // Layout halaman login
├── components/
│   ├── sidebar.blade.php      // Navigasi sidebar berdasarkan role
│   ├── topbar.blade.php       // Topbar (notifikasi dropdown, user menu)
│   └── ui/
│       └── nav-item.blade.php // Komponen item navigasi reusable
├── auth/
│   └── login.blade.php        // Halaman login
├── profile/
│   └── show.blade.php         // Halaman profil (avatar, edit, password)
├── notifications/
│   └── index.blade.php        // Semua notifikasi + pagination
├── admin/
│   ├── dashboard/index.blade.php
│   ├── users/{index,edit}.blade.php
│   ├── clients/{index,edit}.blade.php
│   ├── projects/{index,show,create,edit}.blade.php
│   ├── jobs/{index,show,create,edit}.blade.php
│   ├── invoices/{index,show,create,edit,pdf}.blade.php
│   ├── portfolios/{index,show,create,edit}.blade.php
│   └── reports/{index,crew,pdf}.blade.php
├── crew/
│   ├── dashboard/index.blade.php
│   └── jobs/{index,show}.blade.php
└── client/
    ├── dashboard/index.blade.php
    ├── invoices/{index,show}.blade.php
    └── portfolios/{index,show}.blade.php
```

### 9.3 Alur Request

```
┌──────────┐     ┌─────────┐     ┌────────────┐     ┌──────────┐     ┌───────────┐
│ Browser  │────→│ Apache  │────→│ index.php  │────→│ Laravel  │────→│  Router   │
│ (User)   │     │ (XAMPP) │     │ (Front Controller)│ Kernel   │     │           │
└──────────┘     └─────────┘     └────────────┘     └────┬─────┘     └─────┬─────┘
                                                          │                 │
                                                          │                 ↓
                                                          │        ┌────────────────┐
                                                          │        │   Middleware   │
                                                          │        │ • auth         │
                                                          │        │ • CheckRole    │
                                                          │        │ • LogActivity  │
                                                          │        └───────┬────────┘
                                                          │                │
                                                          ↓                ↓
                                                  ┌──────────────┐ ┌──────────────┐
                                                  │  Controller  │ │   View       │
                                                  │  (Business   │ │ (Blade +     │
                                                  │   Logic)     │ │  Tailwind)   │
                                                  └──────┬───────┘ └──────────────┘
                                                         │
                                                         ↓
                                                  ┌──────────────┐
                                                  │    Model     │
                                                  │  (Eloquent)  │
                                                  └──────┬───────┘
                                                         │
                                                         ↓
                                                  ┌──────────────┐
                                                  │    MySQL     │
                                                  │   (via PDO)  │
                                                  └──────────────┘

Response: HTML (Blade) → Browser
```

---

## 10. WIREFRAME / STRUKTUR HALAMAN

### 10.1 Layout Umum (Admin & Atasan)

```
┌─────────────────────────────────────────────────────────────┐
│  TOPBAR                                                      │
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │ Logo/Klik    │  [Breadcrumb]              🔔 Notif  👤 ││
│  │ ≡ Toggle     │                           (badge)  User ││
│  └──────────────┴──────────────────────────────────────────┘│
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │  SIDEBAR     │  MAIN CONTENT                            ││
│  │              │                                           ││
│  │ 📊 Dashboard │  ┌────────────────────────────────────┐  ││
│  │              │  │  Page Title     [Action Button]     │  ││
│  │ ⚙ Manajemen  │  ├────────────────────────────────────┤  ││
│  │   👥 Users   │  │                                     │  ││
│  │   🏢 Clients │  │  Content area                       │  ││
│  │              │  │  - Table / Card / Form / Detail     │  ││
│  │ 📁 Projects  │  │  - Filter / Search / Pagination     │  ││
│  │   📋 Jobs    │  │                                     │  ││
│  │              │  └────────────────────────────────────┘  ││
│  │ 💰 Invoices  │                                           ││
│  │              │  Flash message (success/error)           ││
│  │ 🖼 Portfolios│                                           ││
│  │              │                                           ││
│  │ 📈 Reports   │                                           ││
│  │              │                                           ││
│  │ ──────────── │                                           ││
│  │ 👤 Nama User │                                           ││
│  │    role badge│                                           ││
│  └──────────────┴──────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

### 10.2 Layout Crew

```
┌─────────────────────────────────────────────────────────────┐
│  TOPBAR                                                      │
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │ Crew View    │                              🔔 Notif  👤││
│  └──────────────┴──────────────────────────────────────────┘│
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │  SIDEBAR     │  MAIN CONTENT                            ││
│  │              │                                           ││
│  │ 📊 Dashboard │  (crew hanya lihat data sendiri)         ││
│  │              │                                           ││
│  │ 📋 My Jobs   │                                           ││
│  │              │                                           ││
│  └──────────────┴──────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

### 10.3 Layout Client

```
┌─────────────────────────────────────────────────────────────┐
│  TOPBAR                                                      │
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │ Client Portal│                              🔔 Notif  👤││
│  └──────────────┴──────────────────────────────────────────┘│
│  ┌──────────────┬──────────────────────────────────────────┐│
│  │  SIDEBAR     │  MAIN CONTENT                            ││
│  │              │                                           ││
│  │ 📊 Dashboard │  (client hanya lihat data sendiri)       ││
│  │              │                                           ││
│  │ 💰 Invoices  │                                           ││
│  │              │                                           ││
│  │ 🖼 Portfolios│                                           ││
│  └──────────────┴──────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

### 10.4 Halaman Kunci

#### Halaman Dashboard Admin
```
┌────────────────────────────────────────────────────────────┐
│  Dashboard                                           Admin │
├────────────────────────────────────────────────────────────┤
│  ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐                   │
│  │ 12   │  │ 8    │  │ 5    │  │ 10   │                   │
│  │ Total│  │ Active│  │Pending│  │ Crew │                   │
│  │Project│  │ Jobs │  │Invoice│  │Active│                   │
│  └──────┘  └──────┘  └──────┘  └──────┘                   │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │ Project Progress                      [Sort ▾]          ││
│  ├─────────────────────────────────────────────────────────┤│
│  │ Project A  ████████████░░░░░░ 75%     STX-2026-001    ││
│  │ Project B  ██████░░░░░░░░░░░░ 35%     STX-2026-002    ││
│  │ Project C  ████████████████ 100%      STX-2026-003    ││
│  └─────────────────────────────────────────────────────────┘│
└────────────────────────────────────────────────────────────┘
```

#### Halaman Detail Invoice
```
┌────────────────────────────────────────────────────────────┐
│  Invoice INV/STX/2026/001                       [PDF] [⋮] │
├────────────────────────────────────────────────────────────┤
│  Status: ● Terkirim (Sent)  │  Client: Rizky Pratama      │
│  Project: Video Company Profil  │  Tgl: 13 Jun 2026      │
├────────────────────────────────────────────────────────────┤
│  ┌────────────────────────────────────────────────────────┐│
│  │ Item                           Harga    Disc    Total  ││
│  ├────────────────────────────────────────────────────────┤│
│  │ Video Company Profile     Rp5.000.000  0%  Rp5.000.000││
│  │ Motion Graphics           Rp2.000.000 10%  Rp1.800.000││
│  └────────────────────────────────────────────────────────┘│
│                                                             │
│  Subtotal: Rp6.800.000                                      │
│  PPH 2%  : (Rp136.000)                                     │
│  TOTAL   : Rp6.664.000                                     │
│  DP      : Rp3.000.000                                     │
│  Sisa    : Rp3.664.000                                     │
│                                                             │
│  [Ubah Status ▾]    Bank: BCA 0191040839 a.n PT JTM       │
└────────────────────────────────────────────────────────────┘
```

#### Halaman Detail Job
```
┌────────────────────────────────────────────────────────────┐
│  Job: Buat Naskah Video                         STX-2026..│
├────────────────────────────────────────────────────────────┤
│  Status: ● In Progress    │  Priority: 🔴 High            │
│  Assignee: Budi (Crew)    │  Deadline: 20 Jul 2026        │
│  Google Drive: [Link]                                     │
├────────────────────────────────────────────────────────────┤
│  Deskripsi                                                 │
│  Buat naskah untuk video company profile.                  │
│  Target durasi 3 menit, tone profesional.                 │
├────────────────────────────────────────────────────────────┤
│  Timeline                                                  │
│  ┌────────────────────────────────────────────────────────┐│
│  │ 🔵 Admin → To Do                       12 Jul 09:00  ││
│  │ 🟡 Budi → In Progress                  13 Jul 14:30  ││
│  │ 📝 Catatan: "Mulai riset bahan"                      ││
│  └────────────────────────────────────────────────────────┘│
│                                                             │
│  [Update Status ▾]  [Edit]  [Hapus]                       │
└────────────────────────────────────────────────────────────┘
```

---

## 11. STATUS FLOW

### 11.1 Flow Status Project

```
┌─────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌───────────┐
│  DRAFT   │────→│  ACTIVE  │────→│  REVIEW  │────→│   DONE   │────→│ ARCHIVED  │
│ (gray)   │     │ (blue)   │     │ (yellow) │     │ (green)  │     │ (red)     │
└─────────┘     └──────────┘     └──────────┘     └──────────┘     └───────────┘
     │               │               │                               ↑
     └───────────────┴───────────────┴───────────────────────────────┘
     (dapat langsung ke status mana pun)
```

### 11.2 Flow Status Job

```
                    ┌─────────┐
                    │  TODO   │
                    │ (gray)  │
                    └────┬────┘
                         │
           ┌─────────────┼──────────────┐
           │             │              │
           ↓             ↓              ↓
     ┌───────────┐ ┌────────┐ ┌────────────┐
     │INPROGRESS │ │ REVIEW │ │   DONE     │
     │ (blue)    │ │(yellow)│ │ (green)    │
     └─────┬─────┘ └───┬────┘ └──────┬─────┘
           │           │             │
           └───────────┴─────────────┘
           (dapat kembali ke status sebelumnya)
```

**Auto-timestamp:**
- `todo → inprogress` : `started_at = now()`
- `→ done` : `completed_at = now()`

### 11.3 Flow Status Invoice

```
┌───────┐      ┌──────┐      ┌──────────┐      ┌──────┐
│ DRAFT │─────→│ SENT │─────→│ DP_PAID  │─────→│ PAID │
│(gray) │      │(blue)│      │ (yellow) │      │(green)│
└───────┘      └──┬───┘      └──────────┘      └──────┘
                  │                │
                  ↓                ↓
            ┌──────────┐    ┌──────────┐
            │ OVERDUE  │    │ OVERDUE  │
            │  (red)   │    │  (red)   │
            └──────────┘    └──────────┘
```

**Auto-timestamp:**
- `→ sent` : `sent_at = now()`
- `→ dp_paid` : `dp_paid_at = now()`, `dp_paid = dp_amount`
- `→ paid` : `paid_at = now()`, `dp_paid = total`

---

## 12. ROUTE STRUCTURE

### 12.1 Route Prefix per Role

| **Role** | **Prefix** | **Middleware** |
|----------|-----------|----------------|
| Admin + Atasan (read) | `/admin/*` | `auth, role:admin,atasan, log.activity` |
| Admin (mutations) | `/admin/*` | `auth, role:admin, log.activity` |
| Crew | `/crew/*` | `auth, role:crew, log.activity` |
| Client | `/client/*` | `auth, role:client` |
| Semua (shared) | `/notifications/*`, `/profile/*` | `auth` |
| Guest | `/login` | `guest` |

### 12.2 Daftar Route per Modul

#### Autentikasi
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/login` | `login` |
| POST | `/login` | `login.post` |
| POST | `/logout` | `logout` |

#### Profil
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/profile` | `profile.show` |
| PUT | `/profile` | `profile.update` |
| POST | `/profile/avatar` | `profile.avatar` |
| PUT | `/profile/password` | `profile.password` |

#### Notifikasi
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/notifications` | `notifications.index` |
| GET/PATCH | `/notifications/{notification}/read` | `notifications.read` |
| PATCH | `/notifications/read-all` | `notifications.read-all` |
| DELETE | `/notifications/{notification}` | `notifications.destroy` |
| GET | `/notifications/unread-count` | `notifications.unread-count` |

#### Admin — Read (Admin + Atasan)
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/admin/dashboard` | `admin.dashboard` |
| GET | `/admin/projects` | `admin.projects.index` |
| GET | `/admin/projects/create` | `admin.projects.create` |
| GET | `/admin/projects/{project}` | `admin.projects.show` |
| GET | `/admin/projects/{project}/edit` | `admin.projects.edit` |
| GET | `/admin/jobs` | `admin.jobs.index` |
| GET | `/admin/jobs/{job}` | `admin.jobs.show` |
| GET | `/admin/jobs/{job}/edit` | `admin.jobs.edit` |
| GET | `/admin/projects/{project}/jobs/create` | `admin.projects.jobs.create` |
| GET | `/admin/invoices` | `admin.invoices.index` |
| GET | `/admin/invoices/create` | `admin.invoices.create` |
| GET | `/admin/invoices/{invoice}` | `admin.invoices.show` |
| GET | `/admin/invoices/{invoice}/pdf` | `admin.invoices.pdf` |
| GET | `/admin/portfolios` | `admin.portfolios.index` |
| GET | `/admin/portfolios/create` | `admin.portfolios.create` |
| GET | `/admin/portfolios/{portfolio}` | `admin.portfolios.show` |
| GET | `/admin/portfolios/{portfolio}/edit` | `admin.portfolios.edit` |
| GET | `/admin/reports` | `admin.reports.index` |
| GET | `/admin/reports/crew/{user}` | `admin.reports.crew` |
| GET | `/admin/reports/export/pdf` | `admin.reports.export.pdf` |
| GET | `/admin/reports/export/excel` | `admin.reports.export.excel` |

#### Admin — Mutasi (Admin Only)
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET+POST | `/admin/users` + `/admin/users/create` | `admin.users.*` |
| `resource` | `/admin/users/{user}` | `admin.users.*` |
| PATCH | `/admin/users/{user}/toggle-active` | `admin.users.toggle-active` |
| GET+POST | `/admin/clients` | `admin.clients.*` |
| POST | `/admin/projects` | `admin.projects.store` |
| PUT | `/admin/projects/{project}` | `admin.projects.update` |
| DELETE | `/admin/projects/{project}` | `admin.projects.destroy` |
| PATCH | `/admin/projects/{project}/status` | `admin.projects.update-status` |
| POST | `/admin/projects/{project}/jobs` | `admin.projects.jobs.store` |
| PUT | `/admin/jobs/{job}` | `admin.jobs.update` |
| DELETE | `/admin/jobs/{job}` | `admin.jobs.destroy` |
| PATCH | `/admin/jobs/{job}/status` | `admin.jobs.update-status` |
| POST | `/admin/invoices` | `admin.invoices.store` |
| PUT | `/admin/invoices/{invoice}` | `admin.invoices.update` |
| DELETE | `/admin/invoices/{invoice}` | `admin.invoices.destroy` |
| PATCH | `/admin/invoices/{invoice}/status` | `admin.invoices.update-status` |
| POST | `/admin/portfolios` | `admin.portfolios.store` |
| PUT | `/admin/portfolios/{portfolio}` | `admin.portfolios.update` |
| DELETE | `/admin/portfolios/{portfolio}` | `admin.portfolios.destroy` |
| PATCH | `/admin/portfolios/{portfolio}/toggle-public` | `admin.portfolios.toggle-public` |

#### Crew
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/crew/dashboard` | `crew.dashboard` |
| GET | `/crew/jobs` | `crew.jobs.index` |
| GET | `/crew/jobs/{job}` | `crew.jobs.show` |
| PATCH | `/crew/jobs/{job}/status` | `crew.jobs.update-status` |

#### Client
| **Method** | **URI** | **Nama Route** |
|:----------:|---------|---------------|
| GET | `/client/dashboard` | `client.dashboard` |
| GET | `/client/invoices` | `client.invoices.index` |
| GET | `/client/invoices/{invoice}` | `client.invoices.show` |
| GET | `/client/portfolios` | `client.portfolios.index` |
| GET | `/client/portfolios/{portfolio}` | `client.portfolios.show` |

---

## 13. TECH STACK DETAIL

| **Lapisan** | **Teknologi** | **Keterangan** |
|-------------|---------------|----------------|
| **Backend Framework** | Laravel 12 | PHP 8.2.12 |
| **Database** | MySQL 8 | Via XAMPP, port 3306 |
| **Frontend Template** | Blade | Laravel templating engine |
| **CSS Framework** | Tailwind CSS v4 | Tanpa tailwind.config.js — config via `@import` |
| **CSS Typography** | Inter Font | Google Fonts (400,500,600,700,800) |
| **JavaScript** | Alpine.js | Interaktivitas dinamis (form items, dll) |
| **PDF Generator** | DOMPDF | `barryvdh/laravel-dompdf` |
| **Bundler** | Vite v7 | HMR + build production |
| **Web Server** | Apache | XAMPP bawaan |
| **Session** | File Driver | Bukan database (issue MySQL XAMPP) |
| **Primary Key** | UUID | Semua tabel |
| **Soft Delete** | `Illuminate\Database\Eloquent\SoftDeletes` | users, clients, projects, jobs, invoices, portfolios |

---

## 14. NOTIFIKASI SISTEM

### 14.1 Trigger Notifikasi

| **Event** | **Trigger** | **Tipe** | **Penerima** |
|-----------|-------------|----------|-------------|
| Job Baru Ditugaskan | `JobController@store` | `job_assigned` | Crew yang diassign |
| Job Status Diubah (oleh Admin) | `JobController@updateStatus` | `job_status_*` | Crew yang diassign |
| Job → InProgress (oleh Crew) | `ProgressController@updateStatus` | `job_inprogress` | Admin + Atasan + Client |
| Job → Review (oleh Crew) | `ProgressController@updateStatus` | `job_review` | Admin + Atasan + Client |
| Job → Done (oleh Crew) | `ProgressController@updateStatus` | `job_done` | Admin + Atasan + Client |
| Invoice Dikirim | `InvoiceController@updateStatus` | `invoice_sent` | Client terkait |
| Invoice DP Dibayar | `InvoiceController@updateStatus` | `invoice_dp_paid` | Admin + Atasan |
| Invoice Lunas | `InvoiceController@updateStatus` | `invoice_paid` | Admin + Atasan |
| Portofolio Dipublikasi | `PortfolioController@store/togglePublic` | `portfolio_published` | Admin + Atasan |

### 14.2 Channel Notifikasi
- **Saat ini:** In-app notification (database) — via dropdown topbar + halaman `/notifications`
- **Future:** Email notification (belum diimplementasikan)

---

## 15. KENDALA DAN CATATAN TEKNIS

### 15.1 Known Issues
1. **SESSION_DRIVER** harus `file` — MySQL XAMPP tidak support `performance_schema` yang diperlukan untuk session database
2. **Session & Cache tables** dibuat manual via artisan, bukan dari migration awal
3. **Laravel 12 default SQLite** — harus diubah manual ke MySQL di `.env`
4. **Tailwind v4** tidak pakai `tailwind.config.js` — config via `@import` di CSS
5. **npm** harus dijalankan di CMD (bukan PowerShell) — execution policy Windows
6. **make:enum** tidak tersedia di Laravel 12 — buat file PHP manual
7. **Invoice number** mengandung karakter `/` (INV/STX/YYYY/NNN) — harus di-replace jadi `-` untuk filename PDF download
8. **DOMPDF** tidak bisa pakai class Tailwind — harus inline CSS atau `<style>` tag biasa
9. **Route ordering** — static route (`/create`, `/edit`) harus SEBELUM parameterized (`/{id}`)
10. **Operator Precedence PHP** — `.` (string concat) lebih tinggi dari `??` (null coalescing). Gunakan ternary atau parentheses

### 15.2 Catatan Penting
- **Cascade delete** dilakukan via model event (`Project@booted()`), bukan via foreign key — karena soft-delete tidak trigger foreign key cascade di database
- **Invoice tidak bisa diedit** setelah dibuat — by design karena invoice adalah dokumen keuangan final. Perubahan hanya status flow
- **Blade cache** bisa silent bug — setelah edit view, jalankan `php artisan view:clear`
- **JobAttachment** menyimpan path file lokal — pastikan `php artisan storage:link` sudah dijalankan
- **Portfolio thumbnail** disimpan di `storage/app/public/portfolios/`
- **Avatar user** disimpan di `storage/app/public/avatars/`

### 15.3 Command Berguna
```bash
npm run dev                          # Jalankan Vite dev server
npm run build                        # Build production
php artisan migrate:fresh --seed     # Reset + seed database
php artisan storage:link             # Symlink untuk upload
php artisan optimize:clear           # Clear semua cache
php artisan view:clear               # Clear compiled views
php artisan route:list --name=admin  # Cek route admin
```

---

> **Dokumen ini disusun sebagai bagian dari dokumentasi sistem Storimax Agency Admin System**
> **PT Jalur Tengah Kreasindo — 2026**
