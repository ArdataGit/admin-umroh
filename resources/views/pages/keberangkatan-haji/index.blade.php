@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Keberangkatan Haji" :breadcrumbs="[
    ['label' => 'Keberangkatan Haji', 'url' => '#']
]" />

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
  {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
      items: @json($keberangkatan),
      currentPage: 1,
      itemsPerPage: 10,
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredItems() {
        if (!this.searchQuery) return this.items;
        const query = this.searchQuery.toLowerCase();
        return this.items.filter(i => {
          return i.kode_keberangkatan.toLowerCase().includes(query) ||
                 i.nama_keberangkatan.toLowerCase().includes(query);
        });
      },
      get paginatedItems() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredItems.slice(start, start + this.itemsPerPage);
      },
      get totalPages() { return Math.ceil(this.filteredItems.length / this.itemsPerPage); },
      openDeleteModal(id, kode) { this.deleteTarget = { id, kode }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/keberangkatan-haji/${this.deleteTarget.id}`, {
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
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jadwal Keberangkatan Haji</h3>
                    <a href="{{ route('keberangkatan-haji.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                        Tambah Jadwal
                    </a>
                </div>
                 <div class="relative">
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white sm:w-64"/>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Kode Keberangkatan</th>
                            <th class="px-6 py-3">Nama Keberangkatan</th>
                            <th class="px-6 py-3">Tanggal Keberangkatan</th>
                            <th class="px-6 py-3 text-center">Jumlah Hari</th>
                            <th class="px-6 py-3">Nama Maskapai</th>
                            <th class="px-6 py-3">Rute Penerbangan</th>
                            <th class="px-6 py-3">Lokasi Keberangkatan</th>
                            <th class="px-6 py-3 text-center">Kuota Jamaah</th>
                            <th class="px-6 py-3 text-center">Kuota Terisi</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Manifest Jamaah</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in paginatedItems" :key="item.id">
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td class="px-6 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                                <td class="px-6 py-4 font-medium" x-text="item.kode_keberangkatan"></td>
                                <td class="px-6 py-4">
                                    <a :href="`/paket-haji/${item.paket_haji_id}`" class="font-medium text-blue-600 hover:text-blue-500 hover:underline" x-text="item.nama_keberangkatan"></a>
                                </td>
                                <td class="px-6 py-4" x-text="new Date(item.tanggal_keberangkatan).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></td>
                                <td class="px-6 py-4 text-center" x-text="item.jumlah_hari + ' Hari'"></td>
                                <td class="px-6 py-4" x-text="item.paket_haji?.maskapai?.nama_maskapai || '-'"></td>
                                <td class="px-6 py-4" x-text="item.paket_haji?.rute_penerbangan || '-'"></td>
                                <td class="px-6 py-4" x-text="item.paket_haji?.lokasi_keberangkatan || '-'"></td>
                                <td class="px-6 py-4 text-center" x-text="item.kuota_jamaah + ' Pax'"></td>
                                <td class="px-6 py-4 text-center">0 Pax</td> <!-- Placeholder logic -->
                                <td class="px-6 py-4">
                                     <span :class="{
                                        'bg-blue-100 text-blue-800': item.status_keberangkatan === 'active',
                                        'bg-green-100 text-green-800': item.status_keberangkatan === 'completed'
                                     }" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="item.status_keberangkatan"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a :href="`/customer-haji/${item.id}`" class="inline-block text-blue-600 hover:text-blue-800 font-medium text-xs" title="Lihat Manifest">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-auto">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a :href="`/keberangkatan-haji/${item.id}`" class="text-gray-500 hover:text-gray-700" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                        <a :href="`/keberangkatan-haji/${item.id}/edit`" class="text-blue-500 hover:text-blue-700" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                        <button @click="openDeleteModal(item.id, item.kode_keberangkatan)" class="text-red-500 hover:text-red-700" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                         <tr x-show="paginatedItems.length === 0">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data keberangkatan haji.</td>
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
                    <h3 class="text-lg font-bold">Hapus Keberangkatan?</h3>
                    <p>Hapus jadwal <span x-text="deleteTarget?.kode" class="font-bold"></span>?</p>
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
