<?php

namespace Database\Seeders;

use App\Models\PaketTrip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketTrip::create([
            'slug' => 'danau-alpin-kemilau',
            'nama' => 'Danau Alpin Kemilau',
            'deskripsi' => 'Rasakan keheningan malam di bawah jutaan bintang pada ketinggian 2.500 mdpl. Petualangan ini menawarkan pengalaman camping eksklusif dengan pemandangan danau yang tak terlupakan.',
            'fasilitas' => 'Tenda berkualitas, Sleeping bag, Perlengkapan camping, Guide profesional, Asuransi perjalanan, Snack & minuman',
            'lokasi' => 'Danau Tolire, Ternate, Maluku Utara',
            'kategori' => 'Camping & Hiking',
            'meeting_point' => 'Bandara Ternate, jam 06:00 pagi',
            'include' => 'Transportasi, Akomodasi kemah, Makan 3x, Pemandu wisata, Asuransi perjalanan',
            'exclude' => 'Perjalanan ke Ternate, Minuman beralkohol, Biaya pribadi tambahan, Foto profesional',
            'durasi_hari' => 3,
            'harga' => 4500000,
            'foto' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1511316695145-4992006ffddb?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'jalur-puncak-embun',
            'nama' => 'Jalur Puncak Embun',
            'deskripsi' => 'Taklukkan medan menantang melalui hutan tropis basah menuju samudera awan yang ikonik. Rute pendakian ini terkenal dengan keindahan sunrise dari puncak gunung.',
            'fasilitas' => 'Peralatan pendakian lengkap, Pemandu gunung, Tenda, Sleeping bag, Dapur lapangan, Medis kit',
            'lokasi' => 'Gunung Kerinci, Sumatera Barat',
            'kategori' => 'Pendakian',
            'meeting_point' => 'Pos Pendakian, Sungai Penuh, jam 09:00 pagi',
            'include' => 'Pemandu gunung bersertifikat, Transportasi, Akomodasi pondok, Makan 3x, Peralatan dasar',
            'exclude' => 'Perlengkapan pribadi, Konsumsi di luar jadwal, Perjalanan ke Sungai Penuh',
            'durasi_hari' => 4,
            'harga' => 1200000,
            'foto' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'sinar-hutan-emas-fotografi',
            'nama' => 'Sinar Hutan Emas - Fotografi',
            'deskripsi' => 'Memburu momen magis Ray of Light di kedalaman hutan purba bersama fotografer ahli. Workshop fotografi alam dengan teknik pencahayaan profesional.',
            'fasilitas' => 'Grup max 8 org, Fotografer profesional, Transportasi, Akomodasi, Meals',
            'lokasi' => 'Hutan Tanjung Puting, Kalimantan Tengah',
            'kategori' => 'Fotografi',
            'meeting_point' => 'Hotel Pangkalan, jam 07:00 pagi',
            'include' => 'Workshop fotografi, Transportasi, Akomodasi, Makan 3x, Pemandu foto',
            'exclude' => 'Peralatan fotografi pribadi, Asuransi, Perjalanan ke Pangkalan',
            'durasi_hari' => 3,
            'harga' => 950000,
            'foto' => 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'ekspedisi-kayak-rimba-kabut',
            'nama' => 'Ekspedisi Kayak Rimba Kabut',
            'deskripsi' => 'Menyusuri labirin sungai di kedalaman hutan saat kabut pagi menyelimuti pepohonan. Pengalaman meditasi dengan activitas kayaking yang menantang.',
            'fasilitas' => 'Kayak, Life jacket, Paddle, Shelter, Peralatan camping, Medis kit',
            'lokasi' => 'Sungai Sekonyer, Kalimantan Barat',
            'kategori' => 'Adventure',
            'meeting_point' => 'Dermaga Kubu Raya, jam 06:00 pagi',
            'include' => 'Kayak & peralatan, Pemandu lokal, Akomodasi rumah pohon, Makan 3x, Asuransi',
            'exclude' => 'Perjalanan ke Kubu Raya, Peralatan foto underwater, Aktivitas tambahan',
            'durasi_hari' => 4,
            'harga' => 3450000,
            'foto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1533604659319-0f1e9250adf5?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'puncak-pinus-emas',
            'nama' => 'Puncak Pinus Emas',
            'deskripsi' => 'Pendakian santai dengan pemandangan hutan pinus memukau. Cocok untuk keluarga pemula dengan rute yang tidak terlalu berat.',
            'fasilitas' => 'Pemandu wisata, Tenda, Akomodasi shelter, Makanan, Peralatan dasar',
            'lokasi' => 'Gunung Bromo, Jawa Timur',
            'kategori' => 'Hiking',
            'meeting_point' => 'Desa Cemoro Lawang, jam 03:00 pagi',
            'include' => 'Jeep basecamp, Pemandu lokal, Breakfast, Snack, Sunset & sunrise',
            'exclude' => 'Hotel Malang, Makan malam, Biaya masuk taman nasional',
            'durasi_hari' => 2,
            'harga' => 2200000,
            'foto' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1426604342519-3240eb86fa80?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1464207687429-7505649dae38?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'ray-of-light-expedition',
            'nama' => 'Ray of Light Expedition',
            'deskripsi' => 'Petualangan eksklusif mencari fenomena Rays di tengah hutan kuno dengan pengalaman spiritual.',
            'fasilitas' => 'Pemandu ahli alam, Perlengkapan outdoor, Eco-lodge, Makanan organik',
            'lokasi' => 'Hutan Belantara, Kalimantan Timur',
            'kategori' => 'Eksplorasi',
            'meeting_point' => 'Samarinda, transportasi 2 jam ke basecamp',
            'include' => 'Pemandu bilingual, Akomodasi ramah lingkungan, Makanan tradisional lokal',
            'exclude' => 'Pemandu fotografi profesional, Asuransi ekstra, Adventure activities',
            'durasi_hari' => 5,
            'harga' => 1800000,
            'foto' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'glacier-lake-camping',
            'nama' => 'Glacier Lake Camping',
            'deskripsi' => 'Berkemah di tepi danau dengan pemandangan pegunungan menakjubkan dan bintang langka.',
            'fasilitas' => 'Tenda premium, Sleeping bag -5C, Camping stove, Lampu LED, First aid kit',
            'lokasi' => 'Danau Pegunungan Dieng, Jawa Tengah',
            'kategori' => 'Camping',
            'meeting_point' => 'Wonosobo, perjalanan 1.5 jam ke lokasi',
            'include' => 'Transportasi, Kemah premium, Makan 3x, Pemandu nature, Stargazing',
            'exclude' => 'Perjalanan ke Wonosobo, Adventure activities, Dokumentasi profesional',
            'durasi_hari' => 3,
            'harga' => 4500000,
            'foto' => 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1511316695145-4992006ffddb?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);

        PaketTrip::create([
            'slug' => 'pantai-pink-snorkeling',
            'nama' => 'Pantai Pink Snorkeling',
            'deskripsi' => 'Jelajahi keindahan terumbu karang dan ikan eksotis di pantai pasir pink langka.',
            'fasilitas' => 'Peralatan snorkeling, Perahu tradisional, Life jacket, Instruktur bersertifikat',
            'lokasi' => 'Pantai Pink, Pulau Komodo, NTT',
            'kategori' => 'Pantai',
            'meeting_point' => 'Labuan Bajo, 20 menit ke dermaga',
            'include' => 'Perjalanan laut, Snorkeling 3 lokasi, Lunch seafood, Instruktur, Dokumentasi',
            'exclude' => 'Penerbangan Labuan Bajo, Hotel, Biaya taman nasional, Aktivitas lain',
            'durasi_hari' => 1,
            'harga' => 750000,
            'foto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
            'foto2' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
            'foto3' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'foto4' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'aktif' => true,
        ]);
    }
}
