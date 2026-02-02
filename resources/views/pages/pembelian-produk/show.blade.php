@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pembelian Produk" :breadcrumbs="[
    ['label' => 'Pembelian Produk', 'url' => route('pembelian-produk.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b pb-4 border-gray-100 dark:border-gray-700">
        <div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Invoice #{{ $pembelian->kode_pembelian }}</h3>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="text-right">
             <span class="@if($pembelian->status_pembayaran == 'order') bg-blue-100 text-blue-800 @elseif($pembelian->status_pembayaran == 'delivery') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif px-3 py-1 rounded-full text-sm font-semibold uppercase">
                {{ $pembelian->status_pembayaran }}
            </span>
        </div>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-8">
        <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Supplier Info</h4>
            <div class="text-gray-800 dark:text-white">
                <p class="font-bold text-lg">{{ $pembelian->supplier->nama_supplier }}</p>
                <p>{{ $pembelian->supplier->kontak_supplier }}</p>
                <p>{{ $pembelian->supplier->alamat_supplier }}</p>
            </div>
        </div>
         <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Payment Info</h4>
            <div class="text-gray-800 dark:text-white">
                <p><span class="font-medium">Metode:</span> <span class="uppercase">{{ $pembelian->metode_pembayaran }}</span></p>
                @if($pembelian->catatan)
                <p class="mt-2 text-sm text-gray-500"><span class="font-medium">Catatan:</span> {{ $pembelian->catatan }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="overflow-x-auto mb-8 border rounded-lg border-gray-200 dark:border-gray-700">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3 text-right">Harga Satuan</th>
                    <th class="px-6 py-3 text-center">Qty</th>
                    <th class="px-6 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian->details as $item)
                <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <p>{{ $item->produk->nama_produk }}</p>
                        <p class="text-xs text-gray-500">{{ $item->produk->kode_produk }}</p>
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
                <span class="font-semibold">Rp {{ number_format($pembelian->details->sum('total_harga'), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Tax ({{ $pembelian->tax_percentage }}%)</span>
                <span class="text-red-500">+ Rp {{ number_format(($pembelian->details->sum('total_harga') * $pembelian->tax_percentage / 100), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Discount ({{ $pembelian->discount_percentage }}%)</span>
                <span class="text-green-500">- Rp {{ number_format(($pembelian->details->sum('total_harga') * $pembelian->discount_percentage / 100), 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Shipping</span>
                <span>Rp {{ number_format($pembelian->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-lg font-bold text-gray-800 dark:text-white">
                <span>Grand Total</span>
                <span>Rp {{ number_format($pembelian->total_pembayaran, 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                <span>Jumlah Bayar</span>
                <span>Rp {{ number_format($pembelian->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
             <div class="flex justify-between text-sm font-bold mt-1">
                <span>{{ $pembelian->jumlah_bayar - $pembelian->total_pembayaran >= 0 ? 'Kembali' : 'Kekurangan' }}</span>
                <span class="{{ $pembelian->jumlah_bayar - $pembelian->total_pembayaran >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    Rp {{ number_format(abs($pembelian->jumlah_bayar - $pembelian->total_pembayaran), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('pembelian-produk.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
        <a href="{{ route('pembelian-produk.edit', $pembelian->id) }}" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Edit Data</a>
    </div>
</div>
@endsection
