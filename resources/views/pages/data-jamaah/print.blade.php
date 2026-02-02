<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        td.center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
        @media print { @page { size: landscape; margin: 10mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DATA JAMAAH</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 8%">Kode</th>
                <th style="width: 10%">NIK</th>
                <th style="width: 15%">Nama</th>
                <th style="width: 5%">L/P</th>
                <th style="width: 10%">Kontak</th>
                <th style="width: 12%">Kota/Kab</th>
                <th style="width: 15%">Paspor</th>
                <th style="width: 20%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jamaahs as $index => $jamaah)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $jamaah->kode_jamaah }}</td>
                    <td>{{ $jamaah->nik_jamaah }}</td>
                    <td>{{ $jamaah->nama_jamaah }}</td>
                    <td class="center">{{ $jamaah->jenis_kelamin }}</td>
                    <td>{{ $jamaah->kontak_jamaah }}</td>
                    <td>{{ $jamaah->kabupaten_kota }}</td>
                    <td>{{ $jamaah->nomor_paspor ?? '-' }}</td>
                    <td>{{ $jamaah->alamat_jamaah }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="center">Tidak ada data jamaah</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer"><p>Dicetak oleh Administrator</p></div>
</body>
</html>
