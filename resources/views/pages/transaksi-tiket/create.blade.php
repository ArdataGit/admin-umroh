@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Transaksi Tiket" :breadcrumbs="[
    ['label' => 'Transaksi Tiket', 'url' => route('transaksi-tiket.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="transaksiTiket()">
        <form @submit.prevent="submitForm">
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Transaksi Tiket</h3>
            
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
                 <div class="relative" @click.away="showPelangganDropdown = false">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Pelanggan</label>
                    <div class="relative">
                        <input type="text" x-model="searchPelanggan" @focus="showPelangganDropdown = true" @input="showPelangganDropdown = true; form.pelanggan_id = ''" placeholder="Cari atau pilih pelanggan..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div x-show="showPelangganDropdown" style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                        <ul>
                            <template x-for="pelanggan in filteredPelanggans" :key="pelanggan.id">
                                <li @click="selectPelanggan(pelanggan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 border-b dark:border-gray-700 last:border-0">
                                    <span x-text="pelanggan.nama_pelanggan"></span>
                                </li>
                            </template>
                            <li x-show="filteredPelanggans.length === 0" class="px-4 py-2 text-sm text-gray-500">
                                Pelanggan tidak ditemukan.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ticket Search & Table -->
            <div class="mb-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Daftar Tiket</h4>
                
                <div class="relative mb-4">
                    <input type="text" x-model="searchQuery" @input="filterTickets" placeholder="Cari Tiket..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    <div x-show="showSearchResults && filteredTickets.length > 0" @click.away="showSearchResults = false" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                        <ul>
                            <template x-for="ticket in filteredTickets" :key="ticket.id">
                                <li @click="addTicket(ticket)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 flex justify-between">
                                    <span x-text="ticket.nama_tiket + ' (' + ticket.kode_tiket + ') - Stok: ' + ticket.jumlah_tiket"></span>
                                    <span x-text="ticket.kurs && ticket.kurs !== 'IDR' ? 
                                        ((ticket.kurs === 'MYR' ? 'RM' : ticket.kurs) + ' ' + formatNumberDecimal(ticket.harga_jual_asing) + ' (Rp ' + formatNumber(ticket.harga_jual) + ')') : 
                                        'Harga: Rp ' + formatNumber(ticket.harga_jual)"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 min-w-[200px]">Nama Tiket</th>
                                <th class="px-4 py-3">Harga Satuan</th>
                                <th class="px-4 py-3 w-24">Qty</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3 text-center w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in form.details" :key="index">
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        <p x-text="item.nama_tiket"></p>
                                        <p class="text-xs text-gray-500" x-text="'Stok: ' + item.stok"></p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <template x-if="item.kurs && item.kurs !== 'IDR'">
                                            <div class="flex flex-col gap-2">
                                                <!-- Harga Jual Asing -->
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400 w-8" x-text="(item.kurs === 'MYR' ? 'RM' : item.kurs)"></span>
                                                    <input type="number" step="0.01" x-model.number="item.harga_jual_asing" @input="item.harga_satuan = Math.round(item.harga_jual_asing * item.rate); calculateLineTotal(index)" class="w-full rounded border border-gray-300 bg-white px-2 py-1.5 text-xs focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                                                </div>
                                                
                                                <!-- Editable Rate (Kurs) -->
                                                <div class="flex items-center gap-2 bg-blue-50/50 dark:bg-blue-900/10 p-1.5 rounded border border-blue-100 dark:border-blue-900/30">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 w-8">Kurs</span>
                                                    <input type="number" step="0.01" x-model.number="item.rate" @input="item.harga_satuan = Math.round(item.harga_jual_asing * item.rate); calculateLineTotal(index)" class="w-full rounded border border-blue-200 bg-white px-2 py-1 text-xs focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" title="Edit nilai kurs untuk transaksi ini" placeholder="Nilai tukar" />
                                                </div>

                                                <!-- Harga Satuan IDR -->
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-8">Rp</span>
                                                    <input type="text" :value="formatNumber(item.harga_satuan)" @input="$el.value = $el.value.replace(/\D/g, ''); item.harga_satuan = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(item.harga_satuan); calculateLineTotal(index)" class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm font-medium focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!item.kurs || item.kurs === 'IDR'">
                                            <div class="flex items-center gap-1">
                                                <span class="text-sm text-gray-500">Rp</span>
                                                <input type="text" :value="formatNumber(item.harga_satuan)" @input="$el.value = $el.value.replace(/\D/g, ''); item.harga_satuan = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(item.harga_satuan); calculateLineTotal(index)" class="w-full rounded border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800" />
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="item.quantity" @input="calculateLineTotal(index)" min="1" :max="item.stok" class="w-full rounded border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800" />
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        Rp <span x-text="formatNumber(item.total_harga)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeTicket(index)" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                             <tr x-show="form.details.length === 0">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada tiket dipilih. Cari dan tambahkan tiket di atas.</td>
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
                        <p class="mt-1 text-xs text-gray-500">Stok tiket akan berkurang saat status 'Process' atau 'Completed'.</p>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Transaksi</label>
                        <textarea x-model="form.alamat_transaksi" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan</label>
                        <textarea x-model="form.catatan" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Bukti Transaksi (Optional)</label>
                        <input type="file" @change="form.bukti_transaksi = $event.target.files[0]" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" accept="image/*" />
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF (Max 2MB)</p>
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
                        <input type="text" :value="formatNumber(form.shipping_cost)" @input="$el.value = $el.value.replace(/\D/g, ''); form.shipping_cost = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(form.shipping_cost); calculateGrandTotal()" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white" placeholder="0" />
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-lg font-bold text-gray-800 dark:text-white">
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
                             <input type="text" :value="formatNumber(form.jumlah_bayar)" @input="$el.value = $el.value.replace(/\D/g, ''); form.jumlah_bayar = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(form.jumlah_bayar); calculateSisa()" class="w-32 text-right rounded border border-gray-300 px-2 py-1 text-sm bg-white" placeholder="0" />
                        </div>
                        <div class="flex justify-between items-center text-sm font-bold text-gray-800 dark:text-white">
                             <span>Sisa Tagihan</span>
                             <span>Rp <span x-text="formatNumber(sisaTagihan)"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('transaksi-tiket.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" :disabled="form.details.length === 0" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">Simpan Transaksi</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function transaksiTiket() {
        return {
            tickets: @json($tickets),
            pelanggans: @json($pelanggans),
            searchQuery: '',
            showSearchResults: false,
            filteredTickets: [],
            searchPelanggan: '',
            showPelangganDropdown: false,
            get filteredPelanggans() {
                if (this.searchPelanggan === '') {
                    return this.pelanggans;
                }
                const query = this.searchPelanggan.toLowerCase();
                return this.pelanggans.filter(p => p.nama_pelanggan.toLowerCase().includes(query));
            },
            selectPelanggan(pelanggan) {
                this.form.pelanggan_id = pelanggan.id;
                this.searchPelanggan = pelanggan.nama_pelanggan;
                this.showPelangganDropdown = false;
            },
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
                bukti_transaksi: null,
                // Payment Fields
                jumlah_bayar: 0,
                metode_pembayaran: ''
            },
            subtotal: 0,
            taxAmount: 0,
            discountAmount: 0,
            sisaTagihan: 0,
            
            init() {
                this.filteredTickets = this.tickets;
            },
            filterTickets() {
                if (this.searchQuery === '') {
                    this.showSearchResults = false;
                    return;
                }
                const query = this.searchQuery.toLowerCase();
                this.filteredTickets = this.tickets.filter(t => 
                    t.nama_tiket.toLowerCase().includes(query) || 
                    t.kode_tiket.toLowerCase().includes(query)
                );
                this.showSearchResults = true;
            },
            addTicket(ticket) {
                // Check if already exists
                const existing = this.form.details.find(d => d.ticket_id === ticket.id);
                if (existing) {
                    if (existing.quantity < ticket.jumlah_tiket) {
                        existing.quantity++;
                        this.calculateLineTotal(this.form.details.indexOf(existing));
                    } else {
                        alert('Stok tiket tidak mencukupi untuk menambah lagi.');
                    }
                } else {
                    this.form.details.push({
                        ticket_id: ticket.id,
                        nama_tiket: ticket.nama_tiket,
                        kode_tiket: ticket.kode_tiket,
                        stok: ticket.jumlah_tiket,
                        kurs: ticket.kurs,
                        harga_jual_asing: parseFloat(ticket.harga_jual_asing) || 0,
                        harga_satuan: parseFloat(ticket.harga_jual) || 0,
                        rate: (parseFloat(ticket.harga_jual_asing) || 0) > 0 ? (parseFloat(ticket.harga_jual) / parseFloat(ticket.harga_jual_asing)) : 0,
                        quantity: 1,
                        total_harga: parseFloat(ticket.harga_jual) || 0
                    });
                }
                this.searchQuery = '';
                this.showSearchResults = false;
                this.calculateGrandTotal();
            },
            removeTicket(index) {
                this.form.details.splice(index, 1);
                this.calculateGrandTotal();
            },
            calculateLineTotal(index) {
                const item = this.form.details[index];
                if (item.quantity > item.stok) {
                    alert('Jumlah melebihi stok tersedia!');
                    item.quantity = item.stok;
                }
                item.total_harga = item.quantity * item.harga_satuan;
                this.calculateGrandTotal();
            },
            calculateGrandTotal() {
                this.subtotal = this.form.details.reduce((sum, item) => sum + parseFloat(item.total_harga || 0), 0);
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
                if (!num && num !== 0) return '';
                return new Intl.NumberFormat('id-ID').format(Math.round(num));
            },
            formatNumberDecimal(num) {
                if (!num && num !== 0) return '';
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
            },
            parseFormattedNumber(value) {
                if (!value) return 0;
                return parseFloat(value.replace(/\./g, '').replace(/,/g, '')) || 0;
            },
            submitForm() {
                if (!this.form.pelanggan_id) {
                    alert('Silakan pilih pelanggan dari daftar dropdown.');
                    return;
                }
                const formData = new FormData();
                
                // Base fields
                formData.append('kode_transaksi', this.form.kode_transaksi);
                formData.append('pelanggan_id', this.form.pelanggan_id);
                formData.append('tanggal_transaksi', this.form.tanggal_transaksi);
                formData.append('subtotal', this.subtotal);
                formData.append('tax_percentage', this.form.tax_percentage);
                formData.append('discount_percentage', this.form.discount_percentage);
                formData.append('shipping_cost', this.form.shipping_cost);
                formData.append('total_transaksi', this.form.total_transaksi);
                formData.append('status_transaksi', this.form.status_transaksi);
                formData.append('alamat_transaksi', this.form.alamat_transaksi || '');
                formData.append('catatan', this.form.catatan || '');
                formData.append('jumlah_bayar', this.form.jumlah_bayar);
                formData.append('metode_pembayaran', this.form.metode_pembayaran || '');
                
                if (this.form.bukti_transaksi) {
                    formData.append('bukti_transaksi', this.form.bukti_transaksi);
                }

                // Append Details
                this.form.details.forEach((item, index) => {
                    formData.append(`details[${index}][ticket_id]`, item.ticket_id);
                    formData.append(`details[${index}][quantity]`, item.quantity);
                    formData.append(`details[${index}][harga_satuan]`, item.harga_satuan);
                    formData.append(`details[${index}][total_harga]`, item.total_harga);
                });

                fetch('{{ route('transaksi-tiket.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
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
