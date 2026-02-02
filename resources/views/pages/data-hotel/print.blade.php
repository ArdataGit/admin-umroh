<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold uppercase tracking-wider text-gray-800">Laporan Data Hotel</h1>
        <p class="text-sm text-gray-500">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-center">No</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Kode Hotel</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nama Hotel</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Lokasi</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Kontak</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Rating</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotels as $index => $hotel)
                <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-2 font-medium">{{ $hotel->kode_hotel }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $hotel->nama_hotel }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $hotel->lokasi_hotel }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $hotel->kontak_hotel }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $hotel->rating_hotel }} ★</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">Rp {{ number_format($hotel->harga_hotel, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
