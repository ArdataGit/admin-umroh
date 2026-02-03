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
        .agent-info {
            margin-bottom: 20px;
            width: 100%;
        }
        .agent-info td {
            border: none;
            padding: 2px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
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
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
    </div>

    <table class="agent-info">
        <tr>
            <td width="15%"><strong>Nama Agent</strong></td>
            <td width="35%">: {{ $agent->nama_agent }}</td>
            <td width="15%"><strong>Total Bonus</strong></td>
            <td width="35%">: Rp {{ number_format($agent->total_bonus, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kode Agent</strong></td>
            <td>: {{ $agent->kode_agent }}</td>
            <td><strong>Sudah Dibayar</strong></td>
            <td>: Rp {{ number_format($agent->sudah_dibayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kontak</strong></td>
            <td>: {{ $agent->kontak_agent }}</td>
            <td><strong>Sisa Bonus</strong></td>
            <td>: Rp {{ number_format($agent->sisa_bonus, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal Pembayaran</th>
                <th>Kode Transaksi</th>
                <th>Jumlah Pembayaran</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Kode Referensi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agent->bonusPayouts as $index => $payout)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($payout->tanggal_bayar)->format('d M Y') }}</td>
                    <td class="text-center">{{ $payout->kode_transaksi }}</td>
                    <td class="text-right">Rp {{ number_format($payout->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $payout->metode_pembayaran }}</td>
                    <td class="text-center">{{ $payout->status_pembayaran }}</td>
                    <td>{{ $payout->kode_referensi_mutasi }}</td>
                    <td>{{ $payout->catatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>Admin</p>
    </div>
</body>
</html>
