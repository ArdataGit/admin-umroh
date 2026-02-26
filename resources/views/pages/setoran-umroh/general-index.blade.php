@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Setoran Umroh" :breadcrumbs="[
    ['label' => 'Data Setoran', 'url' => '#'],
    ['label' => 'Setoran Umroh', 'url' => '#']
]" />

<div class="space-y-6">
    <!-- Transaction History Table -->
    <div class="col-span-12" x-data='{
      transactions: @json($transaksis),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "tanggal_transaksi",
      sortDirection: "desc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredTransactions() {
        if (!this.searchQuery) return this.transactions;
        const query = this.searchQuery.toLowerCase();
        return this.transactions.filter(t => {
          return t.kode_transaksi.toLowerCase().includes(query) ||
                 (t.keterangan && t.keterangan.toLowerCase().includes(query)) ||
                 (t.tabungan_umroh?.jamaah?.nama_lengkap && t.tabungan_umroh.jamaah.nama_lengkap.toLowerCase().includes(query)) ||
                 (t.kode_referensi && t.kode_referensi.toLowerCase().includes(query));
        });
      },
      get sortedTransactions() {
        if (!this.sortField) return this.filteredTransactions;
        return [...this.filteredTransactions].sort((a, b) => {
          let aVal = a[this.sortField];
          let bVal = b[this.sortField];
          
          // Access nested properties for sorting if needed
          if (this.sortField === "nama_jamaah") {
            aVal = a.tabungan_umroh?.jamaah?.nama_lengkap || "";
            bVal = b.tabungan_umroh?.jamaah?.nama_lengkap || "";
          }

          if (typeof aVal === "string") {
            aVal = aVal.toLowerCase();
            bVal = bVal.toLowerCase();
          }
           if (aVal < bVal) return this.sortDirection === "asc" ? -1 : 1;
          if (aVal > bVal) return this.sortDirection === "asc" ? 1 : -1;
          return 0;
        });
      },
      get totalPages() { return Math.ceil(this.sortedTransactions.length / this.itemsPerPage); },
      get paginatedTransactions() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.sortedTransactions.slice(start, start + this.itemsPerPage);
      },
      sortBy(field) {
        if (this.sortField === field) this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        else { this.sortField = field; this.sortDirection = "asc"; }
        this.currentPage = 1;
      },
      openDeleteModal(id, kode) { this.deleteTarget = { id, kode }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/setoran-umroh/transaksi/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content, "Accept": "application/json" }
        }).then(r => r.json()).then(d => {
            if (d.success) { 
                this.transactions = this.transactions.filter(t => t.id !== this.deleteTarget.id); 
                this.showDeleteModal = false; 
            }
            else alert("Gagal menghapus data");
        });
      }
    }'>
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Semua Setoran</h3>
                    <!-- Link to Tabungan List because Setoran creation requires picking a Tabungan first -->
                    <a href="{{ route('tabungan-umroh') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                        Tambah Setoran (Pilih Jamaah)
                    </a>
                </div>
                 <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Show</span>
                        <select x-model.number="itemsPerPage" @change="currentPage = 1" class="rounded-lg border border-gray-300 bg-transparent py-1.5 px-2 text-sm dark:border-gray-700 dark:text-gray-400">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
                    </div>
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari Transaksi / Jamaah..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white sm:w-64"/>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="cursor-pointer px-6 py-3" @click="sortBy('tanggal_transaksi')">Tanggal Setoran</th>
                            <th class="cursor-pointer px-6 py-3" @click="sortBy('kode_transaksi')">Kode Transaksi</th>
                            <th class="cursor-pointer px-6 py-3" @click="sortBy('nama_jamaah')">Nama Jamaah</th>
                            <th class="cursor-pointer px-6 py-3" @click="sortBy('nominal')">Jumlah Setoran</th>
                            <th class="px-6 py-3">Metode Pembayaran</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(trx, index) in paginatedTransactions" :key="trx.id">
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td class="px-6 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                                <td class="px-6 py-4" x-text="new Date(trx.tanggal_transaksi).toLocaleDateString('id-ID')"></td>
                                <td class="px-6 py-4 font-medium" x-text="trx.kode_transaksi"></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-800 dark:text-white" x-text="trx.tabungan_umroh?.jamaah?.nama_lengkap || '-'"></span>
                                        <span class="text-xs text-gray-500" x-text="trx.tabungan_umroh?.kode_tabungan || '-'"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-green-600" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(trx.nominal)"></td>
                                <td class="px-6 py-4" x-text="trx.metode_pembayaran"></td>
                                <td class="px-6 py-4">
                                     <span :class="{
                                        'bg-red-100 text-red-800': trx.status_setoran === 'checked',
                                        'bg-green-100 text-green-800': trx.status_setoran === 'completed',
                                        'bg-yellow-100 text-yellow-800': !['checked', 'completed'].includes(trx.status_setoran)
                                     }" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="trx.status_setoran || 'checked'"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="#" class="text-blue-500 hover:text-blue-700" title="Cetak Kwitansi">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                            </svg>
                                        </a>
                                        @if($canEdit)
                                        <a :href="`/setoran-umroh/transaksi/${trx.id}/edit`" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                        @endif
                                        @if($canDelete)
                                        <button @click="openDeleteModal(trx.id, trx.kode_transaksi)" class="text-red-500 hover:text-red-700" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </template>
                         <tr x-show="paginatedTransactions.length === 0">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat transaksi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-700" x-show="transactions.length > 0">
                <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Prev</button>
                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
                <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Next</button>
            </div>
             
             <!-- Delete Modal -->
            <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h3 class="text-lg font-bold">Hapus Transaksi?</h3>
                    <p>Hapus transaksi <span x-text="deleteTarget?.kode" class="font-bold"></span>?</p>
                    <p class="text-sm text-red-500 mt-2">Saldo tabungan akan disesuaikan otomatis.</p>
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
