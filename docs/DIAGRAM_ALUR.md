# DIAGRAM ALUR SISTEM
## Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Diagram Alur Sistem |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Diagram Alur Autentikasi
2. Diagram Alur Manajemen Proyek
3. Diagram Alur Status Pekerjaan
4. Diagram Alur Status Faktur
5. Diagram Alur Otorisasi Peran
6. Diagram Alur Notifikasi
7. Diagram Alur Pembuatan Faktur PDF
8. Diagram Alur CRUD Umum
9. Diagram Alur Kalkulasi Faktur
10. Diagram Deployment

---

## 1. DIAGRAM ALUR AUTENTIKASI

Diagram alur autentikasi menggambarkan proses _login_ dan _logout_ pengguna pada sistem.

```
                    ┌──────────────────┐
                    │   MULAI           │
                    │   Pengguna        │
                    │   membuka /login  │
                    └────────┬─────────┘
                             │
                             ↓
                    ┌──────────────────┐
                    │ Apakah pengguna  │──── Ya ──→ ┌──────────────────────────────┐
                    │ sudah login?     │            │ Redirect ke dasbor            │
                    │ (Auth::check())  │            │ sesuai peran pengguna         │
                    └────────┬─────────┘            └──────────────────────────────┘
                             │ Tidak
                             ↓
              ┌──────────────────────────────────┐
              │ Tampilkan formulir login          │
              │ Input: surel, kata sandi,         │
              │ opsi "Ingat Saya"                 │
              └──────────────┬───────────────────┘
                             │ Pengguna mengirim formulir
                             ↓
              ┌──────────────────────────────────┐
              │ Validasi input:                   │
              │ • Surel: wajib, format email      │
              │ • Kata sandi: wajib, minimal 8    │
              │ karakter                          │
              └──────────────┬───────────────────┘
                             │
                    ┌────────┴────────┐
                    │ Valid?          │── Tidak ──→ ┌──────────────────────┐
                    └────────┬────────┘             │ Kembali ke halaman    │
                             │ Ya                   │ login dengan pesan   │
                             ↓                      │ kesalahan validasi    │
              ┌──────────────────────────────────┐  └──────────────────────┘
              │ Auth::attempt($credentials)       │
              │ • Cari pengguna berdasarkan surel  │
              │ • Verifikasi kata sandi (bcrypt)  │
              └──────────────┬───────────────────┘
                             │
                    ┌────────┴────────┐
                    │ Kredensial      │── Tidak ──→ ┌──────────────────────┐
                    │ cocok?          │             │ Kembali ke login     │
                    └────────┬────────┘             │ "Email atau password │
                             │ Ya                   │ salah"               │
                             ↓                      └──────────────────────┘
              ┌──────────────────────────────────┐
              │ Periksa status pengguna           │
              │ $user->is_active                  │
              └──────────────┬───────────────────┘
                             │
                    ┌────────┴────────┐
                    │ Akun aktif?     │── Tidak ──→ Logout → Redirect login
                    └────────┬────────┘            "Akun kamu tidak aktif"
                             │ Ya
                             ↓
              ┌──────────────────────────────────┐
              │ Regenerasi sesi                   │
              │ Redirect sesuai peran:            │
              │ • ADMIN   → /admin/dashboard      │
              │ • ATASAN  → /admin/dashboard      │
              │ • CREW    → /crew/dashboard       │
              │ • CLIENT  → /client/dashboard     │
              └──────────────────────────────────┘
```

---

## 2. DIAGRAM ALUR MANAJEMEN PROYEK

Diagram alur manajemen proyek menggambarkan proses pengelolaan proyek mulai dari pembuatan hingga penghapusan.

