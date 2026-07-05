# PENGUJIAN SISTEM
## Sistem Informasi Manajemen Agency Kreatif — Storimax
### PT Jalur Tengah Kreasindo

| Identitas Dokumen | |
|--------------------|-|
| **Judul** | Pengujian Sistem |
| **Topik** | Sistem Informasi Manajemen Agency Kreatif |
| **Lembaga** | PT Jalur Tengah Kreasindo |
| **Versi** | 1.0 |
| **Tanggal** | 5 Juli 2026 |

---

## DAFTAR ISI

1. Pendahuluan
2. Lingkungan Pengujian
3. Pengujian Unit
   3.1. Strategi Pengujian
   3.2. Hasil Pengujian Unit
   3.3. Rangkuman Pengujian Unit
4. Pengujian Faktur PDF
5. Pengujian Manual
   5.1. Skenario Autentikasi
   5.2. Skenario Otorisasi
   5.3. Skenario CRUD
   5.4. Skenario Alur Status
6. Pengujian Keamanan
7. Temuan dan Solusi
8. Kesimpulan Pengujian

---

## 1. PENDAHULUAN

Pengujian perangkat lunak merupakan salah satu tahapan kritis dalam siklus pengembangan perangkat lunak yang bertujuan untuk memastikan bahwa sistem yang dikembangkan telah sesuai dengan spesifikasi kebutuhan yang telah ditetapkan. Menurut Pressman (2015), pengujian perangkat lunak adalah proses mengeksekusi program dengan maksud menemukan kesalahan _(error)_ yang belum terdeteksi sebelumnya.

Pengujian pada penelitian ini dilakukan dalam dua kategori utama:
1. **Pengujian Unit** — menggunakan kerangka kerja PHPUnit secara terotomatisasi
2. **Pengujian Manual** — dilakukan secara langsung oleh peneliti melalui peramban web

---

## 2. LINGKUNGAN PENGUJIAN

Pengujian dilakukan pada lingkungan dengan spesifikasi sebagai berikut:

| Komponen | Spesifikasi |
|----------|-------------|
| Sistem Operasi | Windows |
| Server Lokal | XAMPP 8.2.12 |
| Bahasa Pemrograman | PHP 8.2.12 |
| Basis Data | MySQL 8 |
| Kerangka Kerja | Laravel 12 |
| Alat Pengujian | PHPUnit ^11.5.50 |
| Pustaka PDF | barryvdh/laravel-dompdf ^3.1 |

---

## 3. PENGUJIAN UNIT

### 3.1 Strategi Pengujian

Pengujian unit dilakukan pada tiga pengendali _(controller)_ utama, yaitu ProjectController, InvoiceController, dan JobController. Setiap kelas pengujian menggunakan _trait_ `RefreshDatabase` yang disediakan oleh Laravel untuk mereset basis data sebelum setiap metode pengujian dijalankan. Pendekatan ini memastikan bahwa setiap pengujian dimulai dari kondisi basis data yang bersih dan terisolasi.

Untuk menangani perlindungan CSRF yang diterapkan oleh Laravel, dikembangkan metode _helper_ `withCsrf()` yang memulai sesi dan menghasilkan token CSRF yang valid tanpa harus menonaktifkan _middleware_ secara keseluruhan:

```php
private function withCsrf(array $data): array
{
    $this->app['session']->start();
    return array_merge(['_token' => csrf_token()], $data);
}
```

Pendekatan ini dipilih karena menonaktifkan _middleware_ secara keseluruhan (menggunakan _trait_ `WithoutMiddleware`) akan menyebabkan tampilan tidak dapat di-render dengan benar, khususnya kehilangan variabel `$errors` yang disediakan oleh _middleware_ `ShareErrorsFromSession`.

### 3.2 Hasil Pengujian Unit

#### 3.2.1 Pengujian ProjectController (11 Kasus)

