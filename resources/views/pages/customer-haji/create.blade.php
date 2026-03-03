@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Jamaah Haji" :breadcrumbs="[
    ['label' => 'Manifest', 'url' => route('customer-haji.index', $keberangkatan->id)],
    ['label' => 'Tambah Jamaah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="jamaahForm()">
        <form @submit.prevent="submitForm">
             <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Registrasi Jamaah Haji</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Keberangkatan (Read Only) -->
                    <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Keberangkatan</div>
                                <div class="font-semibold text-gray-800 dark:text-white">{{ $keberangkatan->nama_keberangkatan }}</div>
                                <div class="text-xs text-gray-500">{{ $keberangkatan->kode_keberangkatan }} | {{ $keberangkatan->tanggal_keberangkatan }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Paket Haji</div>
                                <div class="font-bold text-gray-800 dark:text-white">{{ $keberangkatan->paketHaji->nama_paket }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Jamaah Selection -->
                    <div class="md:col-span-2 relative" @click.away="showJamaahDropdown = false">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jamaah</label>
                        <div class="relative">
                            <input type="text" x-model="searchJamaah" @focus="showJamaahDropdown = true" @input="showJamaahDropdown = true; form.jamaah_id = ''" placeholder="Cari Kode atau Nama Jamaah..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div x-show="showJamaahDropdown" style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                            <ul>
                                <template x-for="jamaah in filteredJamaahs" :key="jamaah.id">
                                    <li @click="selectJamaah(jamaah)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 border-b dark:border-gray-700 last:border-0">
                                        <span x-text="jamaah.kode_jamaah + ' - ' + jamaah.nama_lengkap"></span>
                                    </li>
                                </template>
                                <li x-show="filteredJamaahs.length === 0" class="px-4 py-2 text-sm text-gray-500">
                                    Jamaah tidak ditemukan.
                                </li>
                            </ul>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Pastikan data jamaah sudah terdaftar di master data jamaah.</p>
                    </div>

                    <!-- Agent Selection -->
                    <div class="relative" @click.away="showAgentDropdown = false">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Agent / Marketing</label>
                        <div class="relative">
                            <input type="text" x-model="searchAgent" @focus="showAgentDropdown = true" @input="showAgentDropdown = true; form.agent_id = ''" placeholder="Cari Kode atau Nama Agent..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div x-show="showAgentDropdown" style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-60 overflow-y-auto">
                            <ul>
                                <template x-for="agent in filteredAgents" :key="agent.id">
                                    <li @click="selectAgent(agent)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-800 dark:text-gray-300 border-b dark:border-gray-700 last:border-0">
                                        <span x-text="agent.kode_agent + ' - ' + agent.nama_agent"></span>
                                    </li>
                                </template>
                                <li x-show="filteredAgents.length === 0" class="px-4 py-2 text-sm text-gray-500">
                                    Agent tidak ditemukan.
                                </li>
                            </ul>
                        </div>
                    </div>

                     <!-- Tipe Kamar -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Kamar</label>
                        <select x-model="form.tipe_kamar" @change="updateHarga" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Tipe Kamar --</option>
                            @if($keberangkatan->paketHaji->harga_quad_1 > 0) <option value="quad">Quad (Rp {{ number_format($keberangkatan->paketHaji->harga_quad_1, 0, ',', '.') }})</option> @endif
                            @if($keberangkatan->paketHaji->harga_triple_1 > 0) <option value="triple">Triple (Rp {{ number_format($keberangkatan->paketHaji->harga_triple_1, 0, ',', '.') }})</option> @endif
                            @if($keberangkatan->paketHaji->harga_double_1 > 0) <option value="double">Double (Rp {{ number_format($keberangkatan->paketHaji->harga_double_1, 0, ',', '.') }})</option> @endif
                        </select>
                    </div>

                    <!-- Jumlah Jamaah -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Jamaah (Pax)</label>
                         <input type="number" x-model="form.jumlah_jamaah" min="1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                    </div>

                     <!-- Nama Keluarga -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Keluarga (Grup)</label>
                         <input type="text" x-model="form.nama_keluarga" placeholder="Contoh: Kel. Bpk Ahmad" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <!-- Financials -->
                    <div class="md:col-span-2 border-t border-gray-100 pt-4 mt-2 dark:border-gray-700">
                         <h4 class="mb-4 font-semibold text-gray-800 dark:text-white">Rincian Biaya</h4>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Paket (Per Pax)</label>
                                <input type="number" x-model="form.harga_paket" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Diskon (Nominal)</label>
                                <input type="number" x-model="form.diskon" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="0" />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Total Tagihan</label>
                                <input type="text" :value="formatRupiah(totalTagihan)" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-bold text-gray-800" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pembayaran Awal (DP) <span class="text-red-500">*</span></label>
                                <input type="number" x-model="form.total_bayar" 
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" 
                                    :class="{'border-red-500': form.total_bayar < 1}"
                                    required min="1" />
                                <p x-show="form.total_bayar < 1" class="mt-1 text-xs text-red-500">Pembayaran awal wajib diisi minimal Rp 1</p>
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                                <select x-model="form.metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                    <option value="cash">Cash / Tunai</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="debit">Debit Card</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                         </div>
                    </div>

                    <!-- Checklist Status -->
                     <div class="md:col-span-2 border-t border-gray-100 pt-4 mt-2 dark:border-gray-700">
                         <h4 class="mb-4 font-semibold text-gray-800 dark:text-white">Status Dokumen & Perlengkapan</h4>
                         <div class="flex flex-wrap gap-6">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" x-model="form.status_visa" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                <span class="text-sm text-gray-700 dark:text-gray-400">Visa Approved</span>
                            </label>
                             <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" x-model="form.status_tiket" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                <span class="text-sm text-gray-700 dark:text-gray-400">Tiket Issued</span>
                            </label>
                             <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" x-model="form.status_siskopatuh" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                <span class="text-sm text-gray-700 dark:text-gray-400">Input Siskopatuh</span>
                            </label>
                             <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" x-model="form.status_perlengkapan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                <span class="text-sm text-gray-700 dark:text-gray-400">Perlengkapan Diterima</span>
                            </label>
                         </div>
                    </div>
                </div>

                 <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('customer-haji.index', $keberangkatan->id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Jamaah</button>
                </div>
             </div>
        </form>
    </div>
</div>

<script>
    function jamaahForm() {
        return {
            form: {
                jamaah_id: '',
                agent_id: '',
                tipe_kamar: '',
                jumlah_jamaah: 1,
                nama_keluarga: '',
                harga_paket: 0,
                diskon: 0,
                total_bayar: 0,
                metode_pembayaran: 'transfer',
                status_visa: false,
                status_tiket: false,
                status_siskopatuh: false,
                status_perlengkapan: false,
                catatan: ''
            },
            agents: @json($agents),
            searchAgent: '',
            showAgentDropdown: false,
            get filteredAgents() {
                if (this.searchAgent === '') {
                    return this.agents;
                }
                const query = this.searchAgent.toLowerCase();
                return this.agents.filter(a => 
                    a.nama_agent.toLowerCase().includes(query) || 
                    a.kode_agent.toLowerCase().includes(query)
                );
            },
            selectAgent(agent) {
                this.form.agent_id = agent.id;
                this.searchAgent = agent.kode_agent + ' - ' + agent.nama_agent;
                this.showAgentDropdown = false;
            },

            jamaahs: @json($jamaahs),
            searchJamaah: '',
            showJamaahDropdown: false,
            get filteredJamaahs() {
                if (this.searchJamaah === '') {
                    return this.jamaahs;
                }
                const query = this.searchJamaah.toLowerCase();
                return this.jamaahs.filter(j => 
                    j.nama_lengkap.toLowerCase().includes(query) || 
                    j.kode_jamaah.toLowerCase().includes(query)
                );
            },
            selectJamaah(jamaah) {
                this.form.jamaah_id = jamaah.id;
                this.searchJamaah = jamaah.kode_jamaah + ' - ' + jamaah.nama_lengkap;
                this.showJamaahDropdown = false;
            },
            paketPrices: {
                quad: {{ $keberangkatan->paketHaji->harga_quad_1 ?? 0 }},
                triple: {{ $keberangkatan->paketHaji->harga_triple_1 ?? 0 }},
                double: {{ $keberangkatan->paketHaji->harga_double_1 ?? 0 }}
            },
            updateHarga() {
                if(this.form.tipe_kamar && this.paketPrices[this.form.tipe_kamar]) {
                    this.form.harga_paket = this.paketPrices[this.form.tipe_kamar];
                } else {
                    this.form.harga_paket = 0;
                }
            },
            get totalTagihan() {
                return (this.form.harga_paket * this.form.jumlah_jamaah) - this.form.diskon;
            },
            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            },
             submitForm() {
                // Pre-submission validation
                if (!this.form.jamaah_id) {
                    alert('Gagal: Silakan pilih Jamaah dari daftar.');
                    return;
                }
                if (!this.form.agent_id) {
                    alert('Gagal: Silakan pilih Agent dari daftar.');
                    return;
                }
                if (!this.form.total_bayar || this.form.total_bayar < 1) {
                    alert('Gagal: Pembayaran awal (DP) harus diisi dan minimal Rp 1.');
                    return;
                }

                fetch('{{ route('customer-haji.store', $keberangkatan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        // Handle validation errors if available
                        if (data.errors) {
                            let errorMsg = 'Terjadi kesalahan validasi:\n';
                            for (const key in data.errors) {
                                errorMsg += `- ${data.errors[key].join(', ')}\n`;
                            }
                            alert(errorMsg);
                        } else {
                            alert(data.message || 'Terjadi kesalahan sistem');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada sistem');
                });
            }
        }
    }
</script>
@endsection
