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
        <h1>LAPORAN DATA PRODUK</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Kode Produk</th>
                <th style="width: 20%">Nama Produk</th>
                <th style="width: 10%">Std Stok</th>
                <th style="width: 10%">Akt Stok</th>
                <th style="width: 10%">Satuan</th>
                <th style="width: 15%">Harga Beli</th>
                <th style="width: 15%">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produks as $index => $produk)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $produk->kode_produk }}</td>
                    <td>{{ $produk->nama_produk }}</td>
                    <td class="center">{{ $produk->standar_stok }}</td>
                    <td class="center">{{ $produk->aktual_stok }}</td>
                    <td class="center">{{ $produk->satuan_unit }}</td>
                    <td class="right">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh Administrator</p>
    </div>
</body>
</html>
