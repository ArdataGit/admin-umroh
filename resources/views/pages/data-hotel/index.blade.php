@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Hotel" />

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
      hotels: @json($dataHotel),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "",
      sortDirection: "asc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredHotels() {
        if (!this.searchQuery) return this.hotels;
        return this.hotels.filter(hotel => {
          const query = this.searchQuery.toLowerCase();
          return hotel.kode_hotel.toLowerCase().includes(query) ||
                 hotel.nama_hotel.toLowerCase().includes(query) ||
                 hotel.lokasi_hotel.toLowerCase().includes(query) ||
                 hotel.kontak_hotel.toLowerCase().includes(query) ||
                 hotel.email_hotel.toLowerCase().includes(query);
        });
      },
      get sortedHotels() {
        if (!this.sortField) return this.filteredHotels;
        return [...this.filteredHotels].sort((a, b) => {
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
        return Math.ceil(this.sortedHotels.length / this.itemsPerPage);
      },
      get paginatedHotels() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.sortedHotels.slice(start, end);
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
      sortBy(field) {
        if (this.sortField === field) {
          this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        } else {
          this.sortField = field;
          this.sortDirection = "asc";
        }
        this.currentPage = 1;
      },
      prevPage() {
        if (this.currentPage > 1) this.currentPage--;
      },
      nextPage() {
        if (this.currentPage < this.totalPages) this.currentPage++;
      },
      goToPage(page) {
        if (page !== "...") this.currentPage = page;
      },
      getRatingStars(rating) {
        return "⭐".repeat(rating);
      },
      formatPrice(price, kurs, kurs_asing) {
        if (kurs === "IDR" || !kurs) {
            return "Rp " + Number(price).toLocaleString("id-ID");
        }
        const symbol = kurs === "MYR" ? "RM" : kurs;
        const foreignPrice = Number(kurs_asing) > 0 ? kurs_asing : price;
        return symbol + " " + Number(foreignPrice).toLocaleString("id-ID") + " (Rp " + Number(price).toLocaleString("id-ID") + ")";
      },
      openDeleteModal(id, name) {
        this.deleteTarget = { id, name };
        this.showDeleteModal = true;
      },
      confirmDelete() {
        if (!this.deleteTarget) return;
        
        fetch(`/data-hotel/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
            "Accept": "application/json"
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.hotels = this.hotels.filter(h => h.id !== this.deleteTarget.id);
            this.showDeleteModal = false;
            this.deleteTarget = null;
          } else {
            alert("Gagal menghapus data hotel");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("Terjadi kesalahan saat menghapus data");
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

                <a href="{{ route('data-hotel.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.89543 20.1046 9 19 9H5C3.89543 9 3 9.89543 3 11V15C3 16.1046 3.89543 17 5 17H7M17 17V13H7V17M17 17H7M15 9V3H9V9H15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Print
                </a>

                <a href="{{ route('data-hotel.export') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 15.8333H15.8333V4.16667H12.5M12.5 15.8333H7.5C5.04543 15.8333 4.16667 14.9546 4.16667 12.5V7.5C4.16667 5.04543 5.04543 4.16667 7.5 4.16667H12.5M12.5 15.8333V4.16667M9.16667 12.5L10.8333 10M10.8333 10L9.16667 7.5M10.8333 10L10.85 10.025" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Export Excel
                </a>
                
                @if($canCreate)
                <a href="{{ route('data-hotel.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Tambah Data Hotel
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
            <table class="w-full min-w-[1200px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('kode_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'kode_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nama_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'nama_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('lokasi_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Lokasi Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'lokasi_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('kontak_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kontak Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'kontak_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('email_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'email_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('rating_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Rating Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'rating_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('harga_hotel')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Harga Hotel</p>
                                <svg class="w-4 h-4" :class="sortField === 'harga_hotel' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(hotel, index) in paginatedHotels" :key="hotel.id">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="hotel.kode_hotel"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-800 text-theme-sm dark:text-white/90" x-text="hotel.nama_hotel"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="hotel.lokasi_hotel"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="hotel.kontak_hotel"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="hotel.email_hotel"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="getRatingStars(hotel.rating_hotel)"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="formatPrice(hotel.harga_hotel, hotel.kurs, hotel.kurs_asing)"></p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a :href="`/data-hotel/${hotel.id}`" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if($canEdit)
                                    <a :href="`/data-hotel/${hotel.id}/edit`" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if($canDelete)
                                    <button @click="openDeleteModal(hotel.id, hotel.nama_hotel)" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">
                <button @click="prevPage" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
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

                <button @click="nextPage" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
                    <span class="hidden sm:inline">Next</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>




    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         @keydown.escape.window="showDeleteModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/50 dark:bg-gray-900/80"
         style="display: none;">
        <div @click.away="showDeleteModal = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="relative w-full max-w-md mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800">
            
            <!-- Modal Header -->
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus hotel 
                    <span class="font-semibold text-gray-900 dark:text-white" x-text="deleteTarget?.name"></span>?
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <button @click="showDeleteModal = false" 
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button @click="confirmDelete" 
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Hotel
                </button>
            </div>
        </div>
    </div>
    
    </div>



  </div>
@endsection