```
                    ┌──────────────────────────────────────┐
                    │  Administrator membuka                │
                    │  halaman /admin/projects              │
                    └─────────────┬────────────────────────┘
                                  │
                                  ↓
                    ┌──────────────────────────────────────┐
                    │  Sistem menampilkan daftar            │
                    │  seluruh proyek (dengan data          │
                    │  klien, status, prioritas, deadline)  │
                    └─────────────┬────────────────────────┘
                                  │
          ┌───────────────────────┼───────────────────────┐
          │                       │                       │
          ↓                       ↓                       ↓
┌──────────────────┐   ┌──────────────────┐   ┌──────────────────┐
│ Klik "Buat Baru" │   │ Klik nama proyek │   │ Klik "Edit"      │
└────────┬─────────┘   └────────┬─────────┘   └────────┬─────────┘
         │                      │                      │
         ↓                      ↓                      ↓
┌──────────────────┐   ┌──────────────────┐   ┌──────────────────┐
│ Formulir Create  │   │ Halaman Detail   │   │ Formulir Edit    │
│ • Pilih klien    │   │ • Info proyek    │   │ (data sudah      │
│ • Nama proyek    │   │ • Data klien     │   │  terisi)         │
│ • Kategori       │   │ • Tim & anggota  │   │                  │
│ • Deskripsi      │   │ • Daftar job     │   │                  │
│ • Prioritas      │   │ • Faktur         │   │                  │
│ • Deadline       │   │ • Tombol aksi   │   │                  │
│ • Catatan        │   └────────┬─────────┘   └──────────────────┘
└────────┬─────────┘            │
         │                      │ (dari halaman detail)
         ↓                      │
┌──────────────────┐            │
│ Validasi input   │            │
│ ┌────────────┐   │            │
│ │ Valid?     │   │            │
│ └──────┬─────┘   │            │
└────────┬─────────┘            │
         │ Ya                   │
         ↓                      │
┌────────────────────────────────────────────⋯
│ Simpan ke basis data:                     │
│ • Generate kode: STX-2026-NNN             │
│ • Status default: DRAFT                   │
│ • Tampilkan pesan sukses                  │
│ • Redirect ke halaman detail              │
└───────────────────────────────────────────⋯
```

---

## 3. DIAGRAM ALUR STATUS PEKERJAAN

Diagram alur status pekerjaan menggambarkan transisi status pekerjaan yang diizinkan beserta proses di dalamnya.

```
                    ┌──────────────┐
                    │    MULAI      │
                    │   Status:     │
                    │    TODO       │
                    └──────┬───────┘
                           │
              ┌────────────┼────────────────┐
              ↓            ↓                ↓
    ┌────────────────┐ ┌──────────┐ ┌──────────────┐
    │ Kru / Admin    │ │  Admin   │ │  Admin       │
    │ mulai          │ │ review   │ │  langsung    │
    │ mengerjakan    │ │          │ │  selesai     │
    └───────┬────────┘ └────┬─────┘ └──────┬───────┘
            │               │              │
            ↓               ↓              ↓
    ┌───────────────────────────────────────────────┐
    │  Permintaan PATCH /admin/jobs/{job}/status     │
    │  atau /crew/jobs/{job}/status                 │
    │  Data: { status: "inprogress" | "review" |    │
    │          "done", note: "..." }                │
    └──────────────────────┬────────────────────────┘
                           │
                           ↓
    ┌───────────────────────────────────────────────┐
    │  Apakah transisi status valid?                │
    │  Transisi yang diizinkan:                     │
    │  • todo → inprogress, todo → review,          │
    │    todo → done                                │
    │  • inprogress → review, inprogress → done     │
    │  • review → done, review → inprogress         │
    │  • done → inprogress                          │
    └──────────────────────┬────────────────────────┘
                           │
              ┌────────────┴────────────┐
              ↓                         ↓
    ┌──────────────────┐   ┌──────────────────────────┐
    │ Transisi Tidak   │   │ Pembaruan Basis Data:    │
    │ Valid             │   │ • Status baru            │
    │ Kembalikan 422   │   │ • Jika → inprogress:     │
    │ Error Validasi    │   │   set started_at=now()   │
    └──────────────────┘   │ • Jika → done:           │
                           │   set completed_at=now()  │
                           │ • Simpan ke job_logs:    │
                           │   old_status, new_status, │
                           │   user_id, note           │
                           │ • Kirim notifikasi       │
                           │   ke kru yang ditugaskan  │
                           │ • Redirect dengan pesan  │
                           │   sukses                  │
                           └──────────────────────────┘
```

### 3.1 Diagram Status Pekerjaan

