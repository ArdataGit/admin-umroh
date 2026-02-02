<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <p style="font-size: 18px; font-weight: bold; margin: 0;">LAPORAN DATA SURAT REKOMENDASI</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>No. Dokumen</th>
                <th>Nama Jamaah</th>
                <th>Keberangkatan</th>
                <th>Kantor Imigrasi</th>
                <th>Nama Ayah</th>
                <th>Nama Kakek</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surat as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->nomor_dokumen }}</td>
                <td>{{ $item->jamaah->nama_jamaah ?? '-' }}</td>
                <td>{{ $item->keberangkatanUmroh->nama_keberangkatan ?? '-' }}</td>
                <td>{{ $item->kantor_imigrasi }}</td>
                <td>{{ $item->nama_ayah }}</td>
                <td>{{ $item->nama_kakek }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
