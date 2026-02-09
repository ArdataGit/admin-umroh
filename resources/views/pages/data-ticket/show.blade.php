@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Ticket" :breadcrumbs="[
    ['label' => 'Data Ticket', 'url' => route('data-ticket')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <div>
             <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $ticket->nama_tiket }}</h2>
             <p class="text-sm text-gray-500">{{ $ticket->kode_tiket }}</p>
        </div>
       
        <div class="flex gap-2">
            <a href="{{ route('data-ticket') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Kembali</a>
            <a href="{{ route('data-ticket.edit', $ticket->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">Edit</a>
        </div>
    </div>

    @if($ticket->foto_tiket)
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('storage/' . $ticket->foto_tiket) }}" alt="{{ $ticket->nama_tiket }}" class="h-64 rounded-xl object-contain shadow-sm border border-gray-100 dark:border-gray-800" />
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Informasi Ticket</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Jenis Tiket</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->jenis_tiket }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Satuan Unit</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->satuan_unit }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase {{ $ticket->status_tiket == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $ticket->status_tiket }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Detail Penerbangan</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Maskapai (System)</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->maskapai->nama_maskapai }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Kode Maskapai (Manual)</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->kode_maskapai }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Rute</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->rute_tiket }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Kode PNR</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->kode_pnr }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Jumlah Tiket</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->jumlah_tiket }} Pax</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Tgl Keberangkatan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->tanggal_keberangkatan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Tgl Kepulangan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->tanggal_kepulangan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Jumlah Hari</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticket->jumlah_hari }} Hari</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
             <h4 class="mb-3 text-lg font-semibold text-blue-600">Harga</h4>
             <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Harga Modal:</span> 
                    <span class="font-semibold">
                        @if($ticket->kurs !== 'IDR')
                            {{ $ticket->kurs === 'MYR' ? 'RM' : $ticket->kurs }} {{ number_format($ticket->harga_modal_asing, 2) }} 
                            <span class="text-xs text-gray-500">(Rp {{ number_format($ticket->harga_modal, 0, ',', '.') }})</span>
                        @else
                            Rp {{ number_format($ticket->harga_modal, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Harga Jual:</span> 
                    <span class="font-semibold">
                        @if($ticket->kurs !== 'IDR')
                            {{ $ticket->kurs === 'MYR' ? 'RM' : $ticket->kurs }} {{ number_format($ticket->harga_jual_asing, 2) }} 
                            <span class="text-xs text-gray-500">(Rp {{ number_format($ticket->harga_jual, 0, ',', '.') }})</span>
                        @else
                            Rp {{ number_format($ticket->harga_jual, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
             </div>
        </div>

        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
             <h4 class="mb-3 text-lg font-semibold text-blue-600">Informasi Tambahan</h4>
             <div class="space-y-2 text-sm">
                @if($ticket->kode_tiket_1) <div class="flex justify-between"><span>Kode Tiket 1:</span> <span class="font-medium">{{ $ticket->kode_tiket_1 }}</span></div> @endif
                @if($ticket->kode_tiket_2) <div class="flex justify-between"><span>Kode Tiket 2:</span> <span class="font-medium">{{ $ticket->kode_tiket_2 }}</span></div> @endif
                @if($ticket->kode_tiket_3) <div class="flex justify-between"><span>Kode Tiket 3:</span> <span class="font-medium">{{ $ticket->kode_tiket_3 }}</span></div> @endif
                @if($ticket->kode_tiket_4) <div class="flex justify-between"><span>Kode Tiket 4:</span> <span class="font-medium">{{ $ticket->kode_tiket_4 }}</span></div> @endif
                @if($ticket->catatan_tiket) 
                    <div class="mt-2 text-gray-600 dark:text-gray-400 border-t border-gray-100 pt-2"><span class="font-semibold block">Catatan:</span> {{ $ticket->catatan_tiket }}</div>
                @endif
             </div>
        </div>
    </div>

</div>
@endsection