```
                    ┌─────────┐
                    │  TODO   │
                    └────┬────┘
                         │
           ┌─────────────┼──────────────┐
           │             │              │
           ↓             ↓              ↓
     ┌───────────┐ ┌────────┐ ┌────────────┐
     │INPROGRESS│ │ REVIEW │ │   DONE     │
     └─────┬─────┘ └───┬────┘ └──────┬─────┘
           │           │             │
           └───────────┴─────────────┘
           (dapat kembali ke status
            sebelumnya)
```

---

## 4. DIAGRAM ALUR STATUS FAKTUR

Diagram alur status faktur menggambarkan transisi status faktur beserta efek samping yang terjadi.

```
                    ┌──────────────────┐
                    │     DRAFT         │
                    │  (baru dibuat)    │
                    └──────┬───────────┘
                           │
                           ↓ Administrator mengirim
                    ┌──────────────────┐
                    │     SENT          │
                    │  • sent_at = now()│
                    │  • Notifikasi     │
                    │    ke klien       │
                    └──────┬───────────┘
                           │
              ┌────────────┼────────────────────┐
              ↓            ↓                    ↓
      ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
      │   DP_PAID    │ │   OVERDUE    │ │   PAID       │
      │ • dp_paid_at │ │ (lewati      │ │ • paid_at    │
      │   = now()    │ │   due_date)  │ │   = now()    │
      │ • dp_paid    │ └──────────────┘ │ • dp_paid    │
      │   = dp_amount│                  │   = total    │
      │ • Notifikasi │                  │ • Notifikasi │
      │   ke admin   │                  │   ke admin   │
      └──────┬───────┘                  └──────┬───────┘
             │                                │
             └───────────────┬────────────────┘
                             ↓
                     ┌──────────────┐
                     │   PAID       │
                     │   (Lunas)    │
                     └──────────────┘
```

### 4.1 Diagram Status Faktur

```
┌───────┐      ┌──────┐      ┌──────────┐      ┌──────┐
│ DRAFT │ ───→ │ SENT │ ───→ │ DP_PAID  │ ───→ │ PAID│
└───────┘      └──┬───┘      └──────────┘      └──────┘
                  │                 │
                  ↓                 ↓
            ┌──────────┐     ┌──────────┐
            │ OVERDUE  │     │ OVERDUE  │
            └──────────┘     └──────────┘
```

---

## 5. DIAGRAM ALUR OTORISASI PERAN

Diagram alur otorisasi peran menggambarkan proses verifikasi hak akses setiap permintaan HTTP.

```
                ┌──────────────────────────┐
                │   Permintaan HTTP Masuk   │
                └────────────┬─────────────┘
                             │
                             ↓
                ┌──────────────────────────┐
                │   Pencocokan Route        │
                │   (web.php, auth.php,     │
                │    admin.php, crew.php,   │
                │    client.php)            │
                └────────────┬─────────────┘
                             │
                             ↓
                ┌──────────────────────────┐
                │   Middleware Stack        │
                └────────────┬─────────────┘
                             │
                    ┌────────┴────────┐
                    ↓                 ↓
           ┌────────────────┐ ┌──────────────────┐
           │  Middleware    │ │  Middleware       │
           │  "auth"        │ │  "guest"          │
           │  (periksa      │ │  (hanya untuk     │
           │   login)       │ │   halaman login)  │
           └───────┬────────┘ └──────┬───────────┘
                   │                 │
            ┌──────┴──────┐         │
            │ Middleware  │          │
            │ "role"      │          │
            │ Validasi    │          │
            │ peran:      │          │
            │ admin,      │          │
            │ atasan,     │          │
            │ crew,       │          │
            │ client      │          │
            └──────┬──────┘          │
                   │                 │
                   ↓                 ↓
         ┌──────────────────┐ ┌────────────────┐
         │  Controller      │ │  Halaman Login │
         │  • CRUD          │ │  (guest only)  │
         │  • Render view   │ └────────────────┘
         │  • Redirect      │
         └──────────────────┘
```

---

## 6. DIAGRAM ALUR NOTIFIKASI

Diagram alur notifikasi menggambarkan proses pengiriman notifikasi dari pemicu hingga diterima oleh pengguna.

