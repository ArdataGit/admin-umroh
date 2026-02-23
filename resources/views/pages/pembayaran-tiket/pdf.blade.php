<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .content {
            padding: 0 1cm 0.5cm 1cm;
        }
        .header-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .meta-info {
            margin-bottom: 15px;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            vertical-align: top;
        }
        .meta-info .label {
            width: 120px;
            font-weight: normal;
        }
        .meta-info .value {
            font-weight: bold;
        }
        
        .address-section {
            width: 100%;
            margin-bottom: 10px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .address-table {
            width: 100%;
            border-collapse: collapse;
        }
        .address-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .section-title {
            font-size: 8pt;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .company-name {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 2px;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .main-table th {
            background-color: #fcfcfc;
            border: 1px solid #e0e0e0;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 8.5pt;
        }
        .main-table td {
            border: 1px solid #e0e0e0;
            padding: 8px 5px;
            vertical-align: top;
        }
        
        .summary-section {
            float: right;
            width: 40%;
            margin-bottom: 5px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 5px;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-table .label {
            text-align: left;
            font-weight: bold;
        }
        .summary-table .value {
            text-align: right;
        }
        .summary-table .grand-total {
            font-size: 10pt;
            font-weight: bold;
        }
        
        .payment-status-section {
            width: 60%;
            float: right;
            margin-top: 10px;
            clear: both;
        }
        .status-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .status-table th {
            background-color: #fcfcfc;
            border: 1px solid #e0e0e0;
            padding: 6px;
            font-size: 8pt;
        }
        .status-table td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .footer {
            margin-top: 0px;
            width: 100%;
            clear: both;
        }
        .signature-area {
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-date {
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 180px;
            margin: 0 auto;
        }
        .signature-name {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .capitalize { text-transform: capitalize; }
    </style>
</head>
<body>

    <div style="width: 100%; margin: 0; padding: 0;">
        <img src="{{ public_path('images/surat/header.png') }}" style="width: 100%; display: block;">
    </div>

    <div class="content">
        <div class="header-title" style="margin-top: 0px;">INVOICE TRANSAKSI TIKET</div>

    <div class="meta-info">
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td class="label">Tanggal Transaksi:</td>
                            <td class="value">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Kode Transaksi:</td>
                            <td class="value">{{ $transaksi->kode_transaksi }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="address-section">
        <table class="address-table">
            <tr>
                <td>
                    <div class="section-title">DATA TRAVEL :</div>
                    <div class="company-name">TRANSMART GLOBAL WISATA</div>
                    <div>Alamat : Jl. Zafri Zam Zam No 40, Belitung, Kec. Banjarmasin Barat, Kota Banjarmasin, Kalimantan Selatan<br>
                    No telp : 0857-5172-9999<br>
                    Email: transglobalwisata@gmail.com</div>
                </td>
                <td>
                    <div class="section-title">DATA MITRA :</div>
                    <div class="company-name">{{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</div>
                    <div>
                        {{ $transaksi->pelanggan->alamat_pelanggan ?? '-' }}<br>
                        Telp: {{ $transaksi->pelanggan->kontak_pelanggan ?? '-' }}<br>
                        Email: {{ $transaksi->pelanggan->email_pelanggan ?? '-' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 35%;">Nama</th>
                <th>Type</th>
                <th style="width: 8%;">Kode PNR</th>
                <th style="width: 15%;">Rute</th>
                <th style="width: 15%;">Harga</th>
                <th>Jumlah (Pax)</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $detail)
            <tr>
                <td>
                    <strong>{{ $detail->ticket->nama_tiket }}</strong>
                    @if($detail->ticket->tanggal_keberangkatan && $detail->ticket->tanggal_kepulangan)
                        <br>
                        <small>({{ \Carbon\Carbon::parse($detail->ticket->tanggal_keberangkatan)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($detail->ticket->tanggal_kepulangan)->format('d/m/Y') }})</small>
                    @endif
                    <br>
                    {{ $detail->ticket->kode_tiket }}<br>
                    @if($detail->ticket->kode_tiket_1) {{ $detail->ticket->kode_tiket_1 }}<br> @endif
                    @if($detail->ticket->kode_tiket_2) {{ $detail->ticket->kode_tiket_2 }}<br> @endif
                    @if($detail->ticket->kode_tiket_3) {{ $detail->ticket->kode_tiket_3 }}<br> @endif
                    @if($detail->ticket->kode_tiket_4) {{ $detail->ticket->kode_tiket_4 }}<br> @endif
                    {!! nl2br(e($detail->ticket->catatan_tiket)) !!}
                </td>
                <td style="text-align: center;">{{ $detail->ticket->jenis_tiket }}</td>
                <td style="text-align: center;">{{ $detail->ticket->kode_pnr }}</td>
                <td>{{ $detail->ticket->rute_tiket }}</td>
                <td style="text-align: right;">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $detail->quantity }} Pax</td>
                <td style="text-align: right;">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Tax ({{ (float)$transaksi->tax_percentage }}%)</td>
                    <td class="value">(+) Rp {{ number_format($transaksi->total_transaksi * ($transaksi->tax_percentage / 100), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Discount ({{ (float)$transaksi->discount_percentage }}%)</td>
                    <td class="value">(-) Rp {{ number_format($transaksi->total_transaksi * ($transaksi->discount_percentage / 100), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label grand-total">Grand Total</td>
                    <td class="value grand-total">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="clearfix">
        <div class="payment-status-section">
            <table class="status-table">
                <thead>
                    <tr>
                        <th>Total Transaksi</th>
                        <th>Sudah Pembayaran</th>
                        <th>Sisa Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($total_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($sisa_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer clearfix">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 60%; vertical-align: top; padding: 0;">
                    <div style="font-size: 6.5pt;">
                        <div class="section-title" style="margin-bottom: 5px; color: #1a1a1a;">Kondisi dan Ketentuan</div>
                        <ol style="margin: 0; padding-left: 15px; list-style-type: decimal;">
                            <li>Harga dapat berubah sewaktu-waktu mengikuti regulasi maskapai penerbangan</li>
                            <li>Deposit sebesar minimal IDR 2.000.000/pax</li>
                            <li>Time limit pembayaran deposit paling lambat 1 x 24jam setelah LOBC diterima</li>
                            <li>pembayaran Down payment yang telah dilakukan tidak dapat dikembalikan atau dialihkan</li>
                            <li>Materialisasi sesuai kebijakan maskapai masing-masing maksimal dilakukan 31 (tiga puluh satu) hari sebelum keberangkatan</li>
                            <li>Pelunasan harus dilakukan paling lambat 30 (tiga puluh) hari sebelum tanggal keberangkatan</li>
                            <li>Apabila agen tidak melakukan pelunasan pada waktu yang sudah ditentukan, maka seat akan dibatalkan dan deposit akan hangus</li>
                            <li>Pelunasan tiket tidak bisa ditarik kembali atau dibatalkan meskipun belum proses issued</li>
                            <li>Issued tiket dilakukan selambat-lambatnya 8 (delapan) hari kerja sebelum tanggal keberangkatan</li>
                            <li>TRANSMART GLOBAL WISATA dibebaskan dari segala tuntutan atas pembatalan reservasi atau pembukuan yang disebabkan gagal Visa</li>
                            <li>Ketentuan perbaikan nama (core name), change nama, reschedule, rebook dan refund mengikuti regulasi dari masing-masing Maskapai</li>
                            <li>Ketentuan Bagasi, Tas Cabin, Air Zam-Zam mengikuti regulasi dari Maskapai</li>
                            <li>Seluruh Pembayaran Desposit dan Pelunasan ke : Bank Mandiri (IDR) 0310089798899 a.n TRANSMART GLOBAL WISATA</li>
                        </ol>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top; padding: 0;">
                    <div class="signature-area" style="position: relative; float: right; font-size: 8pt;">
                        <div class="signature-date">Banjarmasin, {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</div>
                        <div>TRANSMART GLOBAL WISATA</div>
                        <div style="position: relative; height: 80px;">
                            <img src="{{ public_path('images/surat/stempel.png') }}" style="position: absolute; left: 50%; transform: translateX(-50%); width: 100px; opacity: 0.8; top: 0px;">
                        </div>
                        <div class="signature-name" style="position: relative; z-index: 1;">ANJELIA RAHMAH</div>
                        <br>
                        <div class="signature-name" style="position: relative; z-index: 1;">ADMINISTRATOR</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    </div>

</body>
</html>