| Kode | Nama Kasus Uji | Status | Deskripsi |
|:----:|----------------|:------:|-----------|
| P-01 | `it_can_list_projects` | ✓ Lulus | Halaman indeks proyek menampilkan daftar proyek |
| P-02 | `it_can_show_create_form` | ✓ Lulus | Formulir pembuatan proyek tampil dengan benar |
| P-03 | `it_can_store_a_new_project` | ✓ Lulus | Penyimpanan proyek baru dengan kode otomatis |
| P-04 | `it_validates_required_fields_when_storing` | ✓ Lulus | Validasi kolom wajib saat menyimpan proyek |
| P-05 | `it_can_show_a_project` | ✓ Lulus | Halaman detail proyek menampilkan informasi sesuai |
| P-06 | `it_can_show_edit_form` | ✓ Lulus | Formulir pengeditan proyek tampil dengan benar |
| P-07 | `it_can_update_a_project` | ✓ Lulus | Pembaruan data proyek berhasil disimpan |
| P-08 | `it_can_soft_delete_a_project` | ✓ Lulus | Penghapusan lunak proyek berfungsi |
| P-09 | `it_can_update_project_status` | ✓ Lulus | Perubahan status proyek (draft ke active) berhasil |
| P-10 | `it_validates_status_transition` | ✓ Lulus | Status tidak valid ditolak oleh sistem |
| P-11 | `it_returns_empty_list_when_no_projects` | ✓ Lulus | Halaman kosong saat belum ada proyek |

#### 3.2.2 Pengujian InvoiceController (14 Kasus)

| Kode | Nama Kasus Uji | Status | Deskripsi |
|:----:|----------------|:------:|-----------|
| I-01 | `it_can_list_invoices` | ✓ Lulus | Halaman indeks faktur menampilkan daftar faktur |
| I-02 | `it_can_show_create_form` | ✓ Lulus | Formulir pembuatan faktur tampil dengan benar |
| I-03 | `it_can_store_invoice_with_items` | ✓ Lulus | Penyimpanan faktur dengan dua item, PPh 2%, diskon 10% |
| I-04 | `it_validates_required_fields_when_storing` | ✓ Lulus | Validasi kolom wajib saat menyimpan faktur |
| I-05 | `it_can_show_invoice_detail` | ✓ Lulus | Halaman detail faktur menampilkan informasi sesuai |
| I-06 | `it_can_update_status_to_sent` | ✓ Lulus | Perubahan status draft ke sent dengan timestamp sent_at |
| I-07 | `it_can_update_status_to_dp_paid` | ✓ Lulus | Perubahan status sent ke dp_paid dengan timestamp |
| I-08 | `it_can_update_status_to_paid` | ✓ Lulus | Perubahan status ke paid dengan pengisian dp_paid = total |
| I-09 | `it_validates_invoice_status_transition` | ✓ Lulus | Status tidak valid ditolak oleh sistem |
| I-10 | `it_can_delete_an_invoice` | ✓ Lulus | Penghapusan lunak faktur berfungsi |
| I-11 | `it_can_download_invoice_pdf` | ✓ Lulus | Unduh PDF faktur berhasil (HTTP 200, Content-Type PDF) |
| I-12 | `it_returns_empty_list_when_no_invoices` | ✓ Lulus | Halaman kosong saat belum ada faktur |
| I-13 | `it_can_handle_zero_dp` | ✓ Lulus | Faktur dengan DP 0 dan PPh 0 terproses dengan benar |
| I-14 | `it_includes_items_in_invoice_creation` | ✓ Lulus | Item faktur dengan diskon 20% tersimpan dengan benar |

#### 3.2.3 Pengujian JobController (17 Kasus)

