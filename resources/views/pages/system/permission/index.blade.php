@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Role & Permission Management" :breadcrumbs="[['label' => 'System', 'url' => '#'], ['label' => 'Permission', 'url' => '#']]" />

    <!-- Summary Start -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Total Roles -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Role Tersedia</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $roles->count() }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Summary End -->

    <div x-data="{
        showRoleModal: false,
        searchQuery: '',
        items: {{ Js::from($roles) }},
        itemsPerPage: 10,
        currentPage: 1,
        get filteredItems() {
            if (this.searchQuery === '') return this.items;
            return this.items.filter(item => {
                return item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        },
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredItems.slice(start, end);
        },
        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        goToPage(page) {
            this.currentPage = page;
        }
    }" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Table Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 gap-4 border-b border-gray-100 dark:border-gray-800">
            <div class="relative w-full md:w-96">
                <input x-model="searchQuery" type="text" placeholder="Cari nama role..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            @if($canCreate)
            <button @click="showRoleModal = true" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Role
            </button>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-700 dark:bg-gray-800/50 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Nama Role / Permission</th>
                        <th scope="col" class="px-6 py-4 text-center">Jumlah Pengguna</th>
                        <th scope="col" class="px-6 py-4 text-center">Created At</th>
                        <th scope="col" class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="item in paginatedItems" :key="item.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="item.id"></td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white capitalize" x-text="item.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400" x-text="item.users_count + ' Users'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center" x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-3">
                                    @if($canEdit)
                                    <a :href="`/permission/${item.id}`" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($canDelete)
                                    <form :action="`/role/${item.id}`" method="POST" class="inline" @submit.prevent="if(item.users_count > 0) { alert('Role tidak dapat dihapus karena sedang digunakan oleh ' + item.users_count + ' user.'); } else if(confirm('Apakah Anda yakin ingin menghapus role ini?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete" :class="{'opacity-50 cursor-not-allowed': item.users_count > 0}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data role yang ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="border-t border-gray-100 p-6 dark:border-gray-800" x-show="filteredItems.length > 0">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan <span class="font-medium text-gray-900 dark:text-white" x-text="((currentPage - 1) * itemsPerPage) + 1"></span> - 
                    <span class="font-medium text-gray-900 dark:text-white" x-text="Math.min(currentPage * itemsPerPage, filteredItems.length)"></span>
                    dari <span class="font-medium text-gray-900 dark:text-white" x-text="filteredItems.length"></span> data
                </p>
                <div class="flex items-center gap-2">
                    <button @click="prevPage()" :disabled="currentPage === 1" 
                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        Previous
                    </button>
                    
                    <div class="flex items-center gap-1">
                        <template x-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)" 
                                class="h-8 w-8 rounded-lg text-sm font-medium focus:outline-none transition-colors"
                                :class="currentPage === page ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                                x-text="page">
                            </button>
                        </template>
                    </div>

                    <button @click="nextPage()" :disabled="currentPage === totalPages" 
                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Role Modal -->
        <div x-show="showRoleModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div x-show="showRoleModal" x-transition.opacity class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showRoleModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         @click.away="showRoleModal = false"
                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <form action="{{ route('role.store') }}" method="POST">
                            @csrf
                            <div class="px-6 py-6 pb-4">
                                <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">
                                    Tambah Role / Permission Baru
                                </h3>
                                <div class="mt-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Role / Permission <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" placeholder="Bisa menggunakan huruf kecil, cth: finance">
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Role ini otomatis bisa ditambahkan kepada pengguna di menu User Management.</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-2xl border-t border-gray-100 dark:border-gray-800">
                                <button type="button" @click="showRoleModal = false" class="inline-flex justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Simpan Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
