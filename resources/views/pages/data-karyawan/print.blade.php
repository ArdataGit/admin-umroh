<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
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
            font-size: 16px;
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
                <th>Kode</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>Kota</th>
                <th>L/P</th>
                <th>TTL</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $index => $karyawan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $karyawan->kode_karyawan }}</td>
                <td>{{ $karyawan->nik_karyawan }}</td>
                <td>{{ $karyawan->nama_karyawan }}</td>
                <td>{{ $karyawan->kontak_karyawan }}</td>
                <td>{{ $karyawan->email_karyawan }}</td>
                <td>{{ $karyawan->kabupaten_kota }}</td>
                <td>{{ $karyawan->jenis_kelamin }}</td>
                <td>{{ $karyawan->tempat_lahir }}, {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') }}</td>
                <td>{{ $karyawan->alamat_karyawan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
