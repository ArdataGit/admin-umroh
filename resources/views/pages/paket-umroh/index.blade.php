@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Paket Umroh" />

  
  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-400">
      {{ session('error') }}
    </div>
  @endif

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
      pakets: @json($paketUmrohs),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "",
      sortDirection: "asc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredPakets() {
        if (!this.searchQuery) return this.pakets;
        return this.pakets.filter(paket => {
          const query = this.searchQuery.toLowerCase();
          return paket.kode_paket.toLowerCase().includes(query) ||
                 paket.nama_paket.toLowerCase().includes(query);
        });
      },
      get sortedPakets() {
        if (!this.sortField) return this.filteredPakets;
        return [...this.filteredPakets].sort((a, b) => {
          let aVal = a[this.sortField];
          let bVal = b[this.sortField];
          if (typeof aVal === "string") {
            aVal = aVal.toLowerCase();
            bVal = bVal.toLowerCase();
          }
          if (aVal < bVal) return this.sortDirection === "asc" ? -1 : 1;
          if (aVal > bVal) return this.sortDirection === "asc" ? 1 : -1;
          return 0;
        });
      },
      get totalPages() { return Math.ceil(this.sortedPakets.length / this.itemsPerPage); },
      get paginatedPakets() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.sortedPakets.slice(start, start + this.itemsPerPage);
      },
      sortBy(field) {
        if (this.sortField === field) this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        else { this.sortField = field; this.sortDirection = "asc"; }
        this.currentPage = 1;
      },
      openDeleteModal(id, name) { this.deleteTarget = { id, name }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/paket-umroh/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content, "Accept": "application/json" }
        }).then(r => r.json()).then(d => {
            if (d.success) { this.pakets = this.pakets.filter(p => p.id !== this.deleteTarget.id); this.showDeleteModal = false; }
            else alert("Gagal menghapus data");
        });
      }
    }'>

    <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-3">
            <select x-model="itemsPerPage" @change="currentPage = 1" class="h-10 rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
            </select>
            <a href="{{ route('paket-umroh.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm text-white">Tambah Paket</a>
        </div>
        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm sm:w-64"/>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left cursor-pointer" @click="sortBy('kode_paket')">Kode</th>
                        <th class="px-4 py-3 text-left cursor-pointer" @click="sortBy('nama_paket')">Nama Paket</th>
                        <th class="px-4 py-3 text-left">Tgl Berangkat</th>
                        <th class="px-4 py-3 text-left">Hari</th>
                        <th class="px-4 py-3 text-left">Maskapai</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(paket, index) in paginatedPakets" :key="paket.id">
                        <tr class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800">
                            <td class="px-4 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                            <td class="px-4 py-4 font-medium" x-text="paket.kode_paket"></td>
                            <td class="px-4 py-4" x-text="paket.nama_paket"></td>
                            <td class="px-4 py-4" x-text="paket.tanggal_keberangkatan"></td>
                            <td class="px-4 py-4" x-text="paket.jumlah_hari + ' Hari'"></td>
                            <td class="px-4 py-4" x-text="paket.maskapai?.nama_maskapai"></td>
                            <td class="px-4 py-4">
                                <span :class="paket.status_paket === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="paket.status_paket"></span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a :href="`/paket-umroh/${paket.id}`" class="text-green-500 hover:text-green-700">View</a>
                                    <a :href="`/paket-umroh/${paket.id}/edit`" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <button @click="openDeleteModal(paket.id, paket.nama_paket)" class="text-red-500 hover:text-red-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
        <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Prev</button>
        <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
        <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 bg-gray-100 rounded disabled:opacity-50">Next</button>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-bold">Hapus Paket?</h3>
            <p>Hapus paket <span x-text="deleteTarget?.name" class="font-bold"></span>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>

    </div>
  </div>
@endsection
