<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        td {
            vertical-align: top;
            padding: 3px;
        }
        .label {
            width: 150px;
        }
        .separator {
            width: 10px;
            text-align: center;
        }
        .signature {
            margin-top: 30px;
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header Kop Surat (Optional, sesuaikan dengan kebutuhan) -->
        <div class="header">
            <h1>PT. PERWAKAB BATAM</h1>
            <p>Jalan Contoh No. 123, Batam, Kepulauan Riau</p>
            <p>Telp: (0778) 123456 | Email: info@perwakab.com</p>
        </div>

        <div class="content">
            <div class="title">SURAT REKOMENDASI</div>
            <div class="subtitle">Nomor: {{ $surat->nomor_dokumen }}</div>

            <p>Yang bertanda tangan di bawah ini:</p>
            <table class="data-table">
                <tr>
                    <td class="label">Nama</td>
                    <td class="separator">:</td>
                    <td><strong>H. CONTOH NAMA</strong></td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="separator">:</td>
                    <td>Direktur Utama</td>
                </tr>
            </table>

            <p>Dengan ini memberikan rekomendasi kepada:</p>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->nama_jamaah }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->jamaah->tanggal_lahir)->format('d F Y') }}</td>
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
                    <td class="label">No. Telepon / HP</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->kontak_jamaah }}</td>
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

            <p style="text-align: justify;">
                Adalah benar calon jamaah Umroh yang terdaftar di <strong>PT. PERWAKAB BATAM</strong> yang akan berangkat pada tanggal <strong>{{ \Carbon\Carbon::parse($surat->keberangkatanUmroh->tanggal_keberangkatan)->format('d F Y') }}</strong>.
            </p>

            <p style="text-align: justify;">
                Surat rekomendasi ini dibuat untuk keperluan pengurusan paspor di <strong>Kantor Imigrasi {{ $surat->kantor_imigrasi }}</strong>. Mohon kiranya dapat dibantu dan diberikan kemudahan dalam pengurusan tersebut.
            </p>
            
            @if($surat->catatan)
            <p><strong>Catatan:</strong> {{ $surat->catatan }}</p>
            @endif

            <p>Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

            <div class="signature">
                <p>Batam, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                <p>Hormat Kami,</p>
                <div class="signature-name">H. CONTOH NAMA</div>
                <p>Direktur Utama</p>
            </div>
        </div>
    </div>

</body>
</html>
