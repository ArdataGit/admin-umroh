@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Keberangkatan Haji" :breadcrumbs="[
    ['label' => 'Keberangkatan Haji', 'url' => route('keberangkatan-haji.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="keberangkatanForm()">
        <form @submit.prevent="submitForm">
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Jadwal Keberangkatan Haji</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <!-- Kode (Auto) -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Keberangkatan</label>
                    <input type="text" x-model="form.kode_keberangkatan" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>

                <!-- Paket Selection -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Paket Haji</label>
                    <select x-model="form.paket_haji_id" @change="onPaketChange" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($paketHajis as $paket)
                            <option value="{{ $paket->id }}">{{ $paket->nama_paket }} ({{ $paket->kode_paket }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Read-only Fields populated from Paket -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Keberangkatan</label>
                    <input type="text" x-model="form.nama_keberangkatan" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Keberangkatan</label>
                    <input type="date" x-model="form.tanggal_keberangkatan" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Hari</label>
                    <input type="number" x-model="form.jumlah_hari" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kuota Jamaah (Pax)</label>
                    <input type="number" x-model="form.kuota_jamaah" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                
                <!-- Manual Fields -->
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Keberangkatan</label>
                    <select x-model="form.status_keberangkatan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan</label>
                    <textarea x-model="form.catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('keberangkatan-haji.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    function keberangkatanForm() {
        return {
            pakets: @json($paketHajis),
            form: {
                kode_keberangkatan: '{{ $kodeKeberangkatan }}',
                paket_haji_id: '',
                nama_keberangkatan: '',
                tanggal_keberangkatan: '',
                jumlah_hari: '',
                kuota_jamaah: '',
                status_keberangkatan: 'active',
                catatan: ''
            },
            onPaketChange() {
                const selected = this.pakets.find(p => p.id == this.form.paket_haji_id);
                if (selected) {
                    this.form.nama_keberangkatan = selected.nama_paket;
                    this.form.tanggal_keberangkatan = selected.tanggal_keberangkatan;
                    this.form.jumlah_hari = selected.jumlah_hari;
                    this.form.kuota_jamaah = selected.kuota_jamaah;
                } else {
                    this.form.nama_keberangkatan = '';
                    this.form.tanggal_keberangkatan = '';
                    this.form.jumlah_hari = '';
                    this.form.kuota_jamaah = '';
                }
            },
            submitForm() {
                fetch('{{ route('keberangkatan-haji.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
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
