@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Jamaah" :breadcrumbs="[
    ['label' => 'Manifest Jamaah', 'url' => route('customer-umroh.index', $keberangkatan->id)],
    ['label' => 'Tambah Jamaah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="customerUmrohForm()">
        <form @submit.prevent="submitForm">
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Tambah Jamaah</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- Nama Keberangkatan -->
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Keberangkatan</label>
                    <input type="text" value="{{ $keberangkatan->kode_keberangkatan }} - {{ $keberangkatan->nama_keberangkatan }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>

                <!-- Jamaah Selection -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jamaah</label>
                    <select x-model="form.jamaah_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Jamaah --</option>
                        @foreach($jamaahs as $j)
                            <option value="{{ $j->id }}">{{ $j->kode_jamaah }} - {{ $j->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Agent Selection -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Agent</label>
                    <select x-model="form.agent_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Agent --</option>
                        @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->kode_agent }} - {{ $a->nama_agent }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Kamar -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Kamar</label>
                    <select x-model="form.tipe_kamar" @change="updatePrice" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Tipe Kamar --</option>
                        <option value="quad">Quad</option>
                        <option value="triple">Triple</option>
                        <option value="double">Double</option>
                    </select>
                </div>

                <!-- Jumlah Jamaah -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Jamaah (Pax)</label>
                    <input type="number" x-model.number="form.jumlah_jamaah" min="1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                </div>
                
                <!-- Nama Keluarga -->
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Keluarga (Leader)</label>
                    <input type="text" x-model="form.nama_keluarga" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Keluarga Bpk. Ahmad" />
                </div>

                 <!-- Harga Total Paket (Editable) -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Total Paket</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                         <input type="number" x-model.number="form.total_harga_paket" @input="updateUnitPrice" class="w-full pl-10 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>

                 <!-- Diskon -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Diskon (Nominal)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                        <input type="number" x-model.number="form.diskon" class="w-full pl-10 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>

                 <!-- Total Setelah Diskon (Auto) -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Total Tagihan (Setelah Diskon)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                         <input type="text" :value="formatRupiah(totalTagihan)" readonly class="w-full pl-10 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 font-bold" />
                    </div>
                </div>
                
                  <!-- DP -->
                  <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Total Pembayaran (DP) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                        <input type="number" x-model.number="form.total_bayar" 
                            class="w-full pl-10 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" 
                            :class="{'border-red-500': form.total_bayar < 1}"
                            required min="1" />
                    </div>
                    <p x-show="form.total_bayar < 1" class="mt-1 text-xs text-red-500">Pembayaran DP wajib diisi minimal Rp 1</p>
                </div>


                 <!-- Metode Pembayaran -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                     <select x-model="form.metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="debit">Debit</option>
                        <option value="qris">QRIS</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                 <!-- Sisa Pembayaran (Auto) -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Sisa Pembayaran</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                         <input type="text" :value="formatRupiah(sisaPembayaran)" readonly class="w-full pl-10 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-red-500 font-bold" />
                    </div>
                </div>

                 <!-- Processing Checkboxes -->
                <div class="md:col-span-2 space-y-4 pt-4 border-t dark:border-gray-700">
                    <h4 class="font-medium text-gray-800 dark:text-white">Status Proses</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" x-model="form.status_visa" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            <span class="text-sm text-gray-700 dark:text-gray-400">Proses Visa</span>
                        </label>
                         <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" x-model="form.status_tiket" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            <span class="text-sm text-gray-700 dark:text-gray-400">Proses Tiket</span>
                        </label>
                         <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" x-model="form.status_siskopatuh" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            <span class="text-sm text-gray-700 dark:text-gray-400">Proses Siskopatuh</span>
                        </label>
                         <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" x-model="form.status_perlengkapan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            <span class="text-sm text-gray-700 dark:text-gray-400">Perlengkapan</span>
                        </label>
                    </div>
                </div>
                
                 <!-- Catatan -->
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Manifest (Optional)</label>
                    <textarea x-model="form.catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('customer-umroh.index', $keberangkatan->id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Jamaah</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function customerUmrohForm() {
        return {
            prices: {
                quad: {{ $keberangkatan->paketUmroh->harga_quad_1 }},
                triple: {{ $keberangkatan->paketUmroh->harga_triple_1 }},
                double: {{ $keberangkatan->paketUmroh->harga_double_1 }}
            },
            form: {
                jamaah_id: '',
                agent_id: '',
                tipe_kamar: '',
                jumlah_jamaah: 1,
                nama_keluarga: '',
                harga_paket: 0,
                total_harga_paket: 0,
                diskon: 0,
                total_bayar: 0,
                metode_pembayaran: 'cash',
                status_visa: false,
                status_tiket: false,
                status_siskopatuh: false,
                status_perlengkapan: false,
                catatan: ''
            },
            init() {
                this.$watch('form.jumlah_jamaah', (value) => {
                    this.updateTotalPackagePrice();
                });
            },
            updatePrice() {
                if (this.form.tipe_kamar && this.prices[this.form.tipe_kamar]) {
                    this.form.harga_paket = this.prices[this.form.tipe_kamar];
                    this.updateTotalPackagePrice();
                } else {
                    this.form.harga_paket = 0;
                    this.form.total_harga_paket = 0;
                }
            },
            updateTotalPackagePrice() {
                this.form.total_harga_paket = this.form.harga_paket * this.form.jumlah_jamaah;
            },
            updateUnitPrice() {
                // When manual total is entered, reverse calc unit price
                if (this.form.jumlah_jamaah > 0) {
                    this.form.harga_paket = this.form.total_harga_paket / this.form.jumlah_jamaah;
                }
            },
            get totalTagihan() {
                return Math.max(0, this.form.total_harga_paket - this.form.diskon);
            },
            get sisaPembayaran() {
                return Math.max(0, this.totalTagihan - this.form.total_bayar);
            },
            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            },
             submitForm() {
                // Pre-submission validation
                if (!this.form.total_bayar || this.form.total_bayar < 1) {
                    alert('Gagal: Pembayaran DP harus diisi dan minimal Rp 1.');
                    return;
                }

                fetch('{{ route('customer-umroh.store', $keberangkatan->id) }}', {
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