```
┌──────────────────────────────────────────────────────────────────┐
│                    PERISTIWA PEMICU                                │
├──────────────────────────────────────────────────────────────────┤
│ • Pekerjaan ditugaskan ke kru                                    │
│ • Status pekerjaan berubah                                       │
│ • Faktur dikirim ke klien                                        │
│ • Faktur dp_paid / paid                                          │
│ • Portofolio diterbitkan                                         │
└─────────────────────────┬────────────────────────────────────────┘
                          │
                          ↓
┌──────────────────────────────────────────────────────────────────┐
│               NOTIFICATIONHELPER                                  │
├──────────────────────────────────────────────────────────────────┤
│ • notify($userId, $type, $title, $message, $data, $actionUrl)    │
│   → Mengirim ke 1 pengguna                                       │
│ • notifyMany($userIds, ...)                                       │
│   → Mengirim ke beberapa pengguna sekaligus                      │
│ • notifyAdmins($type, $title, $message, $data, $actionUrl)       │
│   → Mengirim ke seluruh admin dan atasan                         │
└─────────────────────────┬────────────────────────────────────────┘
                          │
                          ↓
┌──────────────────────────────────────────────────────────────────┐
│                PENYIMPANAN DI BASIS DATA                          │
│                Tabel: notifications                               │
├──────────────────────────────────────────────────────────────────┤
│ Kolom yang disimpan:                                              │
│ • id (UUID)                                                       │
│ • user_id                                                         │
│ • type (contoh: 'job_assigned', 'invoice_sent')                  │
│ • title                                                           │
│ • message                                                         │
│ • data (JSON, nullable)                                           │
│ • action_url (tautan untuk tindakan)                              │
│ • is_read (boolean, default false)                               │
│ • created_at                                                      │
└─────────────────────────┬────────────────────────────────────────┘
                          │
                          ↓
┌──────────────────────────────────────────────────────────────────┐
│                ANTARMUKA PENGGUNA                                  │
├──────────────────────────────────────────────────────────────────┤
│ 1. Dropdown pada bilah atas (topbar)                             │
│    • Menampilkan 8 notifikasi terbaru                            │
│    • Klik pada notifikasi → tandai sebagai dibaca + redirect     │
│    • Badge jumlah notifikasi belum dibaca                        │
│                                                                  │
│ 2. Halaman /notifications                                        │
│    • Seluruh notifikasi dengan paginasi                          │
│    • Tombol "Tandai Semua Dibaca"                                │
│    • Tombol hapus per notifikasi                                 │
└──────────────────────────────────────────────────────────────────┘
```

---

## 7. DIAGRAM ALUR PEMBUATAN FAKTUR PDF

Diagram alur pembuatan faktur PDF menggambarkan proses dari permintaan unduh hingga berkas PDF diterima oleh pengguna.