| Kode | Nama Kasus Uji | Status | Deskripsi |
|:----:|----------------|:------:|-----------|
| J-01 | `it_can_list_all_jobs` | ✓ Lulus | Halaman indeks pekerjaan menampilkan daftar pekerjaan |
| J-02 | `it_can_show_create_form` | ✓ Lulus | Formulir pembuatan pekerjaan dengan daftar tim dan kru |
| J-03 | `it_can_store_a_job` | ✓ Lulus | Penyimpanan pekerjaan baru dengan penugasan kru |
| J-04 | `it_can_store_job_without_assignee` | ✓ Lulus | Pekerjaan tanpa penugasan tetap dapat dibuat |
| J-05 | `it_validates_required_fields_when_storing` | ✓ Lulus | Validasi kolom wajib saat menyimpan pekerjaan |
| J-06 | `it_can_show_job_detail` | ✓ Lulus | Halaman detail pekerjaan menampilkan informasi sesuai |
| J-07 | `it_can_show_edit_form` | ✓ Lulus | Formulir pengeditan pekerjaan tampil dengan benar |
| J-08 | `it_can_update_a_job` | ✓ Lulus | Pembaruan data pekerjaan berhasil disimpan |
| J-09 | `it_can_delete_a_job` | ✓ Lulus | Penghapusan lunak pekerjaan berfungsi |
| J-10 | `it_can_update_job_status_to_in_progress` | ✓ Lulus | Perubahan status todo ke inprogress dengan log |
| J-11 | `it_can_update_job_status_through_full_cycle` | ✓ Lulus | Siklus penuh: todo→inprogress→review→done |
| J-12 | `it_logs_every_status_change` | ✓ Lulus | Setiap perubahan status tercatat di tabel job_logs |
| J-13 | `it_validates_job_status_transition` | ✓ Lulus | Status tidak valid ditolak oleh sistem |
| J-14 | `show_returns_404_for_non_existent_job` | ✓ Lulus | UUID tidak ditemukan mengembalikan HTTP 404 |
| J-15 | `it_returns_empty_list_when_no_jobs` | ✓ Lulus | Halaman kosong saat belum ada pekerjaan |
| J-16 | `it_can_handle_job_without_team` | ✓ Lulus | Pekerjaan tanpa tim tetap dapat ditampilkan |
| J-17 | `it_can_update_job_with_note` | ✓ Lulus | Pembaruan status dengan catatan tersimpan di log |

### 3.3 Rangkuman Pengujian Unit

| Kelas Pengujian | Jumlah Kasus | Lulus | Gagal | Tingkat Keberhasilan |
|-----------------|:------------:|:-----:|:-----:|:--------------------:|
| ProjectControllerTest | 11 | 11 | 0 | **100%** |
| InvoiceControllerTest | 14 | 14 | 0 | **100%** |
| JobControllerTest | 17 | 17 | 0 | **100%** |
| **Total** | **42** | **42** | **0** | **✓ 100%** |

---

## 4. PENGUJIAN FAKTUR PDF

### 4.1 Latar Belakang Masalah

Salah satu fitur utama sistem adalah kemampuan untuk mengunduh faktur dalam format PDF. Pustaka DOMPDF yang digunakan untuk menghasilkan PDF memerlukan ekstensi PHP GD untuk memproses berkas gambar. Pada lingkungan pengujian CLI PHP di XAMPP, ekstensi GD tidak aktif sehingga menghasilkan pesan galat:

```
The PHP GD extension is required, but is not installed.
vendor/dompdf/dompdf/lib/Cpdf.php:6226
```

### 4.2 Solusi yang Diterapkan

Logo perusahaan yang sebelumnya dimuat dari berkas fisik melalui fungsi `public_path()` diubah menjadi _base64 data URI_ yang disematkan langsung dalam _template_ HTML PDF:

```blade
@if(file_exists(public_path('images/logo.png')))
<img src="data:image/png;base64,{{ base64_encode(
    file_get_contents(public_path('images/logo.png'))
) }}" ...>
@else
<span>STORIMAX</span>
@endif
```

Fungsi `file_get_contents()` membaca berkas sebagai data biner dan tidak memerlukan ekstensi GD, berbeda dengan fungsi `imagecreatefrompng()` yang memerlukan GD untuk memproses gambar.

