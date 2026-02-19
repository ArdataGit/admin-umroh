@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Surat Rekomendasi" :breadcrumbs="[
    ['label' => 'Surat Rekomendasi', 'url' => '#']
]" />

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

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
        items: @json($surat),
        currentPage: 1,
        itemsPerPage: 10,
        sortField: "",
        sortDirection: "asc",
        searchQuery: "",
        deleteModalOpen: false,
        deleteAction: null,
        
        get filteredItems() {
            if (!this.searchQuery) return this.items;
            const query = this.searchQuery.toLowerCase();
            return this.items.filter(item => {
                return (item.nomor_dokumen && item.nomor_dokumen.toLowerCase().includes(query)) ||
                       (item.kantor_imigrasi && item.kantor_imigrasi.toLowerCase().includes(query)) ||
                       (item.jamaah && item.jamaah.nama_jamaah && item.jamaah.nama_jamaah.toLowerCase().includes(query)) ||
                       (item.keberangkatan_umroh && item.keberangkatan_umroh.nama_keberangkatan && item.keberangkatan_umroh.nama_keberangkatan.toLowerCase().includes(query));
            });
        },
        get sortedItems() {
            if (!this.sortField) return this.filteredItems;
            return [...this.filteredItems].sort((a, b) => {
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
            return Math.ceil(this.sortedItems.length / this.itemsPerPage);
        },
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.sortedItems.slice(start, end);
        },
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
            } else {
                this.sortField = field;
                this.sortDirection = "asc";
            }
            this.currentPage = 1;
        },
        formatDate(dateString) {
             const options = { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute:"2-digit" };
             return new Date(dateString).toLocaleDateString("id-ID", options).replace(",", "");
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

                <a href="{{ route('surat-rekomendasi.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.89543 20.1046 9 19 9H5C3.89543 9 3 9.89543 3 11V15C3 16.1046 3.89543 17 5 17H7M17 17V13H7V17M17 17H7M15 9V3H9V9H15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Print
                </a>

                <a href="{{ route('surat-rekomendasi.export') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 15.8333H15.8333V4.16667H12.5M12.5 15.8333H7.5C5.04543 15.8333 4.16667 14.9546 4.16667 12.5V7.5C4.16667 5.04543 5.04543 4.16667 7.5 4.16667H12.5M12.5 15.8333V4.16667M9.16667 12.5L10.8333 10M10.8333 10L9.16667 7.5M10.8333 10L10.85 10.025" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Export Excel
                </a>

                <a href="{{ route('surat-rekomendasi.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-600/20">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Buat Surat
                </a>
            </div>
             <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form>
                    <div class="relative">
                        <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                        </button>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search Surat..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
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
                            <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Buat</p>
                                    <svg class="w-4 h-4" :class="sortField === 'created_at' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No. Dokumen</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Jamaah</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Keberangkatan</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kantor Imigrasi</p>
                            </th>
                             <th class="px-4 py-3 text-center">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                          <template x-for="(item, index) in paginatedItems" :key="item.id">
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="formatDate(item.created_at)"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-blue-600 text-theme-sm" x-text="item.nomor_dokumen"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="item.jamaah?.nama_jamaah || 'N/A'"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.keberangkatan_umroh?.nama_keberangkatan || 'N/A'"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.kantor_imigrasi"></p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail -->
                                        <a :href="'/surat-rekomendasi/' + item.id" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <!-- Edit -->
                                        <a :href="'/surat-rekomendasi/' + item.id + '/edit'" class="text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-500" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <!-- Export PDF -->
                                        <a :href="'/surat-rekomendasi/' + item.id + '/export-pdf'" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Download PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <!-- Print -->
                                        <a :href="'/surat-rekomendasi/' + item.id + '/print-pdf'" target="_blank" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-500" title="Print Invoice">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                        <!-- Delete -->
                                        <button @click="deleteModalOpen = true; deleteAction = '/surat-rekomendasi/' + item.id" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                         </template>
                         <tr x-show="filteredItems.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada surat rekomendasi yang ditemukan.
                            </td>
                         </tr>
                    </tbody>
                </table>
            </div>
             <!-- Pagination -->
              <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                <div class="flex items-center justify-between">
                     <button @click="currentPage--" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>
                      <span class="text-sm text-gray-700 dark:text-gray-400">
                        Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                    </span>
                     <button @click="currentPage++" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>


    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
        <div x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800" @click.away="deleteModalOpen = false">
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 rounded-full bg-red-50 p-3 text-red-500 dark:bg-red-900/20">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Konfirmasi Hapus</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus surat rekomendasi ini? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="mt-6 flex w-full gap-3">
                    <button @click="deleteModalOpen = false" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        Batal
                    </button>
                    <form :action="deleteAction" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-500/20">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    </div>
</div>
@endsection
