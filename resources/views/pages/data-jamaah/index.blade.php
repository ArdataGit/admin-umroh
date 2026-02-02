@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Jamaah" />

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
      jamaahs: @json($dataJamaah),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "",
      sortDirection: "asc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredJamaahs() {
        if (!this.searchQuery) return this.jamaahs;
        return this.jamaahs.filter(jamaah => {
          const query = this.searchQuery.toLowerCase();
          return jamaah.kode_jamaah.toLowerCase().includes(query) ||
                 jamaah.nama_jamaah.toLowerCase().includes(query) ||
                 jamaah.nik_jamaah.toLowerCase().includes(query);
        });
      },
      get sortedJamaahs() {
        if (!this.sortField) return this.filteredJamaahs;
        return [...this.filteredJamaahs].sort((a, b) => {
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
      get totalPages() { return Math.ceil(this.sortedJamaahs.length / this.itemsPerPage); },
      get paginatedJamaahs() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.sortedJamaahs.slice(start, start + this.itemsPerPage);
      },
      sortBy(field) {
        if (this.sortField === field) this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        else { this.sortField = field; this.sortDirection = "asc"; }
        this.currentPage = 1;
      },
      openDeleteModal(id, name) { this.deleteTarget = { id, name }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/data-jamaah/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content, "Accept": "application/json" }
        }).then(r => r.json()).then(d => {
            if (d.success) { this.jamaahs = this.jamaahs.filter(j => j.id !== this.deleteTarget.id); this.showDeleteModal = false; }
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
            <a href="{{ route('data-jamaah.print') }}" target="_blank" class="rounded-lg bg-gray-500 px-4 py-2 text-sm text-white">Print</a>
            <a href="{{ route('data-jamaah.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm text-white">Tambah Data</a>
        </div>
        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm sm:w-64"/>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-left cursor-pointer" @click="sortBy('kode_jamaah')">Kode</th>
                        <th class="px-4 py-3 text-left cursor-pointer" @click="sortBy('nik_jamaah')">NIK</th>
                        <th class="px-4 py-3 text-left cursor-pointer" @click="sortBy('nama_jamaah')">Nama</th>
                        <th class="px-4 py-3 text-left">Kontak</th>
                        <th class="px-4 py-3 text-left">Kota/Kab</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(jamaah, index) in paginatedJamaahs" :key="jamaah.id">
                        <tr class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800">
                            <td class="px-4 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                            <td class="px-4 py-4">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100">
                                    <template x-if="jamaah.foto_jamaah">
                                        <img :src="`/storage/${jamaah.foto_jamaah}`" class="w-full h-full object-cover">
                                    </template>
                                </div>
                            </td>
                            <td class="px-4 py-4" x-text="jamaah.kode_jamaah"></td>
                            <td class="px-4 py-4" x-text="jamaah.nik_jamaah"></td>
                            <td class="px-4 py-4" x-text="jamaah.nama_jamaah"></td>
                            <td class="px-4 py-4" x-text="jamaah.kontak_jamaah"></td>
                            <td class="px-4 py-4" x-text="jamaah.kabupaten_kota"></td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a :href="`/data-jamaah/${jamaah.id}`" class="text-green-500 hover:text-green-700">View</a>
                                    <a :href="`/data-jamaah/${jamaah.id}/edit`" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <button @click="openDeleteModal(jamaah.id, jamaah.nama_jamaah)" class="text-red-500 hover:text-red-700">Delete</button>
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
            <h3 class="text-lg font-bold">Hapus Data?</h3>
            <p>Hapus jamaah <span x-text="deleteTarget?.name" class="font-bold"></span>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>

    </div>
  </div>
@endsection
