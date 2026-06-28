<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramDonasi;
use App\Models\Donasi;
use App\Models\PembelianPangan;
use App\Models\StokPangan;
use App\Models\PenerimaBantuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin FoodCare',
            'email' => 'admin@foodcare.org',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Kantor Pusat FoodCare, Jl. Riau No. 101, Bandung',
        ]);

        $volunteer = User::create([
            'name' => 'Relawan Budi',
            'email' => 'volunteer@foodcare.org',
            'password' => Hash::make('password'),
            'role' => 'relawan',
            'phone' => '081234567891',
            'address' => 'Jl. Dago No. 12, Bandung',
        ]);

        $donatur = User::create([
            'name' => 'Donatur Citra',
            'email' => 'donatur@foodcare.org',
            'password' => Hash::make('password'),
            'role' => 'donatur',
            'phone' => '081234567892',
            'address' => 'Jl. Buah Batu No. 54, Bandung',
        ]);

        // 2. Create Donation Programs
        $program1 = ProgramDonasi::create([
            'nama_program' => 'Bantuan Pangan Balita Stunting',
            'deskripsi' => 'Program bantuan pangan bergizi khusus balita dari keluarga pra-sejahtera guna mencegah dan menanggulangi stunting di kawasan perkotaan.',
            'target_dana' => 50000000.00,
            'dana_terkumpul' => 15000000.00,
            'status' => 'aktif',
            'foto' => null,
        ]);

        $program2 = ProgramDonasi::create([
            'nama_program' => 'Sembako Lansia Dhuafa',
            'deskripsi' => 'Distribusi bulanan paket sembako lengkap (beras, minyak goreng, gula, makanan siap saji) bagi lansia sebatang kara yang tidak memiliki penghasilan tetap.',
            'target_dana' => 30000000.00,
            'dana_terkumpul' => 8500000.00,
            'status' => 'aktif',
            'foto' => null,
        ]);

        $program3 = ProgramDonasi::create([
            'nama_program' => 'Dapur Umum Bencana Banjir',
            'deskripsi' => 'Penyediaan makanan siap saji darurat bagi korban banjir bandang di pengungsian.',
            'target_dana' => 20000000.00,
            'dana_terkumpul' => 20000000.00,
            'status' => 'selesai',
            'foto' => null,
        ]);

        // 3. Create Sample Donations
        Donasi::create([
            'user_id' => $donatur->id,
            'program_id' => $program1->id,
            'jumlah' => 10000000.00,
            'metode_pembayaran' => 'Transfer Bank BCA',
            'status' => 'success',
            'tanggal' => Carbon::now()->subDays(5),
        ]);

        Donasi::create([
            'user_id' => $donatur->id,
            'program_id' => $program1->id,
            'jumlah' => 5000000.00,
            'metode_pembayaran' => 'E-Wallet GoPay',
            'status' => 'success',
            'tanggal' => Carbon::now()->subDays(3),
        ]);

        Donasi::create([
            'user_id' => $donatur->id,
            'program_id' => $program2->id,
            'jumlah' => 8500000.00,
            'metode_pembayaran' => 'Transfer Bank Mandiri',
            'status' => 'success',
            'tanggal' => Carbon::now()->subDays(2),
        ]);

        Donasi::create([
            'user_id' => $donatur->id,
            'program_id' => $program1->id,
            'jumlah' => 250000.00,
            'metode_pembayaran' => 'E-Wallet OVO',
            'status' => 'pending',
            'tanggal' => Carbon::now(),
        ]);

        // 4. Create Food Purchases
        PembelianPangan::create([
            'nama_barang' => 'Beras Pandan Wangi',
            'jumlah' => 500,
            'harga_satuan' => 12000.00,
            'total_harga' => 6000000.00,
            'tanggal_beli' => Carbon::now()->subDays(10)->toDateString(),
        ]);

        PembelianPangan::create([
            'nama_barang' => 'Mie Instan Soto',
            'jumlah' => 240, // 6 dus @ 40 pcs
            'harga_satuan' => 3000.00,
            'total_harga' => 720000.00,
            'tanggal_beli' => Carbon::now()->subDays(8)->toDateString(),
        ]);

        PembelianPangan::create([
            'nama_barang' => 'Minyak Goreng Bimoli',
            'jumlah' => 100, // liter
            'harga_satuan' => 18000.00,
            'total_harga' => 1800000.00,
            'tanggal_beli' => Carbon::now()->subDays(5)->toDateString(),
        ]);

        // 5. Create Food Stocks (Different expiration dates to demonstrate FEFO)
        // Beras Batch 1 (expires early)
        StokPangan::create([
            'nama_barang' => 'Beras Pandan Wangi',
            'jumlah' => 200,
            'tanggal_masuk' => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_kadaluarsa' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        // Beras Batch 2 (expires later)
        StokPangan::create([
            'nama_barang' => 'Beras Pandan Wangi',
            'jumlah' => 300,
            'tanggal_masuk' => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_kadaluarsa' => Carbon::now()->addMonths(6)->toDateString(),
        ]);

        // Mie Instan
        StokPangan::create([
            'nama_barang' => 'Mie Instan Soto',
            'jumlah' => 240,
            'tanggal_masuk' => Carbon::now()->subDays(8)->toDateString(),
            'tanggal_kadaluarsa' => Carbon::now()->addMonths(3)->toDateString(),
        ]);

        // Minyak Goreng
        StokPangan::create([
            'nama_barang' => 'Minyak Goreng Bimoli',
            'jumlah' => 100,
            'tanggal_masuk' => Carbon::now()->subDays(5)->toDateString(),
            'tanggal_kadaluarsa' => Carbon::now()->addMonths(9)->toDateString(),
        ]);

        // 6. Create Recipients (Penerima Bantuan) with coords around Bandung City
        PenerimaBantuan::create([
            'nama_penerima' => 'Panti Asuhan Kasih Ibu',
            'alamat' => 'Jl. Veteran No. 12, Sumur Bandung, Bandung',
            'kontak' => '08987654321',
            'latitude' => -6.92050000,
            'longitude' => 107.62350000,
        ]);

        PenerimaBantuan::create([
            'nama_penerima' => 'Posko Lansia Kelurahan Kebon Pisang',
            'alamat' => 'Jl. Jawa No. 5, Sumur Bandung, Bandung',
            'kontak' => '08987654322',
            'latitude' => -6.91350000,
            'longitude' => 107.61550000,
        ]);

        PenerimaBantuan::create([
            'nama_penerima' => 'Yayasan Peduli Dhuafa Cihampelas',
            'alamat' => 'Jl. Cihampelas No. 45, Coblong, Bandung',
            'kontak' => '08987654323',
            'latitude' => -6.89950000,
            'longitude' => 107.60250000,
        ]);

        PenerimaBantuan::create([
            'nama_penerima' => 'Rumah Singgah Anak Jalanan Gatsu',
            'alamat' => 'Jl. Gatot Subroto No. 80, Lengkong, Bandung',
            'kontak' => '08987654324',
            'latitude' => -6.92750000,
            'longitude' => 107.63550000,
        ]);
    }
}