### 4.3 Hasil Pengujian PDF

| Aspek | Hasil |
|-------|-------|
| Pembuatan PDF | ✓ Berhasil |
| Ukuran Berkas | ~2 MB (peningkatan dari ~860 KB akibat _overhead_ base64) |
| Logo Tampil | ✓ Ya |
| Tata Letak | ✓ Profesional, dua kolom |
| Unduh | ✓ Berfungsi (nama berkas: INV-STX-2026-NNN.pdf) |
| Galat GD | ✓ Tidak muncul |

---

## 5. PENGUJIAN MANUAL

### 5.1 Skenario Autentikasi

| Skenario | Hasil yang Diharapkan | Hasil |
|----------|----------------------|:-----:|
| _Login_ dengan surel dan kata sandi benar | Dialihkan ke dasbor sesuai peran | ✓ |
| _Login_ dengan surel salah | Muncul pesan galat "Email atau password salah" | ✓ |
| _Login_ dengan kata sandi salah | Muncul pesan galat "Email atau password salah" | ✓ |
| _Login_ sebagai administrator | Dialihkan ke `/admin/dashboard` | ✓ |
| _Login_ sebagai atasan | Dialihkan ke `/admin/dashboard` | ✓ |
| _Login_ sebagai kru | Dialihkan ke `/crew/dashboard` | ✓ |
| _Login_ sebagai klien | Dialihkan ke `/client/dashboard` | ✓ |
| Mengakses halaman tanpa _login_ | Dialihkan ke halaman _login_ | ✓ |
| _Logout_ | Dialihkan ke halaman _login_ + sesi dihapus | ✓ |

### 5.2 Skenario Otorisasi

| Skenario | Hasil yang Diharapkan | Hasil |
|----------|----------------------|:-----:|
| Kru mengakses halaman `/admin/*` | HTTP 403 Forbidden | ✓ |
| Klien mengakses halaman `/admin/*` | HTTP 403 Forbidden | ✓ |
| Klien mengakses halaman `/crew/*` | HTTP 403 Forbidden | ✓ |
| Administrator mengakses halaman `/crew/*` | HTTP 403 Forbidden | ✓ |
| Administrator mengakses halaman `/client/*` | HTTP 403 Forbidden | ✓ |
| Pengguna belum _login_ mengakses halaman terproteksi | Dialihkan ke halaman _login_ | ✓ |

### 5.3 Skenario CRUD

| Modul | _Create_ | _Read_ | _Update_ | _Delete_ | Penghapusan Lunak |
|-------|:--------:|:------:|:--------:|:--------:|:-----------------:|
| Departemen | ✓ | ✓ | ✓ | ✓ | N/A |
| Pengguna | ✓ | ✓ | ✓ | ✓ Nonaktifkan | ✓ |
| Klien | ✓ | ✓ | ✓ | ✓ | ✓ |
| Proyek | ✓ | ✓ | ✓ | ✓ | ✓ |
| Tim Proyek | ✓ | ✓ | N/A | ✓ | N/A |
| Pekerjaan | ✓ | ✓ | ✓ | ✓ | ✓ |
| Faktur | ✓ | ✓ | N/A _(by design)_ | ✓ | ✓ |
| Portofolio | ✓ | ✓ | ✓ | ✓ | ✓ |

### 5.4 Skenario Alur Status

#### Status Pekerjaan

| Transisi | Validitas | Hasil |
|----------|:---------:|:-----:|
| todo → inprogress | ✓ Valid | ✓ |
| todo → review | ✓ Valid | ✓ |
| todo → done | ✓ Valid | ✓ |
| inprogress → review | ✓ Valid | ✓ |
| inprogress → done | ✓ Valid | ✓ |
| review → done | ✓ Valid | ✓ |
| review → inprogress | ✓ Valid | ✓ |
| done → inprogress | ✓ Valid | ✓ |
| Status tidak valid | ✗ Ditolak | ✓ |

