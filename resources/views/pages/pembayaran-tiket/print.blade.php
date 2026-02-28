<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .content { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        td.center { text-align: center; }
        td.right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
        @media print { @page { size: landscape; margin: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div style="width: 100%; margin: 0; padding: 0;">
        <img src="{{ asset('images/surat/header.png') }}" style="width: 100%; display: block;">
    </div>
    <div class="content">
    <div class="header">
        <h1>LAPORAN PEMBAYARAN TIKET</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 15%">Kode Pembayaran</th>
                <th style="width: 15%">Trx. Tiket</th>
                <th style="width: 20%">Nama Mitra</th>
                <th style="width: 15%">Jumlah</th>
                <th style="width: 10%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('d/m/Y') }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->transaksi_tiket->kode_transaksi ?? '-' }}</td>
                    <td>{{ $item->transaksi_tiket->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}</td>
                    <td class="center capitalize">{{ $item->metode_pembayaran }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="center">Tidak ada data pembayaran tiket</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="5" class="right">TOTAL</td>
                <td class="right">Rp {{ number_format($pembayarans->sum('jumlah_pembayaran'), 0, ',', '.') }}</td>
                <td colspan="1"></td>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top: 10px; width: 100%; display: flex; justify-content: space-between;">
        <div style="width: 45%; margin-top: 10px;">
            @php $paidPayments = $pembayarans->where('status_pembayaran', 'paid'); @endphp
            @if($paidPayments->count() > 0)
                <div style="font-size: 10px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">Histori Pembayaran :</div>
                <table style="width: 100%; margin-top: 0;">
                    @foreach($paidPayments as $payment)
                        <tr>
                            <td style="font-weight: normal; font-size: 10px; border: none; padding: 2px 0;">{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('d/m/Y') }}</td>
                            <td class="right" style="font-size: 10px; border: none; padding: 2px 0;">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
        <div style="width: 250px; text-align: center; position: relative;">
            <div style="margin-bottom: 10px;">Banjarmasin, {{ date('d F Y') }}</div>
        <div style="position: relative; height: 80px;">
            <img src="{{ asset('images/surat/stempel.png') }}" style="position: absolute; left: 50%; transform: translateX(-50%); width: 100px; opacity: 0.8; top: -10px;">
        </div>
        <div style="font-weight: bold; text-transform: uppercase; position: relative; z-index: 1;">ANJELIA RAHMAH</div>
        <div style="font-weight: bold; text-transform: uppercase; position: relative; z-index: 1; margin-top: -3px;">ADMINISTRATOR</div>
    </div>
    <div style="clear: both; margin-top: 30px; text-align: right; font-size: 10px;">
        <p>Dicetak oleh Administrator</p>
    </div>
    </div>
</body>
</html>
