@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Manifest Jamaah" :breadcrumbs="[
    ['label' => 'Keberangkatan Umroh', 'url' => route('keberangkatan-umroh.index')],
    ['label' => 'Manifest', 'url' => '#']
]" />

<div class="space-y-6">
    <!-- Header Info & Summary -->
    <div class="max-w-5xl rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white">Informasi Keberangkatan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column: Departure Details & Package Prices -->
            <div class="space-y-6">
                <!-- Nama Keberangkatan -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Nama Keberangkatan</div>
                    <div class="font-semibold text-gray-800 dark:text-white">
                        {{ $keberangkatan->paketUmroh->kode_paket }} - {{ $keberangkatan->nama_keberangkatan }}
                    </div>
                </div>

                <!-- Tanggal Keberangkatan -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Tanggal Keberangkatan</div>
                    <div class="font-semibold text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($keberangkatan->tanggal_keberangkatan)->translatedFormat('d F Y') }} <span class="text-gray-400 mx-1">/</span> {{ $keberangkatan->jumlah_hari }} Hari
                    </div>
                </div>

                <!-- Maskapai -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Maskapai</div>
                    <div class="font-semibold text-gray-800 dark:text-white">
                        {{ $keberangkatan->paketUmroh->maskapai->nama_maskapai ?? '-' }} <span class="text-gray-400 mx-1">/</span> <span class="uppercase">{{ $keberangkatan->paketUmroh->rute_penerbangan ?? '-' }}</span>
                    </div>
                </div>

                <!-- Harga Paket -->
                <div>
                    <div class="text-sm text-gray-500 mb-2">Harga Paket</div>
                    <div class="space-y-2">
                        @if($keberangkatan->paketUmroh->harga_quad_1 > 0)
                        <div>
                            <div class="text-xs text-gray-500">Quad:</div>
                            <div class="font-medium text-gray-800 dark:text-white">Rp {{ number_format($keberangkatan->paketUmroh->harga_quad_1, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($keberangkatan->paketUmroh->harga_triple_1 > 0)
                        <div>
                            <div class="text-xs text-gray-500">Triple:</div>
                            <div class="font-medium text-gray-800 dark:text-white">Rp {{ number_format($keberangkatan->paketUmroh->harga_triple_1, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($keberangkatan->paketUmroh->harga_double_1 > 0)
                        <div>
                            <div class="text-xs text-gray-500">Double:</div>
                            <div class="font-medium text-gray-800 dark:text-white">Rp {{ number_format($keberangkatan->paketUmroh->harga_double_1, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Financial Summaries -->
            <div class="space-y-4 md:border-l md:pl-8 md:border-gray-200 md:dark:border-gray-700">
                <!-- Total Harga Paket -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Total Harga Paket</div>
                    <div class="font-bold text-lg text-gray-800 dark:text-white">
                        Rp {{ number_format($summary['total_harga_paket'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Total Diskon -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Total Diskon</div>
                    <div class="font-bold text-lg text-red-500">
                        Rp {{ number_format($summary['total_diskon'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Total Transaksi</div>
                    <div class="font-bold text-lg text-blue-600">
                        Rp {{ number_format($summary['total_transaksi'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Total Sudah Bayar -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Total Sudah Bayar</div>
                    <div class="font-bold text-lg text-green-600">
                        Rp {{ number_format($summary['total_bayar'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Total Sisa Pembayaran -->
                <div class="border-b border-gray-100 pb-3 dark:border-gray-700">
                    <div class="text-sm text-gray-500 mb-1">Total Sisa Pembayaran</div>
                    <div class="font-bold text-lg text-red-600">
                        Rp {{ number_format($summary['total_sisa'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Jumlah Jamaah -->
                <div>
                    <div class="text-sm text-gray-500 mb-1">Jumlah Jamaah</div>
                    <div class="font-bold text-lg text-gray-800 dark:text-white">
                        {{ $summary['jumlah_jamaah'] }} Pax
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Manifest List -->
    <div class="max-w-5xl rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Jamaah</h3>
            <a href="{{ route('customer-umroh.create', $keberangkatan->id) }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                + Tambah Jamaah
            </a>
        </div>
        
        <div class="overflow-x-auto">
             <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal Registrasi</th>
                        <th class="px-6 py-3">Kode Jamaah</th>
                        <th class="px-6 py-3">NIK Jamaah</th>
                        <th class="px-6 py-3">Nama Jamaah (KTP)</th>
                        <th class="px-6 py-3">Nama Jamaah (PASPOR)</th>
                        <th class="px-6 py-3">Jenis Kelamin</th>
                        <th class="px-6 py-3">Tanggal Lahir</th>
                        <th class="px-6 py-3">Umur</th>
                        <th class="px-6 py-3">Nomor Paspor</th>
                        <th class="px-6 py-3">Habis Paspor</th>
                        <th class="px-6 py-3">Tipe Kamar</th>
                        <th class="px-6 py-3 text-center">Jumlah Jamaah (Pax)</th>
                        <th class="px-6 py-3">Nama Keluarga</th>
                        <th class="px-6 py-3">Harga Paket</th>
                        <th class="px-6 py-3">Diskon Harga</th>
                        <th class="px-6 py-3">Total Harga</th>
                        <th class="px-6 py-3">Sudah Pembayaran</th>
                        <th class="px-6 py-3">Sisa Pembayaran</th>
                        <th class="px-6 py-3">Status Pembayaran</th>
                        <th class="px-6 py-3 text-center">Visa</th>
                        <th class="px-6 py-3 text-center">Ticket</th>
                        <th class="px-6 py-3 text-center">Perlengkapan</th>
                        <th class="px-6 py-3">Nama Agent</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerUmrohs as $index => $customer)
                    <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->kode_jamaah }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->nik_jamaah }}</td>
                        <td class="px-6 py-4 font-medium">{{ $customer->jamaah->nama_jamaah }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->nama_paspor ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->tanggal_lahir ? \Carbon\Carbon::parse($customer->jamaah->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->tanggal_lahir ? \Carbon\Carbon::parse($customer->jamaah->tanggal_lahir)->age . ' Thn' : '-' }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->nomor_paspor ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $customer->jamaah->tgl_paspor_expired ? \Carbon\Carbon::parse($customer->jamaah->tgl_paspor_expired)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 uppercase">{{ $customer->tipe_kamar }}</td>
                        <td class="px-6 py-4 text-center">{{ $customer->jumlah_jamaah }} Pax</td>
                        <td class="px-6 py-4">{{ $customer->nama_keluarga ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($customer->harga_paket, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($customer->diskon, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($customer->total_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-green-600">Rp {{ number_format($customer->total_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-red-600">Rp {{ number_format($customer->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                             @if($customer->sisa_tagihan <= 0)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold uppercase">Lunas</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold uppercase">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($customer->status_visa)
                                <span class="text-green-500 font-bold">✓</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                         <td class="px-6 py-4 text-center">
                            @if($customer->status_tiket)
                                <span class="text-green-500 font-bold">✓</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                         <td class="px-6 py-4 text-center">
                            @if($customer->status_perlengkapan)
                                <span class="text-green-500 font-bold">✓</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $customer->agent->nama_agent ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('customer-umroh.show', $customer->id) }}" class="text-gray-500 hover:text-gray-700" title="Lihat">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                <a href="{{ route('customer-umroh.edit', $customer->id) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <form action="{{ route('customer-umroh.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jamaah ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                        <td colspan="25" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data jamaah. Silakan tambahkan jamaah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
