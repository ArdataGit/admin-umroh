@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Layanan" />

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
      layanans: @json($dataLayanan),
      currentPage: 1,
      itemsPerPage: 10,
      sortField: "",
      sortDirection: "asc",
      searchQuery: "",
      showDeleteModal: false,
      deleteTarget: null,
      get filteredLayanans() {
        if (!this.searchQuery) return this.layanans;
        return this.layanans.filter(layanan => {
          const query = this.searchQuery.toLowerCase();
          return layanan.kode_layanan.toLowerCase().includes(query) ||
                 layanan.nama_layanan.toLowerCase().includes(query) ||
                 layanan.jenis_layanan.toLowerCase().includes(query);
        });
      },
      get sortedLayanans() {
        if (!this.sortField) return this.filteredLayanans;
        return [...this.filteredLayanans].sort((a, b) => {
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
        return Math.ceil(this.sortedLayanans.length / this.itemsPerPage);
      },
      get paginatedLayanans() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.sortedLayanans.slice(start, end);
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
      openDeleteModal(id, name) {
        this.deleteTarget = { id, name };
        this.showDeleteModal = true;
      },
      confirmDelete() {
        if (!this.deleteTarget) return;
        
        fetch(`/data-layanan/${this.deleteTarget.id}`, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
            "Accept": "application/json"
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.layanans = this.layanans.filter(l => l.id !== this.deleteTarget.id);
            this.showDeleteModal = false;
            this.deleteTarget = null;
          } else {
            alert("Gagal menghapus data layanan");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("Terjadi kesalahan saat menghapus data");
        });
      },
      formatPrice(price, currency, foreignPrice) {
        if (!currency || currency === "IDR") {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(price);
        } else {
            const symbol = currency === "MYR" ? "RM" : currency;
            return `${symbol} ${parseFloat(foreignPrice).toLocaleString("en-US")} (Rp ${new Intl.NumberFormat("id-ID").format(price)})`;
        }
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

                <a href="{{ route('data-layanan.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-gray-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 17H19C20.1046 17 21 16.1046 21 15V11C21 9.89543 20.1046 9 19 9H5C3.89543 9 3 9.89543 3 11V15C3 16.1046 3.89543 17 5 17H7M17 17V13H7V17M17 17H7M15 9V3H9V9H15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Print
                </a>
                
                <a href="{{ route('data-layanan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Tambah Data Layanan
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
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Foto</p>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('kode_layanan')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Layanan</p>
                                <svg class="w-4 h-4" :class="sortField === 'kode_layanan' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                         <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('jenis_layanan')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jenis Layanan</p>
                                <svg class="w-4 h-4" :class="sortField === 'jenis_layanan' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nama_layanan')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Layanan</p>
                                <svg class="w-4 h-4" :class="sortField === 'nama_layanan' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Harga Jual</p>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('status_layanan')">
                            <div class="flex items-center gap-1">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                                <svg class="w-4 h-4" :class="sortField === 'status_layanan' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
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
                    <template x-for="(layanan, index) in paginatedLayanans" :key="layanan.id">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="h-10 w-10 overflow-hidden rounded-full">
                                    <img :src="layanan.foto_layanan ? '/storage/' + layanan.foto_layanan : 'https://placehold.co/400x400/e2e8f0/1e293b?text=' + layanan.nama_layanan.charAt(0).toUpperCase()" alt="Layanan" class="h-full w-full object-cover"/>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="layanan.kode_layanan"></p>
                            </td>
                             <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="layanan.jenis_layanan"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-800 text-theme-sm dark:text-gray-400" x-text="layanan.nama_layanan"></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="formatPrice(layanan.harga_jual, layanan.kurs, layanan.harga_jual_asing)"></p>
                            </td>
                            <td class="px-4 py-4">
                                <span :class="layanan.status_layanan === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'" class="inline-flex rounded-full px-2 py-1 text-xs font-medium">
                                    <span x-text="layanan.status_layanan"></span>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-center gap-2">
                                    <a :href="`/data-layanan/${layanan.id}`" title="View" class="inline-flex items-center justify-center rounded-lg bg-green-500 p-2 text-white hover:bg-green-600">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 12C11.1046 12 12 11.1046 12 10C12 8.89543 11.1046 8 10 8C8.89543 8 8 8.89543 8 10C8 11.1046 8.89543 12 10 12Z" fill="currentColor"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.458008 10C1.73201 5.943 5.52201 3 10.002 3C14.482 3 18.272 5.943 19.546 10C18.272 14.057 14.482 17 10.002 17C5.52201 17 1.73201 14.057 0.458008 10ZM14 10C14 12.2091 12.2091 14 10 14C7.79086 14 6 12.2091 6 10C6 7.79086 7.79086 6 10 6C12.2091 6 14 7.79086 14 10Z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    <a :href="`/data-layanan/${layanan.id}/edit`" title="Edit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 p-2 text-white hover:bg-blue-600">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.5858 3.58579C14.3668 2.80474 15.6332 2.80474 16.4142 3.58579C17.1953 4.36683 17.1953 5.63316 16.4142 6.41421L15.6213 7.20711L12.7929 4.37868L13.5858 3.58579Z" fill="currentColor"/>
                                            <path d="M11.3787 5.79289L3 14.1716V17H5.82842L14.2071 8.62132L11.3787 5.79289Z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    <button @click="openDeleteModal(layanan.id, layanan.nama_layanan)" title="Delete" class="inline-flex items-center justify-center rounded-lg bg-red-500 p-2 text-white hover:bg-red-600">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2C8.62123 2 8.27497 2.214 8.10557 2.55279L7.38197 4H4C3.44772 4 3 4.44772 3 5C3 5.55228 3.44772 6 4 6V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V6C16.5523 6 17 5.55228 17 5C17 4.44772 16.5523 4 16 4H12.618L11.8944 2.55279C11.725 2.214 11.3788 2 11 2H9ZM7 8C7 7.44772 7.44772 7 8 7C8.55228 7 9 7.44772 9 8V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V8ZM12 7C11.4477 7 11 7.44772 11 8V14C11 14.5523 11.4477 15 12 15C12.5523 15 13 14.5523 13 14V8C13 7.44772 12.5523 7 12 7Z" fill="currentColor"/>
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
                    Apakah Anda yakin ingin menghapus layanan 
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
                    Hapus Layanan
                </button>
            </div>
        </div>
    </div>
    
    </div>



  </div>
@endsection
