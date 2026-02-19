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
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .container {
            padding: 0.5cm 2cm;
        }
        .header-image {
            width: 100%;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        td {
            vertical-align: top;
            padding: 1px 0;
        }
        p {
            margin: 5px 0;
        }
        .label {
            width: 140px;
        }
        .separator {
            width: 15px;
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .signature-section {
            margin-top: 10px;
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-image {
            width: 70px;
            height: 70px;
            margin: 5px auto;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            margin: 0 2cm;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <img src="{{ public_path('images/surat/header.png') }}" class="header-image">

    <div class="container">
        
        <!-- Header Info -->
        <table style="margin-bottom: 10px;">
            <tr>
                <td style="width: 60%">
                    <table>
                        <tr>
                            <td style="width: 60px;">Nomor</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ $surat->nomor_dokumen }}</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td class="bold">Surat Rekomendasi Imigrasi</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: right; width: 40%">
                    Banjarmasin, {{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- Recipient -->
        <div style="margin-bottom: 10px;">
            <p style="margin: 0;">Kepada Yth,</p>
            <p style="margin: 0;" class="bold">Kepala Kantor Imigrasi {{ $surat->kantor_imigrasi }}</p>
            <p style="margin: 0;">Di</p>
            <p style="margin: 0; padding-left: 20px;">Tempat</p>
        </div>

        <!-- Body -->
        <div class="text-justify">
            <p>Assalamualaikum wr. wb.,</p>

            <p style="margin-bottom: 10px;">
                Yang bertanda tangan di bawah ini:
            </p>

            <table style="margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td class="label">Nama</td>
                    <td class="separator">:</td>
                    <td><strong>H. SARIDI, MM</strong></td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="separator">:</td>
                    <td>Direktur Utama</td>
                </tr>
                 <tr>
                    <td class="label">Perusahaan</td>
                    <td class="separator">:</td>
                    <td>PT. TRANSMART GLOBAL WISATA</td>
                </tr>
                 <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td>Jl. Zafri Zam Zam No.40, Teluk Dalam, Banjarmasin Barat, Banjarmasin, Kalimantan Selatan</td>
                </tr>
            </table>

            <p style="margin-bottom: 10px;">
                Dengan ini memberikan rekomendasi kepada calon jamaah umroh kami:
            </p>

            <table style="margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->nama_jamaah }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->jamaah->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->alamat_jamaah }}</td>
                </tr>
                 <tr>
                    <td class="label">Nama Ayah Kandung</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->nama_ayah }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Kakek Kandung</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->nama_kakek }}</td>
                </tr>
            </table>

            <p>
                Adalah benar calon jamaah Umroh yang terdaftar di <strong>PT. TRANSMART GLOBAL WISATA</strong> yang akan berangkat pada tanggal <strong>{{ \Carbon\Carbon::parse($surat->keberangkatanUmroh->tanggal_keberangkatan)->translatedFormat('d F Y') }}</strong>.
            </p>

            <p>
                Surat rekomendasi ini dibuat untuk keperluan pengurusan paspor di <strong>Kantor Imigrasi {{ $surat->kantor_imigrasi }}</strong>. Mohon kiranya dapat dibantu dan diberikan kemudahan dalam pengurusan tersebut.
            </p>
            
            @if($surat->catatan)
            <p><strong>Catatan:</strong> {{ $surat->catatan }}</p>
            @endif

            <p>Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <p style="margin-bottom: 2px;">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="bold" style="margin: 0;">PT. TRANSMART GLOBAL WISATA</p>
            
            @if(file_exists(public_path('images/surat/stempel.png')))
                <img src="{{ public_path('images/surat/stempel.png') }}" class="signature-image">
            @else
                <div style="height: 60px;"></div>
            @endif

            <p class="bold" style="margin: 0;">H. SARIDI, MM</p>
            <p style="margin: 0;">Direktur Utama</p>
        </div>

    </div>

    <div class="footer">
        Travel Management System ® {{ date('Y') }} || <strong>TRANSMART GLOBAL WISATA</strong> © Hajj & Umroh Service
    </div>

</body>
</html>