```
┌─────────────────────────────────────┐
│  Pengguna mengklik tombol           │
│  "Download PDF"                     │
│  pada halaman detail faktur         │
└───────────────┬─────────────────────┘
                │
                ↓
┌─────────────────────────────────────┐
│  Permintaan GET ke                  │
│  /admin/invoices/{invoice}/pdf      │
│  → InvoiceController@downloadPdf    │
└───────────────┬─────────────────────┘
                │
                ↓
┌─────────────────────────────────────┐
│  Memuat data faktur dari basis data │
│  • $invoice->load(['client',        │
│      'project', 'items'])           │
└───────────────┬─────────────────────┘
                │
                ↓
┌─────────────────────────────────────┐
│  Render tampilan Blade:             │
│  admin/invoices/pdf.blade.php       │
│                                     │
│  Bagian tampilan:                   │
│  ┌───────────────────────────────┐  │
│  │ HEADER                        │  │
│  │ • Logo (base64 data URI)      │  │
│  │   Jika logo tidak ada:        │  │
│  │   tampilkan teks "STORIMAX"   │  │
│  │ • Nomor faktur                │  │
│  │ • Status faktur               │  │
│  ├───────────────────────────────┤  │
│  │ DATA KLIEN                    │  │
│  │ • Nama klien                  │  │
│  │ • Nama proyek                 │  │
│  │ • Tanggal faktur              │  │
│  ├───────────────────────────────┤  │
│  │ TABEL ITEM                    │  │
│  │ • Nama layanan                │  │
│  │ • Harga                       │  │
│  │ • Diskon                      │  │
│  │ • Total                       │  │
│  ├───────────────────────────────┤  │
│  │ RINGKASAN                     │  │
│  │ • Subtotal                    │  │
│  │ • PPh (2%)                    │  │
│  │ • TOTAL                       │  │
│  │ • Uang Muka                   │  │
│  │ • Sisa Tagihan                 │  │
│  ├───────────────────────────────┤  │
│  │ FOOTER                        │  │
│  │ • Informasi bank              │  │
│  │ • Catatan pembayaran          │  │
│  └───────────────────────────────┘  │
└───────────────┬─────────────────────┘
                │
                ↓
┌─────────────────────────────────────┐
│  DOMPDF::loadView()                 │
│  • Membuat objek PDF dari HTML      │
│  • Tidak memerlukan ekstensi GD     │
│    (logo menggunakan base64)        │
└───────────────┬─────────────────────┘
                │
                ↓
┌─────────────────────────────────────┐
│  Respons unduhan:                   │
│  • Nama berkas:                     │
│    str_replace('/', '-',            │
│      $invoice->invoice_number)      │
│      . '.pdf'                       │
│    Contoh: INV-STX-2026-001.pdf     │
│  • Header Content-Type:             │
│    application/pdf                  │
└─────────────────────────────────────┘
```

---

## 8. DIAGRAM ALUR CRUD UMUM

Diagram alur CRUD umum menggambarkan pola standar operasi _Create, Read, Update, Delete_ pada sistem.

```
┌──────────┐   ┌──────────┐   ┌──────────┐   ┌──────────┐   ┌──────────┐
│  INDEX   │   │  CREATE  │   │  STORE   │   │  SHOW    │   │  EDIT    │
│  (GET)   │──→│  (GET)   │──→│  (POST)  │──→│  (GET)   │──→│  (GET)   │
│          │   │          │   │          │   │          │   │          │
│ Lihat    │   │ Form     │   │ Simpan   │   │ Detail   │   │ Form     │
│ daftar   │   │ baru     │   │ data +   │   │ data     │   │ edit     │
│ data     │   │          │   │ validasi │   │          │   │          │
└──────────┘   └──────────┘   └──────────┘   └──────────┘   └────┬─────┘
                                                                  │
                          ┌───────────────────────────────────────┘
                          ↓
                    ┌──────────┐
                    │  UPDATE  │
                    │  (PUT)   │
                    │          │
                    │ Update   │
                    │ data +   │
                    │ validasi │
                    └──────────┘
                          │
                          ↓
                    ┌──────────┐
                    │ DESTROY  │
                    │ (DELETE) │
                    │          │
                    │ Soft     │
                    │ Delete   │
                    └──────────┘

Setiap operasi mutasi (STORE, UPDATE, DESTROY):
1. Validasi data masukan ($request->validate())
2. Proses data menggunakan Eloquent ORM
3. Menampilkan pesan sukses (flash message)
4. Redirect ke halaman terkait
```

---

## 9. DIAGRAM ALUR KALKULASI FAKTUR

Diagram alur kalkulasi faktur menggambarkan proses perhitungan nilai-nilai keuangan pada saat pembuatan faktur.

