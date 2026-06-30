@extends('layouts.app')

@section('title', 'Buat Invoice')
@section('header', 'Buat Invoice')
@section('subheader', 'Sesuai format Storimax')

@section('content')
<div class="max-w-3xl" x-data="invoiceForm()">
    <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-6">
        @csrf

        {{-- Project & tanggal --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-medium text-gray-900">Informasi Invoice</h3>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Project <span class="text-red-400">*</span></label>
                <select name="project_id" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="">Pilih project...</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}">
                        {{ $project->code }} — {{ $project->name }} ({{ $project->client->contact_name }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tgl Invoice <span class="text-red-400">*</span></label>
                    <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tgl Sesi</label>
                    <input type="date" name="session_date"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jatuh Tempo</label>
                    <input type="date" name="due_date"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
        </div>

        {{-- Rincian items --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Rincian Layanan</h3>
                <button type="button" @click="addItem()"
                        class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    + Tambah Item
                </button>
            </div>

            <template x-for="(item, index) in items" :key="index">
                <div class="border border-gray-100 rounded-lg p-4 mb-3 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400" x-text="'Item ' + (index + 1)"></span>
                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="text-xs text-red-400 hover:text-red-600">Hapus</button>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Layanan</label>
                        <input type="text" :name="'items['+index+'][service_name]'" x-model="item.service_name"
                               placeholder="misal: Foto Pre-wedding"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                        <input type="text" :name="'items['+index+'][description]'" x-model="item.description"
                               placeholder="Detail layanan..."
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Harga (Rp)</label>
                            <input type="number" :name="'items['+index+'][price]'" x-model.number="item.price"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Disc (%)</label>
                            <input type="number" :name="'items['+index+'][disc_percent]'" x-model.number="item.disc_percent"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-gray-900">
                        </div>
                    </div>
                </div>
            </template>

            {{-- Subtotal preview --}}
            <div class="pt-3 border-t border-gray-100 text-right">
                <span class="text-xs text-gray-400">Subtotal (estimasi): </span>
                <span class="text-sm font-medium text-gray-900" x-text="formatRupiah(subtotal())"></span>
            </div>
        </div>

        {{-- PPH & DP --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-medium text-gray-900">Pajak & DP</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">PPH (%)</label>
                    <input type="number" name="pph_rate" value="2" step="0.01"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">DP (Rp)</label>
                    <input type="number" name="dp_amount" value="0"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
        </div>

        {{-- Metode pembayaran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-medium text-gray-900">Metode Pembayaran</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bank</label>
                    <input type="text" name="bank_name" value="BCA"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Rekening</label>
                    <input type="text" name="bank_account" value="0191040839"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Atas Nama</label>
                    <input type="text" name="bank_holder" value="PT JALUR TENGAH KREASINDO"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Pembayaran</label>
                <textarea name="payment_notes" rows="2"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                 focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none"
                >Mohon lakukan pembayaran maksimal 1x24 jam setelah invoice diterima. Terima kasih telah mempercayakan momen berharga Anda bersama Storimax.</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                Buat Invoice
            </button>
            <a href="{{ route('admin.invoices.index') }}"
               class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function invoiceForm() {
    return {
        items: [{ service_name: '', description: '', price: 0, disc_percent: 0 }],
        addItem() {
            this.items.push({ service_name: '', description: '', price: 0, disc_percent: 0 });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        subtotal() {
            return this.items.reduce((sum, item) => {
                const price = parseFloat(item.price) || 0;
                const disc  = parseFloat(item.disc_percent) || 0;
                return sum + (price - (price * disc / 100));
            }, 0);
        },
        formatRupiah(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }
    }
}
</script>
@endsection