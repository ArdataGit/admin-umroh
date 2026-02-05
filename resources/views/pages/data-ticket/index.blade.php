@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Ticket" />

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
      tickets: @json($tickets),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "",
      sortDirection: "asc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredTickets() {
        if (!this.searchQuery) return this.tickets;
        return this.tickets.filter(ticket => {
          const query = this.searchQuery.toLowerCase();
          return ticket.kode_tiket.toLowerCase().includes(query) ||
                 ticket.nama_tiket.toLowerCase().includes(query) ||
                 ticket.kode_pnr.toLowerCase().includes(query);
        });
      },
      get sortedTickets() {
        if (!this.sortField) return this.filteredTickets;
        return [...this.filteredTickets].sort((a, b) => {
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
      get totalPages() { return Math.ceil(this.sortedTickets.length / this.itemsPerPage); },
      get paginatedTickets() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.sortedTickets.slice(start, start + this.itemsPerPage);
      },
      sortBy(field) {
        if (this.sortField === field) this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        else { this.sortField = field; this.sortDirection = "asc"; }
        this.currentPage = 1;
      },
      openDeleteModal(id, name) { this.deleteTarget = { id, name }; this.showDeleteModal = true; },
      confirmDelete() {
        if (!this.deleteTarget) return;
        fetch(`/data-tiket/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content, "Accept": "application/json" }
        }).then(r => r.json()).then(d => {
            if (d.success) { this.tickets = this.tickets.filter(t => t.id !== this.deleteTarget.id); this.showDeleteModal = false; }
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
            <a href="{{ route('data-ticket.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm text-white">Tambah Ticket</a>
        </div>
        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search..." class="h-10 w-full rounded-lg border border-gray-300 px-4 text-sm sm:w-64"/>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-2 py-3 text-left">No</th>
                        <th class="px-2 py-3 text-left">Foto</th>
                        <th class="px-2 py-3 text-left cursor-pointer" @click="sortBy('kode_tiket')">Kode</th>
                        <th class="px-2 py-3 text-left cursor-pointer" @click="sortBy('nama_tiket')">Nama Ticket</th>
                        <th class="px-2 py-3 text-left">Pax</th>
                        <th class="px-2 py-3 text-left">PNR</th>
                        <th class="px-2 py-3 text-left">Maskapai</th>
                         <th class="px-2 py-3 text-left">Tgl Berangkat</th>
                        <th class="px-2 py-3 text-left">Status</th>
                        <th class="px-2 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(ticket, index) in paginatedTickets" :key="ticket.id">
                        <tr class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800">
                            <td class="px-2 py-4" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                            <td class="px-2 py-4">
                                <div class="h-10 w-10 overflow-hidden rounded bg-gray-100">
                                    <template x-if="ticket.foto_tiket">
                                        <img :src="'/storage/' + ticket.foto_tiket" class="h-full w-full object-cover" />
                                    </template>
                                    <template x-if="!ticket.foto_tiket">
                                        <div class="flex h-full w-full items-center justify-center text-gray-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <td class="px-2 py-4 font-medium" x-text="ticket.kode_tiket"></td>
                            <td class="px-2 py-4" x-text="ticket.nama_tiket"></td>
                            <td class="px-2 py-4" x-text="ticket.jumlah_tiket"></td>
                             <td class="px-2 py-4" x-text="ticket.kode_pnr"></td>
                            <td class="px-2 py-4" x-text="ticket.maskapai?.nama_maskapai"></td>
                             <td class="px-2 py-4" x-text="ticket.tanggal_keberangkatan"></td>
                            <td class="px-2 py-4">
                                <span :class="ticket.status_tiket === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 rounded text-xs font-semibold uppercase" x-text="ticket.status_tiket"></span>
                            </td>
                            <td class="px-2 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                     <a :href="'/data-tiket/' + ticket.id" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a :href="'/data-tiket/' + ticket.id + '/edit'" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button @click="openDeleteModal(ticket.id, ticket.nama_tiket)" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
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
            <h3 class="text-lg font-bold">Hapus Ticket?</h3>
            <p>Hapus ticket <span x-text="deleteTarget?.name" class="font-bold"></span>?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>

    </div>
  </div>
@endsection
