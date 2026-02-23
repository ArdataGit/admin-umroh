@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Transaksi Tiket" :breadcrumbs="[
    ['label' => 'Transaksi Tiket', 'url' => '#']
]" />

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
  {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
      items: @json($transaksi),
      currentPage: 1,
      itemsPerPage: 10,
      searchQuery: "",
      departureDate: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredItems() {
        let result = this.items;
        
        // Filter by text search
        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase();
          result = result.filter(i => {
            return i.kode_transaksi.toLowerCase().includes(query) ||
                   i.pelanggan?.nama_pelanggan.toLowerCase().includes(query);
          });
        }
        
        // Filter by departure date
        if (this.departureDate) {
          result = result.filter(item => {
            if (!item.details) return false;
            return item.details.some(detail => {
              return detail.ticket && detail.ticket.tanggal_keberangkatan === this.departureDate;
            });
          });
        }
        
        return result;
      },
      get paginatedItems() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredItems.slice(start, start + this.itemsPerPage);
      },
      get totalPages() { return Math.ceil(this.filteredItems.length / this.itemsPerPage); },
      openDeleteModal(id, kode) { this.deleteTarget = { id, kode }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/transaksi-tiket/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content, "Accept": "application/json" }
        }).then(r => r.json()).then(d => {
            if (d.success) { 
                this.items = this.items.filter(i => i.id !== this.deleteTarget.id); 
                this.showDeleteModal = false; 
                window.location.reload(); 
            }
            else alert("Gagal menghapus data");
        });
      }
    }'>
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Transaksi Tiket</h3>
                    <a href="{{ route('transaksi-tiket.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                        Tambah Transaksi
                    </a>
                </div>
                 <div class="flex flex-col sm:flex-row gap-2 items-center">
                    <input type="date" x-model="departureDate" @input="currentPage = 1" class="h-10 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" title="Tanggal Keberangkatan Tiket">
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white sm:w-64"/>
                    </div>
                 </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-3">No</th>
                            <th class="px-3 py-3">Tanggal Transaksi</th>
                            <th class="px-3 py-3">Kode Transaksi</th>
                            <th class="px-3 py-3">Nama Pelanggan</th>
                            <th class="px-3 py-3 text-center">Jumlah Tiket</th>
                            <th class="px-3 py-3">Total Harga</th>
                            <th class="px-3 py-3">Total Bayar</th>
                            <th class="px-3 py-3">Sisa Bayar</th>
                            <th class="px-3 py-3">Metode Pembayaran</th>
                            <th class="px-3 py-3">Status Transaksi</th>
                            <th class="px-3 py-3">Status Pembayaran</th>
                            <th class="px-3 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in paginatedItems" :key="item.id">
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td class="px-3 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                                <td class="px-3 py-4" x-text="new Date(item.tanggal_transaksi).toLocaleDateString('id-ID')"></td>
                                <td class="px-3 py-4 font-medium" x-text="item.kode_transaksi"></td>
                                <td class="px-3 py-4" x-text="item.pelanggan?.nama_pelanggan"></td>
                                <td class="px-3 py-4 text-center" x-text="item.details?.reduce((acc, curr) => acc + curr.quantity, 0) || 0"></td>
                                <td class="px-3 py-4 font-bold">
                                    <div class="flex flex-col">
                                        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.total_transaksi)"></span>
                                        <template x-if="item.details?.some(d => d.ticket?.kurs && d.ticket.kurs !== 'IDR')">
                                            <div class="text-xs text-blue-600 dark:text-blue-400">
                                                <template x-for="currency in [...new Set(item.details.filter(d => d.ticket?.kurs && d.ticket.kurs !== 'IDR').map(d => d.ticket.kurs))]">
                                                    <div x-text="(currency === 'MYR' ? 'RM' : currency) + ' ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(
                                                        item.details.filter(d => d.ticket?.kurs === currency).reduce((acc, curr) => acc + (curr.quantity * (curr.ticket.harga_jual_asing || 0)), 0)
                                                    )"></div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-green-600 font-medium" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.pembayaran_tikets?.reduce((acc, curr) => acc + Number(curr.jumlah_pembayaran), 0) || 0)"></td>
                                <td class="px-3 py-4 text-red-600 font-medium" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.total_transaksi - (item.pembayaran_tikets?.reduce((acc, curr) => acc + Number(curr.jumlah_pembayaran), 0) || 0))"></td>
                                <td class="px-3 py-4">
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                         <span x-text="item.pembayaran_tikets?.length > 0 ? [...new Set(item.pembayaran_tikets.map(p => p.metode_pembayaran))].join(', ') : '-'"></span>
                                     </span>
                                </td>
                                <td class="px-3 py-4">
                                     <span :class="{
                                        'bg-blue-100 text-blue-800': item.status_transaksi === 'process',
                                        'bg-red-100 text-red-800': item.status_transaksi === 'cancelled',
                                        'bg-green-100 text-green-800': item.status_transaksi === 'completed'
                                     }" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="item.status_transaksi"></span>
                                </td>
                                <td class="px-3 py-4">
                                    <span :class="{
                                        'bg-green-100 text-green-800': (item.total_transaksi - (item.pembayaran_tikets?.reduce((acc, curr) => acc + Number(curr.jumlah_pembayaran), 0) || 0)) <= 0,
                                        'bg-yellow-100 text-yellow-800': (item.total_transaksi - (item.pembayaran_tikets?.reduce((acc, curr) => acc + Number(curr.jumlah_pembayaran), 0) || 0)) > 0
                                     }" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="(item.total_transaksi - (item.pembayaran_tikets?.reduce((acc, curr) => acc + Number(curr.jumlah_pembayaran), 0) || 0)) <= 0 ? 'LUNAS' : 'BELUM LUNAS'"></span>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a :href="`/transaksi-tiket/${item.id}`" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a :href="`/transaksi-tiket/${item.id}/edit`" class="text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-500" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button @click="openDeleteModal(item.id, item.kode_transaksi)" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                         <tr x-show="paginatedItems.length === 0">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data transaksi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

             <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-700" x-show="items.length > 0">
                <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Prev</button>
                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
                <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Next</button>
            </div>
            
            <!-- Delete Modal -->
            <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h3 class="text-lg font-bold">Hapus Transaksi?</h3>
                    <p>Hapus transaksi <span x-text="deleteTarget?.kode" class="font-bold"></span>?</p>
                    <div class="flex justify-end gap-2 mt-4">
                        <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                         <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