#### Status Faktur

| Transisi | Validitas | Hasil |
|----------|:---------:|:-----:|
| draft → sent | ✓ Valid | ✓ + sent_at |
| sent → dp_paid | ✓ Valid | ✓ + dp_paid_at |
| dp_paid → paid | ✓ Valid | ✓ + paid_at |
| draft → overdue | ✓ Valid | ✓ |
| sent → overdue | ✓ Valid | ✓ |
| Status tidak valid | ✗ Ditolak | ✓ |

---

## 6. PENGUJIAN KEAMANAN

| Aspek | Status | Keterangan |
|-------|:------:|------------|
| Perlindungan CSRF | ✓ Terpenuhi | Seluruh formulir mutasi menggunakan `@csrf` |
| _Hashing_ Kata Sandi | ✓ Terpenuhi | Menggunakan bcrypt (`Hash::make()`) |
| _Middleware_ Autentikasi | ✓ Terpenuhi | Seluruh _route_ kecuali _login_ dilindungi |
| _Middleware_ Peran | ✓ Terpenuhi | CheckRole dengan parameter peran |
| UUID Kunci Primer | ✓ Terpenuhi | Tidak sequential, tidak dapat ditebak |
| Penghapusan Lunak | ✓ Terpenuhi | Data tidak hilang permanen |
| _Driver_ Sesi | ✓ Terpenuhi | Berkas pada sisi server |

---

## 7. TEMUAN DAN SOLUSI

| Permasalahan | Solusi | Status |
|-------------|--------|:------:|
| Ekstensi GD tidak tersedia di CLI PHP | _Base64 data URI_ untuk logo pada PDF | ✓ Teratasi |
| Nama berkas unduhan mengandung "/" | `str_replace('/', '-', $invoiceNumber)` | ✓ Teratasi |
| Faktur belum dapat diedit setelah dibuat | _By design_ — faktur sebagai dokumen resmi | ✓ Diterima |
| Ukuran PDF ~2 MB | Dapat dioptimasi pada pengembangan selanjutnya | ⚠ Catatan |

---

## 8. KESIMPULAN PENGUJIAN

Berdasarkan hasil pengujian yang telah dilakukan, dapat disimpulkan bahwa:

1. Seluruh **42 kasus uji unit lulus** dengan tingkat keberhasilan 100%, mencakup pengujian fungsionalitas pengendali proyek, faktur, dan pekerjaan.
2. **Fitur unduh PDF faktur berfungsi** dengan baik setelah mengimplementasikan solusi _base64 data URI_ untuk mengatasi ketergantungan pada ekstensi GD.
3. **Seluruh operasi CRUD** pada delapan modul utama berfungsi sesuai spesifikasi.
4. **Alur status** pada proyek, pekerjaan, dan faktur berjalan sesuai dengan logika bisnis yang telah ditetapkan.
5. **Sistem otorisasi** berjalan dengan baik — setiap peran pengguna terisolasi dengan benar sesuai hak aksesnya.
6. **Aspek keamanan** terpenuhi melalui penerapan CSRF, bcrypt, UUID, dan _middleware_ berlapis.

Berdasarkan hasil tersebut, sistem dinyatakan **stabil dan siap untuk digunakan** dalam lingkungan operasional.

---

## DAFTAR PUSTAKA

1. Pressman, R. S. (2015). _Software Engineering: A Practitioner's Approach_ (8th ed.). McGraw-Hill Education.
2. Myers, G. J., Sandler, C., & Badgett, T. (2011). _The Art of Software Testing_ (3rd ed.). John Wiley & Sons.
3. Laravel. (2026). _HTTP Testing Documentation_. Retrieved from https://laravel.com/docs/12/http-tests

---

> Dokumen ini disusun sebagai bagian dari laporan penelitian tugas akhir.
