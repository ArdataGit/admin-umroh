<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendaftaran Umroh</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 5px; font-size: 14px; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>LAPORAN PENDAFTARAN UMROH</h2>
    <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Registrasi</th>
                <th>Jamaah</th>
                <th>Paket</th>
                <th>Tipe Kamar</th>
                <th>Pax</th>
                <th>Agent</th>
                <th>Total Tagihan</th>
                <th>Sisa Tagihan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftarans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->jamaah->kode_jamaah }}</td>
                <td>
                    <b>{{ $item->jamaah->nama_jamaah }}</b><br>
                    NIK: {{ $item->jamaah->nik_jamaah }}
                </td>
                <td>{{ $item->keberangkatanUmroh->paketUmroh->nama_paket ?? '-' }}<br>({{ $item->keberangkatanUmroh->kode_keberangkatan }})</td>
                <td>{{ strtoupper($item->tipe_kamar) }}</td>
                <td>{{ $item->jumlah_jamaah }}</td>
                <td>{{ $item->agent->nama_agent ?? '-' }}</td>
                <td>Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                <td>
                    Visa: {{ $item->status_visa ? 'OK' : '-' }}<br>
                    Tiket: {{ $item->status_tiket ? 'OK' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ count($pendaftarans) }}</p>
    </div>
</body>
</html>
