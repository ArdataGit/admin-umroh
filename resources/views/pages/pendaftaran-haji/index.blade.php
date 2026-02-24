@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="pendaftaranHajiTable({{ $canEdit ? 'true' : 'false' }}, {{ $canDelete ? 'true' : 'false' }})">
    <x-common.page-breadcrumb pageTitle="Data Pendaftaran Haji" :breadcrumbs="[
        ['label' => 'Pendaftaran Haji', 'url' => '#']
    ]" />

    <div>
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            
            <!-- Controls Header -->
            <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <!-- Show Entries -->
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

                    <!-- Print -->
                    <a href="{{ route('pendaftaran-haji.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-600">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.89543 20.1046 9 19 9H5C3.89543 9 3 9.89543 3 11V15C3 16.1046 3.89543 17 5 17H7M17 17V13H7V17M17 17H7M15 9V3H9V9H15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Print
                    </a>

                    <!-- Export -->
                    <a href="{{ route('pendaftaran-haji.export') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.5 15.8333H15.8333V4.16667H12.5M12.5 15.8333H7.5C5.04543 15.8333 4.16667 14.9546 4.16667 12.5V7.5C4.16667 5.04543 5.04543 4.16667 7.5 4.16667H12.5M12.5 15.8333V4.16667M9.16667 12.5L10.8333 10M10.8333 10L9.16667 7.5M10.8333 10L10.85 10.025" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Export Excel
                    </a>
                    
                    <!-- Create -->
                    @if($canCreate)
                    <a href="{{ route('pendaftaran-haji.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Tambah Data
                    </a>
                    @endif
                </div>

                <!-- Search -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative">
                        <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                        </button>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto mt-4">
                 <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" @click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    Tanggal Registrasi
                                    <span x-show="sortField === 'created_at'" x-text="sortDirection === 'asc' ? '↑' : '↓'"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" @click="sortBy('kode_jamaah')">
                                <div class="flex items-center gap-1">
                                    Kode Registrasi
                                    <span x-show="sortField === 'kode_jamaah'" x-text="sortDirection === 'asc' ? '↑' : '↓'"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" @click="sortBy('nik_jamaah')">
                                <div class="flex items-center gap-1">
                                    NIK Jamaah
                                    <span x-show="sortField === 'nik_jamaah'" x-text="sortDirection === 'asc' ? '↑' : '↓'"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" @click="sortBy('nama_jamaah')">
                                <div class="flex items-center gap-1">
                                    Nama Jamaah
                                    <span x-show="sortField === 'nama_jamaah'" x-text="sortDirection === 'asc' ? '↑' : '↓'"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3">Jenis Kelamin</th>
                            <th class="px-6 py-3">Tanggal Lahir</th>
                            <th class="px-6 py-3">Kota / Kabupaten</th>
                            <th class="px-6 py-3">Nama Keberangkatan</th>
                            <th class="px-6 py-3 text-center">Jumlah Jamaah (Pax)</th>
                            <th class="px-6 py-3">Nama Agent</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in paginatedItems" :key="item.id">
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td class="px-6 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                                <td class="px-6 py-4" x-text="item.created_at_formatted"></td>
                                <td class="px-6 py-4 font-medium text-blue-600" x-text="item.jamaah.kode_jamaah"></td>
                                <td class="px-6 py-4" x-text="item.jamaah.nik_jamaah"></td>
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white" x-text="item.jamaah.nama_jamaah"></td>
                                <td class="px-6 py-4" x-text="item.jamaah.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'"></td>
                                <td class="px-6 py-4" x-text="item.formatted_dob"></td>
                                <td class="px-6 py-4" x-text="item.jamaah.kabupaten_kota"></td>
                                <td class="px-6 py-4">
                                    <span class="block font-medium" x-text="item.keberangkatan_haji.nama_keberangkatan"></span>
                                    <span class="text-xs text-gray-400" x-text="item.keberangkatan_haji.kode_keberangkatan"></span>
                                </td>
                                <td class="px-6 py-4 text-center" x-text="item.jumlah_jamaah"></td>
                                <td class="px-6 py-4" x-text="item.agent ? item.agent.nama_agent : '-'"></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a :href="`/pendaftaran-haji/${item.id}`" class="text-gray-500 hover:text-gray-700" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                        <template x-if="canEdit">
                                            <a :href="`/pendaftaran-haji/${item.id}/edit`" class="text-gray-500 hover:text-green-600" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                        </template>
                                        <template x-if="canDelete">
                                            <button @click="openDeleteModal(item.id, item.jamaah.nama_jamaah)" class="text-gray-500 hover:text-red-600" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="paginatedItems.length === 0">
                            <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data yang ditemukan.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Controls -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <button @click="prevPage" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </button>
                    
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                        Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                    </span>

                    <ul class="hidden items-center gap-0.5 sm:flex">
                        <template x-for="page in displayPages" :key="page">
                            <li>
                                <button x-show="page !== '...'" @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'" class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium" x-text="page"></button>
                                <span x-show="page === '...'" class="flex h-10 w-10 items-center justify-center text-gray-500">...</span>
                            </li>
                        </template>
                    </ul>

                    <button @click="nextPage" :disabled="currentPage >= totalPages" :class="currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <span class="hidden sm:inline">Next</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Pendaftaran?</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Anda yakin ingin menghapus data jamaah <span x-text="deleteName" class="font-bold text-gray-900 dark:text-white"></span>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    function pendaftaranHajiTable(canEdit, canDelete) {
        return {
            items: @json($pendaftarans),
            canEdit: canEdit,
            canDelete: canDelete,
            searchQuery: '',
            currentPage: 1,
            itemsPerPage: 10,
            sortField: 'created_at',
            sortDirection: 'desc',
            
            // Delete modal state
            showDeleteModal: false,
            deleteId: null,
            deleteName: '',

            init() {
                // Pre-process date formatting to avoid re-parsing on every render
                this.items = this.items.map(item => {
                    item.created_at_formatted = new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                    item.formatted_dob = item.jamaah.tanggal_lahir ? new Date(item.jamaah.tanggal_lahir).toLocaleDateString('id-ID') : '-';
                    return item;
                });
            },

            get filteredItems() {
                if (!this.searchQuery) return this.items;
                const lowerQuery = this.searchQuery.toLowerCase();
                return this.items.filter(item => {
                    return (
                        item.jamaah.nama_jamaah.toLowerCase().includes(lowerQuery) ||
                        item.jamaah.kode_jamaah.toLowerCase().includes(lowerQuery) ||
                        item.jamaah.nik_jamaah.includes(lowerQuery) ||
                        (item.agent && item.agent.nama_agent.toLowerCase().includes(lowerQuery)) ||
                        item.keberangkatan_haji.nama_keberangkatan.toLowerCase().includes(lowerQuery)
                    );
                });
            },

            get sortedItems() {
                return this.filteredItems.sort((a, b) => {
                    let aVal, bVal;
                    
                    // Handle nested properties for sorting
                    if (this.sortField === 'kode_jamaah') {
                         aVal = a.jamaah.kode_jamaah; bVal = b.jamaah.kode_jamaah;
                    } else if (this.sortField === 'nama_jamaah') {
                         aVal = a.jamaah.nama_jamaah; bVal = b.jamaah.nama_jamaah;
                    } else if (this.sortField === 'nik_jamaah') {
                         aVal = a.jamaah.nik_jamaah; bVal = b.jamaah.nik_jamaah;
                    } else {
                        aVal = a[this.sortField]; bVal = b[this.sortField];
                    }

                    if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                    if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            },

            get totalPages() {
                return Math.ceil(this.sortedItems.length / this.itemsPerPage);
            },

            get paginatedItems() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                return this.sortedItems.slice(start, start + this.itemsPerPage);
            },

            get displayPages() {
                const pages = [];
                const maxVisible = 5;
                if (this.totalPages <= maxVisible) {
                    for (let i = 1; i <= this.totalPages; i++) pages.push(i);
                } else {
                    if (this.currentPage <= 3) {
                        for (let i = 1; i <= 4; i++) pages.push(i);
                        pages.push("...");
                        pages.push(this.totalPages);
                    } else if (this.currentPage >= this.totalPages - 2) {
                        pages.push(1);
                        pages.push("...");
                        for (let i = this.totalPages - 3; i <= this.totalPages; i++) pages.push(i);
                    } else {
                        pages.push(1);
                        pages.push("...");
                        for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) pages.push(i);
                        pages.push("...");
                        pages.push(this.totalPages);
                    }
                }
                return pages;
            },

            goToPage(page) {
                if (page !== "...") this.currentPage = page;
            },

            sortBy(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
            },

            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },

            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },
            
            openDeleteModal(id, name) {
                this.deleteId = id;
                this.deleteName = name;
                this.showDeleteModal = true;
            },
            
            confirmDelete() {
                if (!this.deleteId) return;
                fetch(`/pendaftaran-haji/${this.deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        }
    }
</script>
@endsection
