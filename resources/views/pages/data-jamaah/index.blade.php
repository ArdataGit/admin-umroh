@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Jamaah" />

  <!-- Flash Messages -->
  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
      <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-400">
      <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
      jamaahs: @json($dataJamaah),
      canEdit: {{ $canEdit ? 'true' : 'false' }},
      canDelete: {{ $canDelete ? 'true' : 'false' }},
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
      get totalPages() {
        return Math.ceil(this.sortedJamaahs.length / this.itemsPerPage);
      },
      get paginatedJamaahs() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.sortedJamaahs.slice(start, start + this.itemsPerPage);
      },
      sortBy(field) {
        if (this.sortField === field) this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        else { this.sortField = field; this.sortDirection = "asc"; }
        this.currentPage = 1;
      },
      prevPage() { if (this.currentPage > 1) this.currentPage--; },
      nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
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
        <!-- Show Entries Dropdown -->
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-600 dark:text-gray-400">Show</label>
          <select x-model="itemsPerPage" @change="currentPage = 1" class="h-10 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <label class="text-sm text-gray-600 dark:text-gray-400">entries</label>
        </div>

        <a href="{{ route('data-jamaah.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-600">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.89543 20.1046 9 19 9H5C3.89543 9 3 9.89543 3 11V15C3 16.1046 3.89543 17 5 17H7M17 17V13H7V17M17 17H7M15 9V3H9V9H15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Print
        </a>

        <a href="{{ route('data-jamaah.export') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.5 15.8333H15.8333V4.16667H12.5M12.5 15.8333H7.5C5.04543 15.8333 4.16667 14.9546 4.16667 12.5V7.5C4.16667 5.04543 5.04543 4.16667 7.5 4.16667H12.5M12.5 15.8333V4.16667M9.16667 12.5L10.8333 10M10.8333 10L9.16667 7.5M10.8333 10L10.85 10.025" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Export Excel
        </a>
        @if($canCreate)
        <a href="{{ route('data-jamaah.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Tambah Data Jamaah
        </a>
        @endif
      </div>
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <form>
          <div class="relative">
            <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
              <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
              </svg>
            </button>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
          </div>
        </form>
      </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-[1000px]">
          <thead>
            <tr class="border-b border-gray-100 dark:border-gray-800">
              <th class="px-4 py-3 text-left">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
              </th>
              <th class="px-4 py-3 text-left">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Foto</p>
              </th>
              <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('kode_jamaah')">
                <div class="flex items-center gap-1">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode</p>
                  <svg class="w-4 h-4" :class="sortField === 'kode_jamaah' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                  </svg>
                </div>
              </th>
              <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nik_jamaah')">
                <div class="flex items-center gap-1">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">NIK</p>
                  <svg class="w-4 h-4" :class="sortField === 'nik_jamaah' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                  </svg>
                </div>
              </th>
              <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nama_jamaah')">
                <div class="flex items-center gap-1">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama</p>
                  <svg class="w-4 h-4" :class="sortField === 'nama_jamaah' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                  </svg>
                </div>
              </th>
              <th class="px-4 py-3 text-left">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kontak</p>
              </th>
              <th class="px-4 py-3 text-left">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kota/Kab</p>
              </th>
              <th class="px-4 py-3 text-center">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
              </th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(jamaah, index) in paginatedJamaahs" :key="jamaah.id">
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-4">
                  <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                </td>
                <td class="px-4 py-4">
                  <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100">
                    <template x-if="jamaah.foto_jamaah">
                      <img :src="`/storage/${jamaah.foto_jamaah}`" class="w-full h-full object-cover">
                    </template>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="jamaah.kode_jamaah"></p>
                </td>
                <td class="px-4 py-4">
                  <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="jamaah.nik_jamaah"></p>
                </td>
                <td class="px-4 py-4">
                  <p class="text-gray-800 text-theme-sm dark:text-white/90" x-text="jamaah.nama_jamaah"></p>
                </td>
                <td class="px-4 py-4">
                  <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="jamaah.kontak_jamaah"></p>
                </td>
                <td class="px-4 py-4">
                  <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="jamaah.kabupaten_kota"></p>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <a :href="`/data-jamaah/${jamaah.id}`" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300" title="View">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </a>
                    <template x-if="canEdit">
                      <a :href="`/data-jamaah/${jamaah.id}/edit`" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </a>
                    </template>
                    <template x-if="canDelete">
                      <button @click="openDeleteModal(jamaah.id, jamaah.nama_jamaah)" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </template>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
      <div class="flex items-center justify-between">
        <button @click="prevPage" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          <span class="hidden sm:inline">Previous</span>
        </button>
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600 dark:text-gray-400">Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
        </div>
        <button @click="nextPage" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
          <span class="hidden sm:inline">Next</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Data?</h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Hapus jamaah <span x-text="deleteTarget?.name" class="font-bold text-gray-900 dark:text-white"></span>?</p>
        <div class="flex justify-end gap-2 mt-4">
          <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
          <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
        </div>
      </div>
    </div>

    </div>
  </div>
@endsection
