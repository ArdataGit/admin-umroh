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
                            <td class="bold">Permohonan Izin Cuti</td>
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
            <p style="margin: 0;" class="bold">Kepala Perusahaan {{ $surat->kantor_instansi }}</p>
            <p style="margin: 0;">Di</p>
            <p style="margin: 0; padding-left: 20px;">Tempat</p>
        </div>

        <!-- Body -->
        <div class="text-justify">
            <p>Assalamualaikum wr. wb.,</p>

            <p style="margin-bottom: 10px;">
                Segala puji dan syukur kita panjatkan kehadirat Allah subhanahu wata'ala, atas rahmat dan karunia Nya untuk kita yang begitu banyak. Bersama dengan surat ini, kami bermaksud menyampaikan surat pengantar permohonan izin cuti untuk jamaah umroh perusahaan travel kami atas nama :
            </p>

            <table style="margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td class="label">NIK</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->nik_instansi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Nama</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->nama_jamaah }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jabatan_instansi }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->alamat_jamaah }}</td>
                </tr>
                <tr>
                    <td class="label">Keberangkatan</td>
                    <td class="separator">:</td>
                    <td>
                        {{ \Carbon\Carbon::parse($surat->keberangkatanUmroh->tanggal_keberangkatan)->translatedFormat('d F Y') }} s/d 
                        {{ \Carbon\Carbon::parse($surat->keberangkatanUmroh->tanggal_keberangkatan)->addDays($surat->keberangkatanUmroh->jumlah_hari)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            </table>

            <p style="margin-bottom: 10px;">
                Dengan ini kami menyatakan bahwa nama tersebut di atas adalah benar calon jamaah umroh travel kami dari perusahaan travel kami, yang terdaftar secara resmi yaitu :
            </p>

            <table style="margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td class="label">Travel</td>
                    <td class="separator">:</td>
                    <td>TRANSMART GLOBAL WISATA</td>
                </tr>
                <tr>
                    <td class="label">Kontak</td>
                    <td class="separator">:</td>
                    <td>0857-5172-9999</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td>Jl. Zafri Zam Zam No.40, Teluk Dalam, Banjarmasin Barat, Banjarmasin, Kalimantan Selatan</td>
                </tr>
            </table>

            <p>
                Untuk itu kami mohon agar nama tersebut diatas bisa dibantu dalam pemberian izin cuti umroh. Demikian surat permohonan ini kami buat dengan sebenar-benar nya dan dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <p style="margin-bottom: 2px;">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="bold" style="margin: 0;">TRANSMART GLOBAL WISATA</p>
            
            @if(file_exists(public_path('images/surat/stempel.png')))
                <img src="{{ public_path('images/surat/stempel.png') }}" class="signature-image">
            @else
                <div style="height: 60px;"></div>
            @endif

            <p class="bold" style="margin: 0;">SARIDI, MM</p>
            <!-- <p style="margin: 0;">Administrator</p> -->
        </div>

    </div>

    <div class="footer">
        Travel Management System ® {{ date('Y') }} || <strong>TRANSMART GLOBAL WISATA</strong> © Hajj & Umroh Service
    </div>

</body>
</html>
