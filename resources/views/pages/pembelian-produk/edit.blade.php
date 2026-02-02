@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pembelian Produk" :breadcrumbs="[
    ['label' => 'Pembelian Produk', 'url' => route('pembelian-produk.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="pembelianProdukEdit()">
        <form @submit.prevent="submitForm">
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Edit Pembelian Produk</h3>
            
            <div class="mb-6 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                <span class="font-bold">Perhatian:</span> Mengedit data ini akan membatalkan status stock lama dan menerapkan perubahan baru. Pastikan status dan quantity benar.
            </div>

            <!-- Header Info -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-6">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Pembelian</label>
                    <input type="text" x-model="form.kode_pembelian" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pembelian</label>
                    <input type="date" x-model="form.tanggal_pembelian" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Supplier</label>
                    <select x-model="form.supplier_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Product Search & Table -->
            <div class="mb-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Daftar Produk</h4>
                
                <div class="relative mb-4">
                    <input type="text" x-model="searchQuery" @input="filterProduks" placeholder="Cari Produk (Ketik nama atau kode...)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    <div x-show="showSearchResults && filteredProduks.length > 0" @click.away="showSearchResults = false" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                        <ul>
                            <template x-for="produk in filteredProduks" :key="produk.id">
                                <li @click="addProduct(produk)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 flex justify-between">
                                    <span x-text="produk.nama_produk + ' (' + produk.kode_produk + ')'"></span>
                                    <span x-text="'Stok: ' + produk.aktual_stok"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 min-w-[200px]">Nama Produk</th>
                                <th class="px-4 py-3">Harga Beli</th>
                                <th class="px-4 py-3 text-center">Stok (Std/Akt)</th>
                                <th class="px-4 py-3 w-24">Qty</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3 text-center w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in form.details" :key="index">
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white" x-text="item.nama_produk"></td>
                                    <td class="px-4 py-3">
                                        Rp <span x-text="formatNumber(item.harga_satuan)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span x-text="item.standar_stok"></span> / <span x-text="item.aktual_stok"></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="item.quantity" @input="calculateLineTotal(index)" min="1" class="w-full rounded border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800" />
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        Rp <span x-text="formatNumber(item.total_harga)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeProduct(index)" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                             <tr x-show="form.details.length === 0">
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada produk dipilih. Cari dan tambahkan produk di atas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calculations & Payment Info -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Pembayaran</label>
                        <select x-model="form.status_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="order">Order</option>
                            <option value="delivery">Delivery</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                        <select x-model="form.metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pembayaran</label>
                        <textarea x-model="form.catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp <span x-text="formatNumber(subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-2">Tax (%) <input type="number" x-model.number="form.tax_percentage" @input="calculateGrandTotal" min="0" class="w-16 rounded border border-gray-300 px-2 py-1 text-xs" /></span>
                        <span class="text-red-500">+ Rp <span x-text="formatNumber(taxAmount)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-2">Discount (%) <input type="number" x-model.number="form.discount_percentage" @input="calculateGrandTotal" min="0" class="w-16 rounded border border-gray-300 px-2 py-1 text-xs" /></span>
                        <span class="text-green-500">- Rp <span x-text="formatNumber(discountAmount)"></span></span>
                    </div>
                     <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                        <span>Shipping Cost</span>
                        <input type="number" x-model.number="form.shipping_cost" @input="calculateGrandTotal" min="0" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white" placeholder="0" />
                    </div>
                        <span>Total Pembayaran</span>
                        <span>Rp <span x-text="formatNumber(form.total_pembayaran)"></span></span>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                         <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span class="font-bold text-gray-800 dark:text-white">Jumlah Bayar</span>
                            <input type="number" x-model.number="form.jumlah_bayar" @input="calculateChange" min="0" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white font-bold" placeholder="0" />
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span x-text="changeLabel"></span>
                            <span :class="changeAmount >= 0 ? 'text-green-600' : 'text-red-500'">Rp <span x-text="formatNumber(Math.abs(changeAmount))"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('pembelian-produk.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" :disabled="form.details.length === 0" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">Simpan Perubahan</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function pembelianProdukEdit() {
        return {
            produks: @json($produks),
            suppliers: @json($suppliers),
            searchQuery: '',
            showSearchResults: false,
            filteredProduks: [],
            form: {
                kode_pembelian: '{{ $pembelian->kode_pembelian }}',
                supplier_id: {{ $pembelian->supplier_id }},
                tanggal_pembelian: '{{ $pembelian->tanggal_pembelian }}',
                details: @json($details),
                tax_percentage: {{ $pembelian->tax_percentage }},
                discount_percentage: {{ $pembelian->discount_percentage }},
                shipping_cost: {{ $pembelian->shipping_cost }},
                total_pembayaran: {{ $pembelian->total_pembayaran }},
                jumlah_bayar: {{ $pembelian->jumlah_bayar }},
                status_pembayaran: '{{ $pembelian->status_pembayaran }}',
                metode_pembayaran: '{{ $pembelian->metode_pembayaran }}',
                catatan: '{{ $pembelian->catatan }}'
            },
            subtotal: 0,
            taxAmount: 0,
            discountAmount: 0,
            changeAmount: 0,
            
            init() {
                this.filteredProduks = this.produks;
                this.calculateGrandTotal();
            },
            filterProduks() {
                if (this.searchQuery === '') {
                    this.showSearchResults = false;
                    return;
                }
                const query = this.searchQuery.toLowerCase();
                this.filteredProduks = this.produks.filter(p => 
                    p.nama_produk.toLowerCase().includes(query) || 
                    p.kode_produk.toLowerCase().includes(query)
                );
                this.showSearchResults = true;
            },
            addProduct(produk) {
                // Check if already exists
                const existing = this.form.details.find(d => d.produk_id === produk.id);
                if (existing) {
                    existing.quantity++;
                    this.calculateLineTotal(this.form.details.indexOf(existing));
                } else {
                    this.form.details.push({
                        produk_id: produk.id,
                        nama_produk: produk.nama_produk,
                        standar_stok: produk.standar_stok,
                        aktual_stok: produk.aktual_stok,
                        harga_satuan: produk.harga_beli,
                        quantity: 1,
                        total_harga: produk.harga_beli
                    });
                }
                this.searchQuery = '';
                this.showSearchResults = false;
                this.calculateGrandTotal();
            },
            removeProduct(index) {
                this.form.details.splice(index, 1);
                this.calculateGrandTotal();
            },
            calculateLineTotal(index) {
                const item = this.form.details[index];
                item.total_harga = item.quantity * item.harga_satuan;
                this.calculateGrandTotal();
            },
            calculateGrandTotal() {
                this.subtotal = this.form.details.reduce((sum, item) => sum + parseFloat(item.total_harga), 0);
                this.taxAmount = (this.subtotal * this.form.tax_percentage) / 100;
                this.discountAmount = (this.subtotal * this.form.discount_percentage) / 100;
                
                const shipping = parseFloat(this.form.shipping_cost) || 0;
                
                this.form.total_pembayaran = this.subtotal + this.taxAmount - this.discountAmount + shipping;
                this.calculateChange();
            },
            calculateChange() {
                this.changeAmount = this.form.jumlah_bayar - this.form.total_pembayaran;
            },
            get changeLabel() {
                return this.changeAmount >= 0 ? 'Kembali' : 'Kekurangan';
            },
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num));
            },
            submitForm() {
                fetch('{{ route('pembelian-produk.update', $pembelian->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada sistem');
                });
            }
        }
    }
</script>
@endsection
