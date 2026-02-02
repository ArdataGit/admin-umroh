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
            <div class="title">SURAT KETERANGAN IBADAH UMROH</div>
            <div class="subtitle">Nomor: {{ $surat->nomor_dokumen }}</div>

            <p style="text-align: justify;">Yang bertanda tangan di bawah ini Direktur Utama <strong>PT. PERWAKAB BATAM</strong> menerangkan dengan sesungguhnya bahwa:</p>
            
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td><strong>{{ $surat->jamaah->nama_jamaah }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->jamaah->tanggal_lahir)->format('d F Y') }}</td>
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
                    <td class="label">Pekerjaan/Jabatan</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->jabatan_instansi }}</td>
                </tr>
                @if($surat->nik_instansi)
                <tr>
                    <td class="label">NIK / NIP</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->nik_instansi }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Instansi/Perusahaan</td>
                    <td class="separator">:</td>
                    <td>{{ $surat->kantor_instansi }}</td>
                </tr>
            </table>

            <p style="text-align: justify;">
                Adalah benar calon jamaah Umroh yang terdaftar di travel kami <strong>PT. PERWAKAB BATAM</strong> yang akan melaksanakan ibadah umroh dan berangkat pada tanggal <strong>{{ \Carbon\Carbon::parse($surat->keberangkatanUmroh->tanggal_keberangkatan)->format('d F Y') }}</strong>.
            </p>

            <p style="text-align: justify;">
                Sehubungan dengan hal tersebut, kami memohon kepada Bapak/Ibu Pimpinan <strong>{{ $surat->kantor_instansi }}</strong> agar dapat memberikan izin cuti kepada yang bersangkutan untuk melaksanakan ibadah umroh.
            </p>
            
            @if($surat->catatan)
            <p><strong>Catatan:</strong> {{ $surat->catatan }}</p>
            @endif

            <p>Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

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
