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
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        td.right {
            text-align: right;
        }
        td.center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DATA SUPPLIER</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Kode</th>
                <th style="width: 20%">Nama Supplier</th>
                <th style="width: 15%">Kontak</th>
                <th style="width: 15%">Email</th>
                <th style="width: 15%">Kota/Provinsi</th>
                <th style="width: 20%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $index => $supplier)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $supplier->kode_supplier }}</td>
                    <td>{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->kontak_supplier ?? '-' }}</td>
                    <td>{{ $supplier->email_supplier ?? '-' }}</td>
                    <td>{{ $supplier->kota_provinsi ?? '-' }}</td>
                    <td>{{ $supplier->alamat_supplier ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data supplier</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh Administrator</p>
    </div>
</body>
</html>
