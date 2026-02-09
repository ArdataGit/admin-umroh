@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Transaksi Layanan" :breadcrumbs="[
    ['label' => 'Transaksi Layanan', 'url' => route('transaksi-layanan.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="transaksiLayanan()">
        <form @submit.prevent="submitForm">
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Transaksi Layanan</h3>
            
            <!-- Header Info -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-6">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Transaksi</label>
                    <input type="text" x-model="form.kode_transaksi" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Transaksi</label>
                    <input type="date" x-model="form.tanggal_transaksi" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Pelanggan</label>
                    <select x-model="form.pelanggan_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Service Search & Table -->
            <div class="mb-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Daftar Layanan</h4>
                
                <div class="relative mb-4">
                    <input type="text" x-model="searchQuery" @input="filterLayanans" placeholder="Cari Layanan..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    <div x-show="showSearchResults && filteredLayanans.length > 0" @click.away="showSearchResults = false" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                        <ul>
                            <template x-for="layanan in filteredLayanans" :key="layanan.id">
                                <li @click="addLayanan(layanan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 flex justify-between">
                                    <span x-text="layanan.nama_layanan + ' (' + layanan.kode_layanan + ')'"></span>
                                    <span x-text="layanan.kurs && layanan.kurs !== 'IDR' ? 
                                        ((layanan.kurs === 'MYR' ? 'RM' : layanan.kurs) + ' ' + formatNumberDecimal(layanan.harga_jual_asing) + ' (Rp ' + formatNumber(layanan.harga_jual) + ')') : 
                                        'Harga: Rp ' + formatNumber(layanan.harga_jual)"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 min-w-[200px]">Nama Layanan</th>
                                <th class="px-4 py-3">Harga Satuan</th>
                                <th class="px-4 py-3 w-24">Qty</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3 text-center w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in form.details" :key="index">
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white" x-text="item.nama_layanan"></td>
                                    <td class="px-4 py-3">
                                        <template x-if="item.kurs && item.kurs !== 'IDR'">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-blue-600 dark:text-blue-400" x-text="(item.kurs === 'MYR' ? 'RM' : item.kurs) + ' ' + formatNumberDecimal(item.harga_jual_asing)"></span>
                                                <span class="text-xs text-gray-500" x-text="'(Rp ' + formatNumber(item.harga_satuan) + ')'"></span>
                                            </div>
                                        </template>
                                        <template x-if="!item.kurs || item.kurs === 'IDR'">
                                            <span>Rp <span x-text="formatNumber(item.harga_satuan)"></span></span>
                                        </template>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="item.quantity" @input="calculateLineTotal(index)" min="1" class="w-full rounded border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800" />
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        Rp <span x-text="formatNumber(item.total_harga)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeLayanan(index)" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                             <tr x-show="form.details.length === 0">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada layanan dipilih. Cari dan tambahkan layanan di atas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calculations & Transaction Info -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Transaksi</label>
                        <select x-model="form.status_transaksi" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="process">Process</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Transaksi</label>
                        <textarea x-model="form.alamat_transaksi" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan</label>
                        <textarea x-model="form.catatan" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
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
                        <span>Biaya Tambahan (Shipping)</span>
                        <input type="number" x-model.number="form.shipping_cost" @input="calculateGrandTotal" min="0" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white" placeholder="0" />
                    </div>
                        <span>Total Transaksi</span>
                        <span>Rp <span x-text="formatNumber(form.total_transaksi)"></span></span>
                    </div>

                    <!-- Payment Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3 space-y-3">
                        <h4 class="font-medium text-gray-800 dark:text-white">Pembayaran Awal</h4>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                            <select x-model="form.metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">Pilih Metode</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="debit">Debit Card</option>
                                <option value="credit">Credit Card</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                             <span>Jumlah Bayar</span>
                             <input type="number" x-model.number="form.jumlah_bayar" @input="calculateSisa" min="0" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white" placeholder="0" />
                        </div>
                        <div class="flex justify-between items-center text-sm font-bold text-gray-800 dark:text-white">
                             <span>Sisa Tagihan</span>
                             <span>Rp <span x-text="formatNumber(sisaTagihan)"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('transaksi-layanan.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" :disabled="form.details.length === 0" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">Simpan Transaksi</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function transaksiLayanan() {
        return {
            layanans: @json($layanans),
            pelanggans: @json($pelanggans),
            searchQuery: '',
            showSearchResults: false,
            filteredLayanans: [],
            form: {
                kode_transaksi: '{{ $kodeTransaksi }}',
                pelanggan_id: '',
                tanggal_transaksi: '{{ date('Y-m-d') }}',
                details: [],
                tax_percentage: 0,
                discount_percentage: 0,
                shipping_cost: 0,
                total_transaksi: 0,
                status_transaksi: 'process',
                alamat_transaksi: '',
                catatan: '',
                // Payment Fields
                jumlah_bayar: 0,
                metode_pembayaran: ''
            },
            subtotal: 0,
            taxAmount: 0,
            discountAmount: 0,
            sisaTagihan: 0,
            
            init() {
                this.filteredLayanans = this.layanans;
            },
            filterLayanans() {
                if (this.searchQuery === '') {
                    this.showSearchResults = false;
                    return;
                }
                const query = this.searchQuery.toLowerCase();
                this.filteredLayanans = this.layanans.filter(l => 
                    l.nama_layanan.toLowerCase().includes(query) || 
                    l.kode_layanan.toLowerCase().includes(query)
                );
                this.showSearchResults = true;
            },
            addLayanan(layanan) {
                // Check if already exists
                const existing = this.form.details.find(d => d.layanan_id === layanan.id);
                if (existing) {
                    existing.quantity++;
                    this.calculateLineTotal(this.form.details.indexOf(existing));
                } else {
                    this.form.details.push({
                        layanan_id: layanan.id,
                        nama_layanan: layanan.nama_layanan,
                        kurs: layanan.kurs,
                        harga_jual_asing: parseFloat(layanan.harga_jual_asing) || 0,
                        harga_satuan: layanan.harga_jual,
                        quantity: 1,
                        total_harga: layanan.harga_jual
                    });
                }
                this.searchQuery = '';
                this.showSearchResults = false;
                this.calculateGrandTotal();
            },
            removeLayanan(index) {
                this.form.details.splice(index, 1);
                this.calculateGrandTotal();
            },
            calculateLineTotal(index) {
                const item = this.form.details[index];
                item.total_harga = item.quantity * item.harga_satuan;
                this.calculateGrandTotal();
            },
            calculateGrandTotal() {
                this.subtotal = this.form.details.reduce((sum, item) => sum + item.total_harga, 0);
                this.taxAmount = (this.subtotal * this.form.tax_percentage) / 100;
                this.discountAmount = (this.subtotal * this.form.discount_percentage) / 100;
                
                const shipping = parseFloat(this.form.shipping_cost) || 0;
                
                this.form.total_transaksi = this.subtotal + this.taxAmount - this.discountAmount + shipping;
                this.calculateSisa();
            },
            calculateSisa() {
                this.sisaTagihan = this.form.total_transaksi - this.form.jumlah_bayar;
            },
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num));
            },
            formatNumberDecimal(num) {
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
            },
            submitForm() {
                fetch('{{ route('transaksi-layanan.store') }}', {
                    method: 'POST',
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
