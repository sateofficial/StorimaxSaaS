# INVOICE

**No:** {{invoice_number}}
**Client:** {{client_name}}

---

## Kepada

| | |
|---|---|
| **Client** | {{client_name}} |
| **Perusahaan** | {{company_name}} |
| **Kontak** | {{client_phone}} |
| **Instagram** | {{client_instagram}} |
| **Alamat** | {{client_address}} |

## Detail Project

| | |
|---|---|
| **Project** | {{project_name}} |
| **Tgl Invoice** | {{invoice_date}} |
| **Tgl Sesi** | {{session_date}} |
| **Jatuh Tempo** | {{due_date}} |

---

## Rincian Layanan

<!--html-->
{{items_table}}
<!--/html-->

---

## Ringkasan

<!--html-->
{{summary_table}}
<!--/html-->

---

## Pembayaran

**Bank:** {{bank_name}}
**No. Rekening:** {{bank_account}}
**Atas Nama:** {{bank_holder}}

_{{payment_notes}}_
