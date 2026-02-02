<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            [
                'kode_hotel' => 'HTL-001',
                'nama_hotel' => 'Grand Makkah Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 345 6789',
                'email_hotel' => 'info@grandmakkah.com',
                'rating_hotel' => 5,
                'harga_hotel' => 2500000,
                'catatan_hotel' => 'Hotel bintang 5 dengan pemandangan Masjidil Haram. Fasilitas lengkap dan pelayanan terbaik.'
            ],
            [
                'kode_hotel' => 'HTL-002',
                'nama_hotel' => 'Madinah Palace Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 567 8901',
                'email_hotel' => 'contact@madinahpalace.com',
                'rating_hotel' => 4,
                'harga_hotel' => 2000000,
                'catatan_hotel' => 'Dekat dengan Masjid Nabawi, walking distance 5 menit.'
            ],
            [
                'kode_hotel' => 'HTL-003',
                'nama_hotel' => 'Al Haram View Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 234 5678',
                'email_hotel' => 'reservation@alharamview.com',
                'rating_hotel' => 5,
                'harga_hotel' => 3000000,
                'catatan_hotel' => 'Premium hotel dengan view langsung ke Kabah. Termasuk sarapan buffet.'
            ],
            [
                'kode_hotel' => 'HTL-004',
                'nama_hotel' => 'Nabawi Residence',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 678 9012',
                'email_hotel' => 'info@nabawiresidence.com',
                'rating_hotel' => 4,
                'harga_hotel' => 1800000,
                'catatan_hotel' => 'Hotel nyaman dengan harga terjangkau. Cocok untuk jamaah umroh.'
            ],
            [
                'kode_hotel' => 'HTL-005',
                'nama_hotel' => 'Safwa Tower Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 345 7890',
                'email_hotel' => 'booking@safwatower.com',
                'rating_hotel' => 5,
                'harga_hotel' => 2800000,
                'catatan_hotel' => 'Tower hotel modern dengan fasilitas gym dan restaurant halal.'
            ],
            [
                'kode_hotel' => 'HTL-006',
                'nama_hotel' => 'Jeddah Airport Hotel',
                'lokasi_hotel' => 'Jeddah',
                'kontak_hotel' => '+966 12 987 6543',
                'email_hotel' => 'info@jeddahairport.com',
                'rating_hotel' => 3,
                'harga_hotel' => 1200000,
                'catatan_hotel' => 'Hotel transit dekat bandara. Ideal untuk menginap sebelum penerbangan.'
            ],
            [
                'kode_hotel' => 'HTL-007',
                'nama_hotel' => 'Madinah Crown Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 456 7890',
                'email_hotel' => 'reservation@madinahcrown.com',
                'rating_hotel' => 5,
                'harga_hotel' => 2600000,
                'catatan_hotel' => 'Hotel mewah dengan kolam renang dan spa. Pemandangan kota Madinah.'
            ],
            [
                'kode_hotel' => 'HTL-008',
                'nama_hotel' => 'Transit Inn Jeddah',
                'lokasi_hotel' => 'Transit',
                'kontak_hotel' => '+966 12 111 2222',
                'email_hotel' => 'contact@transitinn.com',
                'rating_hotel' => 3,
                'harga_hotel' => 1000000,
                'catatan_hotel' => 'Hotel budget untuk transit. Kamar bersih dan nyaman.'
            ],
            [
                'kode_hotel' => 'HTL-009',
                'nama_hotel' => 'Makkah Hilton Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 555 6666',
                'email_hotel' => 'info@makkah-hilton.com',
                'rating_hotel' => 5,
                'harga_hotel' => 3500000,
                'catatan_hotel' => 'Hotel internasional chain dengan standar pelayanan kelas dunia.'
            ],
            [
                'kode_hotel' => 'HTL-010',
                'nama_hotel' => 'Al Madinah Suites',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 777 8888',
                'email_hotel' => 'booking@almadinahsuites.com',
                'rating_hotel' => 4,
                'harga_hotel' => 2200000,
                'catatan_hotel' => 'Suite hotel dengan dapur kecil. Cocok untuk keluarga.'
            ],
            [
                'kode_hotel' => 'HTL-011',
                'nama_hotel' => 'Dar Al Eiman Royal Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 222 3333',
                'email_hotel' => 'info@dareiman.com',
                'rating_hotel' => 5,
                'harga_hotel' => 3200000,
                'catatan_hotel' => 'Hotel mewah dekat Masjidil Haram. Shuttle gratis ke masjid.'
            ],
            [
                'kode_hotel' => 'HTL-012',
                'nama_hotel' => 'Madinah Oberoi Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 888 9999',
                'email_hotel' => 'reservation@oberoimadinah.com',
                'rating_hotel' => 5,
                'harga_hotel' => 2900000,
                'catatan_hotel' => 'Hotel premium dengan layanan concierge 24 jam.'
            ],
            [
                'kode_hotel' => 'HTL-013',
                'nama_hotel' => 'Jeddah Marriott Hotel',
                'lokasi_hotel' => 'Jeddah',
                'kontak_hotel' => '+966 12 333 4444',
                'email_hotel' => 'info@marriottjeddah.com',
                'rating_hotel' => 4,
                'harga_hotel' => 1500000,
                'catatan_hotel' => 'Hotel bisnis dengan meeting room dan business center.'
            ],
            [
                'kode_hotel' => 'HTL-014',
                'nama_hotel' => 'Makkah Clock Tower Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 444 5555',
                'email_hotel' => 'booking@clocktower.com',
                'rating_hotel' => 5,
                'harga_hotel' => 4000000,
                'catatan_hotel' => 'Hotel super premium di Abraj Al Bait. View terbaik ke Kabah.'
            ],
            [
                'kode_hotel' => 'HTL-015',
                'nama_hotel' => 'Al Aqeeq Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 999 0000',
                'email_hotel' => 'contact@alaqeeq.com',
                'rating_hotel' => 3,
                'harga_hotel' => 1500000,
                'catatan_hotel' => 'Hotel ekonomis dengan fasilitas standar. Dekat pusat kota.'
            ],
            [
                'kode_hotel' => 'HTL-016',
                'nama_hotel' => 'Transit Plaza Jeddah',
                'lokasi_hotel' => 'Transit',
                'kontak_hotel' => '+966 12 666 7777',
                'email_hotel' => 'info@transitplaza.com',
                'rating_hotel' => 3,
                'harga_hotel' => 900000,
                'catatan_hotel' => 'Hotel budget dekat terminal bus. Cocok untuk transit singkat.'
            ],
            [
                'kode_hotel' => 'HTL-017',
                'nama_hotel' => 'Swissotel Makkah',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 777 8888',
                'email_hotel' => 'reservation@swissotel-makkah.com',
                'rating_hotel' => 5,
                'harga_hotel' => 3300000,
                'catatan_hotel' => 'Hotel Swiss dengan standar internasional. Breakfast premium.'
            ],
            [
                'kode_hotel' => 'HTL-018',
                'nama_hotel' => 'Pullman Zamzam Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 888 9999',
                'email_hotel' => 'info@pullmanzamzam.com',
                'rating_hotel' => 5,
                'harga_hotel' => 3600000,
                'catatan_hotel' => 'Hotel Accor group dengan akses langsung ke Masjidil Haram.'
            ],
            [
                'kode_hotel' => 'HTL-019',
                'nama_hotel' => 'Madinah Movenpick Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 111 2222',
                'email_hotel' => 'booking@movenpick-madinah.com',
                'rating_hotel' => 4,
                'harga_hotel' => 2400000,
                'catatan_hotel' => 'Hotel modern dengan rooftop restaurant. View kota Madinah.'
            ],
            [
                'kode_hotel' => 'HTL-020',
                'nama_hotel' => 'Jeddah Radisson Blu',
                'lokasi_hotel' => 'Jeddah',
                'kontak_hotel' => '+966 12 999 0000',
                'email_hotel' => 'info@radissonblu-jeddah.com',
                'rating_hotel' => 4,
                'harga_hotel' => 1600000,
                'catatan_hotel' => 'Hotel tepi laut dengan private beach access.'
            ],
            [
                'kode_hotel' => 'HTL-021',
                'nama_hotel' => 'Elaf Ajyad Hotel',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 123 4567',
                'email_hotel' => 'reservation@elafajyad.com',
                'rating_hotel' => 4,
                'harga_hotel' => 2300000,
                'catatan_hotel' => 'Hotel dengan lokasi strategis. 3 menit jalan kaki ke Masjidil Haram.'
            ],
            [
                'kode_hotel' => 'HTL-022',
                'nama_hotel' => 'Shaza Al Madina Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 234 5678',
                'email_hotel' => 'info@shazamadinah.com',
                'rating_hotel' => 5,
                'harga_hotel' => 2700000,
                'catatan_hotel' => 'Hotel boutique dengan desain Arabia klasik. Pelayanan personal.'
            ],
            [
                'kode_hotel' => 'HTL-023',
                'nama_hotel' => 'Jeddah Hilton',
                'lokasi_hotel' => 'Jeddah',
                'kontak_hotel' => '+966 12 345 6789',
                'email_hotel' => 'booking@hiltonjeddah.com',
                'rating_hotel' => 5,
                'harga_hotel' => 1800000,
                'catatan_hotel' => 'Hotel chain internasional dengan fasilitas lengkap dan kolam renang.'
            ],
            [
                'kode_hotel' => 'HTL-024',
                'nama_hotel' => 'Anjum Hotel Makkah',
                'lokasi_hotel' => 'Mekkah',
                'kontak_hotel' => '+966 12 456 7890',
                'email_hotel' => 'contact@anjummakkah.com',
                'rating_hotel' => 4,
                'harga_hotel' => 2100000,
                'catatan_hotel' => 'Hotel keluarga dengan family room. Dekat dengan shopping mall.'
            ],
            [
                'kode_hotel' => 'HTL-025',
                'nama_hotel' => 'Taiba Madinah Hotel',
                'lokasi_hotel' => 'Madinah',
                'kontak_hotel' => '+966 14 567 8901',
                'email_hotel' => 'info@taibamadinah.com',
                'rating_hotel' => 3,
                'harga_hotel' => 1700000,
                'catatan_hotel' => 'Hotel sederhana dengan harga terjangkau. Fasilitas dasar lengkap.'
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}