```
                    ┌──────────────────────────────────────┐
                    │  Data masukan dari formulir:         │
                    │  • Array items (service_name,        │
                    │    price, disc_percent)               │
                    │  • pph_rate                          │
                    │  • dp_amount                         │
                    └──────────────┬───────────────────────┘
                                   │
                                   ↓
                  ┌───────────────────────────────────────┐
                  │  Inisialisasi subtotal = 0            │
                  │  Inisialisasi array itemsData = []    │
                  └──────────────┬────────────────────────┘
                                   │
                                   ↓
                  ┌───────────────────────────────────────┐
                  │  Iterasi setiap item:                 │
                  │                                      │
                  │  for each item in items:             │
                  │    price = float(item.price)          │
                  │    discPercent = float(item.disc      │
                  │      _percent ?? 0)                   │
                  │    discAmount = price * discPercent   │
                  │      / 100                            │
                  │    totalItem = price - discAmount     │
                  │    subtotal += totalItem              │
                  │                                      │
                  │    Simpan ke itemsData[]:             │
                  │    { price, discPercent,              │
                  │      discAmount, total }              │
                  └──────────────────┬────────────────────┘
                                     │
                                     ↓
                  ┌───────────────────────────────────────┐
                  │  pphRate = float(pph_rate)            │
                  │  pphAmount = subtotal * pphRate / 100 │
                  │  total = subtotal - pphAmount          │
                  │  dpAmount = float(dp_amount ?? 0)      │
                  │  remaining = total - dpAmount          │
                  └──────────────────┬────────────────────┘
                                     │
                                     ↓
                  ┌───────────────────────────────────────┐
                  │  Simpan ke basis data:                 │
                  │  • Tabel invoices:                    │
                  │    subtotal, pph_rate, pph_amount,    │
                  │    total, dp_amount, dp_paid=0,       │
                  │    remaining, status=DRAFT            │
                  │  • Tabel invoice_items:               │
                  │    untuk setiap item dalam            │
                  │    itemsData[]                         │
                  └───────────────────────────────────────┘
```

---

## 10. DIAGRAM DEPLOYMENT

Diagram deployment menggambarkan konfigurasi fisik sistem pada lingkungan produksi.

```
┌───────────────────────────────────────────────────────────────────────────────┐
│                        SERVER XAMPP                                           │
│                                                                               │
│  ┌──────────────────────────┐    ┌───────────────────────────────────────┐    │
│  │      APACHE HTTPD        │    │          PHP 8.2.12                   │    │
│  │                          │    │                                       │    │
│  │  Port: 80 (HTTP)         │───→│  • Laravel 12 Framework              │    │
│  │  DocumentRoot:           │    │  • Blade Template Engine             │    │
│  │   C:/xampp/htdocs/       │    │  • Tailwind CSS v4 + Alpine.js      │    │
│  │   storimax/public        │    │  • DOMPDF (PDF Generation)           │    │
│  │                          │    │                                       │    │
│  └──────────────────────────┘    └──────────────────┬────────────────────┘    │
│                                                      │                        │
│  ┌──────────────────────────┐                        │                        │
│  │        MYSQL 8           │◄───────────────────────┘                        │
│  │                          │                                                 │
│  │  Port: 3306              │                                                 │
│  │  Database: storimax      │                                                 │
│  │  User: root              │                                                 │
│  └──────────────────────────┘                                                 │
│                                                                               │
│  ┌──────────────────────────┐    ┌───────────────────────────────────────┐    │
│  │      FILE SYSTEM         │    │      VITE DEV SERVER                  │    │
│  │                          │    │  (hanya untuk pengembangan)           │    │
│  │  • storage/framework/    │    │                                       │    │
│  │    sessions (file)       │    │  Port: 5173                           │    │
│  │  • storage/logs          │    │  Digunakan untuk HMR                 │    │
│  │  • storage/app/uploads   │    │  (Hot Module Replacement)             │    │
│  │  • public/images         │    └───────────────────────────────────────┘    │
│  └──────────────────────────┘                                                 │
│                                                                               │
└───────────────────────────────────────────────────────────────────────────────┘

Alur permintaan:
1. Pengguna → Browser → http://localhost/storimax/public/login
2. Apache → Meneruskan ke PHP (index.php)
3. PHP → Laravel → Route → Middleware → Controller → Model
4. Model → Query MySQL via Eloquent ORM
5. Controller → Render View (Blade)
6. PHP → Mengembalikan HTML ke Browser
```

---

## DAFTAR PUSTAKA

1. Fowler, M. (2002). _Patterns of Enterprise Application Architecture_. Addison-Wesley.
2. Pressman, R. S. (2015). _Software Engineering: A Practitioner's Approach_ (8th ed.). McGraw-Hill Education.
3. Object Management Group. (2017). _OMG Unified Modeling Language (OMG UML) Version 2.5.1_. Retrieved from https://www.omg.org/spec/UML/

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
