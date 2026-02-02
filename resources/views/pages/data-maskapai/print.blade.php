<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Maskapai</th>
                <th>Nama Maskapai</th>
                <th>Rute</th>
                <th>Lama Perjalanan</th>
                <th>Harga Tiket</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maskapais as $index => $maskapai)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $maskapai->kode_maskapai }}</td>
                <td>{{ $maskapai->nama_maskapai }}</td>
                <td>{{ $maskapai->rute_penerbangan }}</td>
                <td>{{ $maskapai->lama_perjalanan }} Jam</td>
                <td>Rp {{ number_format($maskapai->harga_tiket, 0, ',', '.') }}</td>
                <td>{{ $maskapai->catatan_penerbangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
