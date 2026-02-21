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
            width: 45%;
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
            width: 100%;
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
            margin-top: 20px;
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
        <div class="header-title" style="margin-top: 0px;">INVOICE PEMBAYARAN HAJI</div>

    <div class="meta-info">
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td class="label">Tanggal Bayar:</td>
                            <td class="value">{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Invoice No:</td>
                            <td class="value">{{ $pembayaran->kode_transaksi }}</td>
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
                    <div class="section-title">DARI :</div>
                    <div class="company-name">TRANSMART GLOBAL WISATA</div>
                    <div>Alamat : Jl. Zafri Zam Zam No 40, Belitung, Kec. Banjarmasin Barat, Kota Banjarmasin, Kalimantan Selatan<br>
                    No telp : 0857-5172-9999<br>
                    Email: transglobalwisata@gmail.com</div>
                </td>
                <td>
                    <div class="section-title">KEPADA :</div>
                    <div class="company-name">{{ $customerHaji->jamaah->nama_jamaah }}</div>
                    <div>
                        Kode Jamaah: {{ $customerHaji->jamaah->kode_jamaah }}<br>
                        Alamat: {{ $customerHaji->jamaah->alamat_lengkap ?? '-' }}<br>
                        Telp: {{ $customerHaji->jamaah->nomor_hp ?? '-' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 50%;">Deskripsi Paket</th>
                <th>Keberangkatan</th>
                <th>Tipe Kamar</th>
                <th>Jumlah</th>
                <th style="width: 20%;">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $customerHaji->keberangkatanHaji->paketHaji->nama_paket }}</strong><br>
                    Kode: {{ $customerHaji->keberangkatanHaji->paketHaji->kode_paket }}
                </td>
                <td style="text-align: center;">
                    {{ \Carbon\Carbon::parse($customerHaji->keberangkatanHaji->tanggal_keberangkatan)->format('d/m/Y') }}<br>
                    <small>{{ $customerHaji->keberangkatanHaji->nama_keberangkatan }}</small>
                </td>
                <td style="text-align: center;" class="capitalize">{{ $customerHaji->tipe_kamar }}</td>
                <td style="text-align: center;">{{ $customerHaji->jumlah_jamaah }} Pax</td>
                <td style="text-align: right;">Rp {{ number_format($customerHaji->total_tagihan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="clearfix">
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Harga Paket</td>
                    <td class="value">Rp {{ number_format($customerHaji->harga_paket * $customerHaji->jumlah_jamaah, 0, ',', '.') }}</td>
                </tr>
                @if($customerHaji->diskon > 0)
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">(-) Rp {{ number_format($customerHaji->diskon, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label grand-total">Total Tagihan</td>
                    <td class="value grand-total">Rp {{ number_format($customerHaji->total_tagihan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label" style="color: #16a34a;">Pembayaran Saat Ini</td>
                    <td class="value" style="color: #16a34a; font-weight: bold;">Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="payment-status-section">
        <table class="status-table">
            <thead>
                <tr>
                    <th>Total Tagihan</th>
                    <th>Sudah Dibayar (Total)</th>
                    <th>Sisa Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rp {{ number_format($customerHaji->total_tagihan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($total_bayar, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sisa_pembayaran, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;">
        <p><strong>Keterangan Pembayaran:</strong></p>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 150px;">Metode Pembayaran</td>
                <td>: <span class="capitalize">{{ $pembayaran->metode_pembayaran }}</span></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: <span class="capitalize">{{ $pembayaran->status_pembayaran }}</span></td>
            </tr>
            @if($pembayaran->kode_referensi)
            <tr>
                <td>Kode Referensi</td>
                <td>: {{ $pembayaran->kode_referensi }}</td>
            </tr>
            @endif
            @if($pembayaran->catatan)
            <tr>
                <td>Catatan</td>
                <td>: {{ $pembayaran->catatan }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer clearfix">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 60%; vertical-align: top; padding: 0;">
                    <div style="font-size: 7pt; color: #666;">
                        <p><strong>Catatan:</strong></p>
                        <ul style="margin: 0; padding-left: 15px;">
                            <li>Simpan invoice ini sebagai bukti pembayaran yang sah.</li>
                            <li>Pembayaran dianggap sah apabila dana telah masuk ke rekening kami.</li>
                            <li>Pelunasan dilakukan sesuai dengan ketentuan yang berlaku.</li>
                        </ul>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top; padding: 0;">
                    <div class="signature-area" style="position: relative; float: right; font-size: 8pt;">
                        <div class="signature-date">Banjarmasin, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                        <div>TRANSMART GLOBAL WISATA</div>
                        <div style="position: relative; height: 80px;">
                            <img src="{{ public_path('images/surat/stempel.png') }}" style="position: absolute; left: 50%; transform: translateX(-50%); width: 100px; opacity: 0.8; top: 0px;">
                        </div>
                        <div class="signature-name" style="position: relative; z-index: 1;">SARIDI, MM</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    </div>

</body>
</html>
