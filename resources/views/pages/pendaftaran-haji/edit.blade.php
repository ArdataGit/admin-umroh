@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pendaftaran Haji" :breadcrumbs="[
    ['label' => 'Pendaftaran Haji', 'url' => route('pendaftaran-haji.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="col-span-12" x-data="pendaftaranHajiForm()">
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- SECTION 1: DATA PERSONAL JAMAAH -->
            <div class="space-y-6">
                 <!-- Personal Info Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">1. Data Pribadi Jamaah</h3>
                    <div class="space-y-4">
                        <!-- Kode & Agent -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Jamaah</label>
                                <input type="text" x-model="form.kode_jamaah" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 font-bold" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Agent</label>
                                <select x-model="form.agent_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                    <option value="">-- Pilih Agent --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->nama_agent }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <!-- NIK & Nama -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK Jamaah</label>
                            <input type="number" x-model="form.nik_jamaah" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah (Sesuai KTP)</label>
                            <input type="text" x-model="form.nama_jamaah" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        </div>

                        <!-- TTL & Gender -->
                        <div class="grid grid-cols-2 gap-4">
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat Lahir</label>
                                <input type="text" x-model="form.tempat_lahir" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Lahir</label>
                                <input type="date" x-model="form.tanggal_lahir" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                            </div>
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kelamin</label>
                            <select x-model="form.jenis_kelamin" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Contacts -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">No. HP / WA</label>
                                <input type="text" x-model="form.kontak_jamaah" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Email (Opsional)</label>
                                <input type="email" x-model="form.email_jamaah" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            </div>
                        </div>

                         <!-- Address -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Provinsi</label>
                                <input type="text" x-model="form.provinsi" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" required />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kota/Kab</label>
                                <input type="text" x-model="form.kabupaten_kota" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" required />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                <input type="text" x-model="form.kecamatan" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" required />
                            </div>
                        </div>
                        <div>
                             <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Singkat</label>
                            <input type="text" x-model="form.alamat_jamaah" placeholder="Jl. Contoh No. 123" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        </div>
                        <div>
                             <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Lengkap (RT/RW/Kel)</label>
                            <textarea x-model="form.alamat_lengkap" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Passport Info Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                     <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">2. Data Paspor & Dokumen</h3>
                     <div class="space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama di Paspor</label>
                            <input type="text" x-model="form.nama_paspor" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Paspor</label>
                                <input type="text" x-model="form.nomor_paspor" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kantor Imigrasi</label>
                                <input type="text" x-model="form.kantor_imigrasi" placeholder="Penerbit" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tgl Aktif</label>
                                <input type="date" x-model="form.tgl_paspor_aktif" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tgl Expired</label>
                                <input type="date" x-model="form.tgl_paspor_expired" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                        </div>
                        
                        <!-- Uploads -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Helper to display file link -->
                            <template x-for="field in ['foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_paspor_1', 'foto_paspor_2']">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400" x-text="formatLabel(field)"></label>
                                    <div class="flex flex-col gap-2">
                                        <template x-if="existingFiles[field]">
                                            <a :href="`/storage/${existingFiles[field]}`" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat File Saat Ini</a>
                                        </template>
                                        <input type="file" @change="handleFile($event, field)" class="text-sm file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-blue-700 hover:file:bg-blue-100 dark:text-white" />
                                    </div>
                                </div>
                            </template>
                        </div>
                     </div>
                </div>
            </div>

            <!-- SECTION 2: DATA MANIFEST / KEBERANGKATAN -->
            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 sticky top-6">
                     <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">3. Data Keberangkatan (Manifest)</h3>
                     
                     <div class="space-y-4">
                        <!-- Paket Selection -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jadwal Keberangkatan</label>
                            <select x-model="form.keberangkatan_haji_id" @change="onPaketChange" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach($keberangkatans as $k)
                                    <option value="{{ $k->id }}" 
                                        data-quad="{{ $k->paketHaji->harga_quad_1 }}"
                                        data-triple="{{ $k->paketHaji->harga_triple_1 }}"
                                        data-double="{{ $k->paketHaji->harga_double_1 }}"
                                    >
                                        {{ $k->kode_keberangkatan }} - {{ $k->nama_keberangkatan }} ({{ \Carbon\Carbon::parse($k->tanggal_keberangkatan)->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                         <!-- Room Type -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Kamar</label>
                            <select x-model="form.tipe_kamar" @change="updateHarga" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="quad">Quad (Sekamar Berempat)</option>
                                <option value="triple">Triple (Sekamar Bertiga)</option>
                                <option value="double">Double (Sekamar Berdua)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Jamaah (Pax)</label>
                                <input type="number" x-model="form.jumlah_jamaah" min="1" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" required />
                            </div>
                             <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Keluarga (Grup)</label>
                                <input type="text" x-model="form.nama_keluarga" placeholder="Opsional" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                        </div>

                        <!-- Financials -->
                        <div class="border-t border-gray-100 pt-4 mt-2 dark:border-gray-700 bg-gray-50 p-4 rounded-lg">
                            <h4 class="mb-3 font-bold text-gray-800">Rincian Pembayaran</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-500">Harga Paket (Per Pax)</label>
                                    <input type="text" :value="formatRupiah(form.harga_paket)" readonly class="w-full bg-transparent font-medium text-gray-800 border-none p-0 focus:ring-0" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Diskon (Nominal)</label>
                                    <input type="number" x-model="form.diskon" class="w-full rounded border-gray-200 px-2 py-1 text-sm focus:border-blue-500" placeholder="0" />
                                </div>
                                <div class="border-t border-gray-200 pt-2">
                                     <label class="text-xs text-gray-500 font-bold">Total Harga (Setelah Diskon)</label>
                                     <div class="text-xl font-bold text-blue-600" x-text="formatRupiah(totalTagihan)"></div>
                                </div>
                                
                                <div class="mt-4">
                                     <label class="mb-1 block text-sm font-medium text-gray-700">Pembayaran DP / Total Bayar <span class="text-red-500">*</span></label>
                                    <input type="number" x-model="form.total_bayar" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 dark:bg-gray-900" 
                                        :class="{'border-red-500': form.total_bayar < 1}" 
                                        required min="1" />
                                    <p x-show="form.total_bayar < 1" class="mt-1 text-xs text-red-500">Pembayaran wajib diisi minimal Rp 1</p>
                                </div>
                                
                                <div>
                                     <label class="mb-1 block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                    <select x-model="form.metode_pembayaran" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:bg-gray-900" required>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cash">Cash / Tunai</option>
                                        <option value="debit">Debit Card</option>
                                        <option value="qris">QRIS</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>

                                <div class="border-t border-gray-200 pt-2">
                                    <div class="flex justify-between items-center bg-red-50 p-2 rounded">
                                        <span class="text-sm font-bold text-red-800">Sisa Pembayaran</span>
                                        <span class="text-lg font-bold text-red-600" x-text="formatRupiah(sisaTagihan)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Checklists -->
                        <div>
                             <h4 class="mb-2 font-semibold text-sm text-gray-800 dark:text-white">Checklist Proses</h4>
                             <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center space-x-2"><input type="checkbox" x-model="form.status_visa" class="rounded text-blue-600" /> <span class="text-sm">Proses Visa</span></label>
                                <label class="flex items-center space-x-2"><input type="checkbox" x-model="form.status_tiket" class="rounded text-blue-600" /> <span class="text-sm">Proses Tiket</span></label>
                                <label class="flex items-center space-x-2"><input type="checkbox" x-model="form.status_siskopatuh" class="rounded text-blue-600" /> <span class="text-sm">Input Siskopatuh</span></label>
                                <label class="flex items-center space-x-2"><input type="checkbox" x-model="form.status_perlengkapan" class="rounded text-blue-600" /> <span class="text-sm">Perlengkapan</span></label>
                             </div>
                        </div>

                         <div>
                             <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pendaftaran</label>
                            <textarea x-model="form.catatan_pendaftaran" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm resize-none dark:bg-gray-900 dark:border-gray-700"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-green-600 px-6 py-3 text-white font-medium hover:bg-green-700 transition">
                            Simpan Perubahan
                        </button>
                     </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function pendaftaranHajiForm() {
        return {
            form: {
                // Jamaah Data from DB
                kode_jamaah: '{{ $pendaftaran->jamaah->kode_jamaah }}',
                agent_id: '{{ $pendaftaran->agent_id }}',
                nik_jamaah: '{{ $pendaftaran->jamaah->nik_jamaah }}',
                nama_jamaah: '{{ $pendaftaran->jamaah->nama_jamaah }}',
                jenis_kelamin: '{{ $pendaftaran->jamaah->jenis_kelamin }}',
                tempat_lahir: '{{ $pendaftaran->jamaah->tempat_lahir }}',
                tanggal_lahir: '{{ $pendaftaran->jamaah->tanggal_lahir }}',
                kontak_jamaah: '{{ $pendaftaran->jamaah->kontak_jamaah }}',
                email_jamaah: '{{ $pendaftaran->jamaah->email_jamaah }}',
                provinsi: '{{ $pendaftaran->jamaah->provinsi }}',
                kabupaten_kota: '{{ $pendaftaran->jamaah->kabupaten_kota }}',
                kecamatan: '{{ $pendaftaran->jamaah->kecamatan }}',
                alamat_jamaah: '{{ $pendaftaran->jamaah->alamat_jamaah }}',
                alamat_lengkap: '{{ $pendaftaran->jamaah->alamat_lengkap }}',
                catatan_jamaah: '{{ $pendaftaran->jamaah->catatan_jamaah }}',
                
                // Passport
                nama_paspor: '{{ $pendaftaran->jamaah->nama_paspor }}',
                nomor_paspor: '{{ $pendaftaran->jamaah->nomor_paspor }}',
                kantor_imigrasi: '{{ $pendaftaran->jamaah->kantor_imigrasi }}',
                tgl_paspor_aktif: '{{ $pendaftaran->jamaah->tgl_paspor_aktif }}',
                tgl_paspor_expired: '{{ $pendaftaran->jamaah->tgl_paspor_expired }}',

                // Manifest
                keberangkatan_haji_id: '{{ $pendaftaran->keberangkatan_haji_id }}',
                tipe_kamar: '{{ $pendaftaran->tipe_kamar }}',
                jumlah_jamaah: {{ $pendaftaran->jumlah_jamaah }},
                nama_keluarga: '{{ $pendaftaran->nama_keluarga }}',
                harga_paket: {{ $pendaftaran->harga_paket }}, // Existing saved price
                diskon: {{ $pendaftaran->diskon }},
                total_bayar: {{ $pendaftaran->total_bayar }},
                metode_pembayaran: '{{ $pendaftaran->metode_pembayaran }}',
                status_visa: {{ $pendaftaran->status_visa ? 'true' : 'false' }},
                status_tiket: {{ $pendaftaran->status_tiket ? 'true' : 'false' }},
                status_siskopatuh: {{ $pendaftaran->status_siskopatuh ? 'true' : 'false' }},
                status_perlengkapan: {{ $pendaftaran->status_perlengkapan ? 'true' : 'false' }},
                catatan_pendaftaran: '{{ $pendaftaran->catatan }}'
            },
            
            existingFiles: {
                foto_jamaah: '{{ $pendaftaran->jamaah->foto_jamaah }}',
                foto_ktp: '{{ $pendaftaran->jamaah->foto_ktp }}',
                foto_kk: '{{ $pendaftaran->jamaah->foto_kk }}',
                foto_paspor_1: '{{ $pendaftaran->jamaah->foto_paspor_1 }}',
                foto_paspor_2: '{{ $pendaftaran->jamaah->foto_paspor_2 }}',
            },

            files: {},
            selectedPaketDetails: null, // Temporary storage for pricing logic
            
            formatLabel(field) {
                const labels = {
                    'foto_jamaah': 'Foto Jamaah',
                    'foto_ktp': 'Foto KTP',
                    'foto_kk': 'Foto KK',
                    'foto_paspor_1': 'Foto Paspor (Hal 1)',
                    'foto_paspor_2': 'Foto Paspor (Hal 2)'
                };
                return labels[field] || field;
            },

            onPaketChange(e) {
                const select = e.target;
                const option = select.options[select.selectedIndex];
                if(option.value) {
                    this.selectedPaketDetails = {
                        quad: parseInt(option.dataset.quad || 0),
                        triple: parseInt(option.dataset.triple || 0),
                        double: parseInt(option.dataset.double || 0)
                    };
                    
                    // Don't auto-update price on load, only on change
                    // But here we are changing.
                    this.updateHarga();
                } else {
                    this.selectedPaketDetails = null;
                }
            },
            
            updateHarga() {
                // Should we force update price if user changes room type or packet? 
                // Yes, but in edit mode, maybe they negotiated a custom price?
                // For now, let's behave like create: update default price. They can override if field was editable (it is read-only in create).
                // In create view: <input type="text" :value="formatRupiah(form.harga_paket)" readonly ...>
                // So it's determined by package/room.
                
                if(this.form.tipe_kamar && this.selectedPaketDetails) {
                    this.form.harga_paket = this.selectedPaketDetails[this.form.tipe_kamar] || 0;
                }
            },
            
            // On init, we might want to populate selectedPaketDetails based on current ID,
            // but finding the option element programmatically in Alpine is tricky without ref.
            // A simple hack: loop options on mount? 
            // Better: just let the saved harga_paket stand unless they change selection.
            // But we need selectedPaketDetails populated so if they change Type Kamar, it updates.
            init() {
                 this.$nextTick(() => {
                    const select = document.querySelector('select[x-model="form.keberangkatan_haji_id"]');
                    if(select && select.value) {
                        const option = select.options[select.selectedIndex];
                         this.selectedPaketDetails = {
                            quad: parseInt(option.dataset.quad || 0),
                            triple: parseInt(option.dataset.triple || 0),
                            double: parseInt(option.dataset.double || 0)
                        };
                    }
                 });
            },

            get totalTagihan() {
                return (this.form.harga_paket * this.form.jumlah_jamaah) - this.form.diskon;
            },
            
            get sisaTagihan() {
                return this.totalTagihan - this.form.total_bayar;
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            },

            handleFile(e, key) {
                this.files[key] = e.target.files[0];
            },

            submitForm() {
                // Pre-submission validation
                if (!this.form.total_bayar || this.form.total_bayar < 1) {
                    alert('Gagal: Pembayaran DP / Total Bayar harus diisi dan minimal Rp 1.');
                    return;
                }

                const formData = new FormData();
                
                // Append text data
                for (const key in this.form) {
                    formData.append(key, this.form[key]);
                }
                
                // Append files
                for (const key in this.files) {
                    if (this.files[key]) formData.append(key, this.files[key]);
                }
                
                // Method spoofing for PUT
                formData.append('_method', 'PUT');

                fetch('{{ route('pendaftaran-haji.update', $pendaftaran->id) }}', {
                    method: 'POST', // POST with _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
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
