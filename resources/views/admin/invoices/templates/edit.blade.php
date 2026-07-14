@extends('layouts.app')

@section('title', 'Edit Template Invoice')
@section('header', 'Edit Template: ' . $template->name)
@section('subheader', 'Edit struktur Markdown untuk PDF invoice')

@section('content')
<div class="max-w-5xl">
    <div class="bg-white rounded-xl border border-gray-100 p-6" x-data="{ preview: false, content: '' }">
        <form method="POST" action="{{ $template->exists ? route('admin.invoices.templates.update', $template) : route('admin.invoices.templates.store') }}" class="space-y-4">
            @csrf
            @if($template->exists) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Template</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes', $template->notes) }}"
                           placeholder="misal: Template dengan header premium"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            {{-- Markdown Editor --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-medium text-gray-600">Konten Markdown</label>
                    <button type="button" @click="preview = !preview"
                            class="text-xs px-2 py-1 border border-gray-200 rounded hover:bg-gray-50 transition"
                            x-text="preview ? 'Edit' : 'Preview'">
                        Preview
                    </button>
                </div>

                <template x-if="!preview">
                    <textarea name="content" rows="20" x-model="content"
                              class="w-full px-3 py-2 text-sm font-mono border border-gray-200 rounded-lg
                                     focus:outline-none focus:ring-2 focus:ring-gray-900 resize-y leading-relaxed"
                    >{{ old('content', $template->content) }}</textarea>
                </template>

                <template x-if="preview">
                    <div class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 min-h-[400px] overflow-auto">
                        <div class="prose prose-sm max-w-none">
                            <pre class="text-xs text-gray-500 whitespace-pre-wrap">{{ old('content', $template->content) }}</pre>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Variable Reference --}}
            <details class="border border-gray-100 rounded-lg">
                <summary class="text-xs font-medium text-gray-600 px-3 py-2 cursor-pointer hover:bg-gray-50 rounded-lg">
                    📖 Lihat daftar variabel yang tersedia
                </summary>
                <div class="px-3 py-2 text-xs text-gray-500 space-y-1 border-t border-gray-100">
                    <p>Gunakan <code class="text-blue-600">&#123;&#123;variable&#125;&#125;</code> di template Markdown. Akan diganti otomatis dengan data invoice.</p>
                    <table class="w-full text-xs mt-2">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left py-1 px-2 font-medium text-gray-600">Variabel</th>
                                <th class="text-left py-1 px-2 font-medium text-gray-600">Contoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;invoice_number&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">INV/STX/2026/001</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;client_name&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">Dewi Sartika</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;company_name&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">PT Maju Jaya</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;client_phone&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">08123456789</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;client_instagram&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">@dewisartika</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;client_address&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">Jl. Merdeka No. 1</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;project_name&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">Foto Prewedding</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;invoice_date&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">13 Jul 2026</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;session_date&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">10 Jul 2026</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;due_date&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">20 Jul 2026</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;bank_name&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">BCA</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;bank_account&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">0191040839</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;bank_holder&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">PT JALUR TENGAH KREASINDO</td></tr>
                            <tr><td class="py-0.5 px-2"><code>&#123;&#123;payment_notes&#125;&#125;</code></td><td class="py-0.5 px-2 text-gray-400">Mohon lakukan pembayaran...</td></tr>
                        </tbody>
                    </table>
                    <p class="mt-1 text-gray-400">⚠️ Bagian <code class="text-blue-600">&lt;!--html--&gt;...&lt;!--/html--&gt;</code> digunakan untuk tabel dinamis (items & summary). Jangan dihapus.</p>
                </div>
            </details>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                    {{ $template->exists ? 'Simpan Template' : 'Buat Template' }}
                </button>
                <a href="{{ route('admin.invoices.templates.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection