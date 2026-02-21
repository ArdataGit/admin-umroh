@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Transaksi Tiket" :breadcrumbs="[
    ['label' => 'Transaksi Tiket', 'url' => route('transaksi-tiket.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b pb-4 border-gray-100 dark:border-gray-700">
        <div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Invoice #{{ $transaksi->kode_transaksi }}</h3>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="text-right">
             <span class="@if($transaksi->status_transaksi == 'process') bg-blue-100 text-blue-800 @elseif($transaksi->status_transaksi == 'cancelled') bg-red-100 text-red-800 @else bg-green-100 text-green-800 @endif px-3 py-1 rounded-full text-sm font-semibold uppercase">
                {{ $transaksi->status_transaksi }}
            </span>
        </div>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-8">
        <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Pelanggan Info</h4>
            <div class="text-gray-800 dark:text-white">
                <p class="font-bold text-lg">{{ $transaksi->pelanggan->nama_pelanggan }}</p>
                <p>{{ $transaksi->pelanggan->kontak_pelanggan }}</p>
                <p>{{ $transaksi->pelanggan->alamat_pelanggan }}</p>
            </div>
        </div>
         <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Transaksi Info</h4>
            <div class="text-gray-800 dark:text-white">
                @if($transaksi->alamat_transaksi)
                <p class="mt-1"><span class="font-medium">Alamat Transaksi:</span> {{ $transaksi->alamat_transaksi }}</p>
                @endif
                @if($transaksi->catatan)
                <p class="mt-2 text-sm text-gray-500"><span class="font-medium">Catatan:</span> {{ $transaksi->catatan }}</p>
                @endif
                @if($transaksi->bukti_transaksi)
                <div class="mt-4">
                    <h5 class="text-xs font-semibold uppercase text-gray-400 mb-2">Bukti Transaksi</h5>
                    <a href="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" target="_blank">
                        <img src="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" alt="Bukti Transaksi" class="h-48 rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 hover:opacity-90 transition-opacity">
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="overflow-x-auto mb-8 border rounded-lg border-gray-200 dark:border-gray-700">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Tiket</th>
                    <th class="px-6 py-3 text-right">Harga Satuan</th>
                    <th class="px-6 py-3 text-center">Qty</th>
                    <th class="px-6 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $item)
                <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <p>{{ $item->ticket->nama_tiket }}</p>
                        <p class="text-xs text-gray-500">{{ $item->ticket->kode_tiket }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="flex justify-end">
        <div class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-800 rounded-lg p-6 space-y-3">
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Total Items</span>
                <span class="font-semibold">Rp {{ number_format($transaksi->details->sum('total_harga'), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Tax ({{ $transaksi->tax_percentage }}%)</span>
                <span class="text-red-500">+ Rp {{ number_format(($transaksi->details->sum('total_harga') * $transaksi->tax_percentage / 100), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Discount ({{ $transaksi->discount_percentage }}%)</span>
                <span class="text-green-500">- Rp {{ number_format(($transaksi->details->sum('total_harga') * $transaksi->discount_percentage / 100), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Biaya Tambahan</span>
                <span>Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-lg font-bold text-gray-800 dark:text-white">
                <span>Grand Total</span>
                <span>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('transaksi-tiket.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
        <a href="{{ route('transaksi-tiket.edit', $transaksi->id) }}" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Edit Data</a>
    </div>
</div>
@endsection
