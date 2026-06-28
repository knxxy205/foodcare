<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProgramDonasi;
use App\Models\PembelianPangan;
use App\Models\StokPangan;
use App\Models\PenerimaBantuan;
use App\Models\Distribusi;
use App\Models\Sorting;
use App\Models\Packaging;
use App\Models\SimulasiGudang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FoodCareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test role-based authorization for the Filament admin panel path.
     */
    public function test_filament_panel_authorization(): void
    {
        // 1. Guest is redirected
        $response = $this->get('/admin');
        $response->assertStatus(302);

        // 2. Donor is forbidden
        $donor = User::create([
            'name' => 'Donatur Test',
            'email' => 'donor@test.com',
            'password' => Hash::make('password'),
            'role' => 'donatur',
        ]);

        $response = $this->actingAs($donor)->get('/admin');
        $response->assertStatus(403);

        // 3. Admin can access (returns either OK or redirect to admin login if not fully authenticated, but not 403)
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    /**
     * Test that distribution uses strict FEFO logic for stock deduction.
     */
    public function test_fefo_stock_allocation(): void
    {
        // 1. Create a volunteer
        $volunteer = User::create([
            'name' => 'Relawan Test',
            'email' => 'volunteer@test.com',
            'password' => Hash::make('password'),
            'role' => 'relawan',
        ]);

        // 2. Create Stock A (expires soonest: in 1 month)
        $stockA = StokPangan::create([
            'nama_barang' => 'Beras Pandan',
            'jumlah' => 100,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_kadaluarsa' => now()->addMonth()->toDateString(),
        ]);

        // Create Stock B (expires later: in 2 months)
        $stockB = StokPangan::create([
            'nama_barang' => 'Beras Pandan',
            'jumlah' => 100,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_kadaluarsa' => now()->addMonths(2)->toDateString(),
        ]);

        // Assert starting available stocks
        $this->assertEquals(100, $stockA->jumlah_tersedia);
        $this->assertEquals(100, $stockB->jumlah_tersedia);

        Sorting::create([
            'relawan_id' => $volunteer->id,
            'stok_id' => $stockA->id,
            'jumlah' => 100,
            'waktu_proses' => now(),
        ]);

        Sorting::create([
            'relawan_id' => $volunteer->id,
            'stok_id' => $stockB->id,
            'jumlah' => 100,
            'waktu_proses' => now(),
        ]);

        Packaging::create([
            'relawan_id' => $volunteer->id,
            'stok_id' => $stockA->id,
            'jumlah' => 100,
            'waktu_proses' => now(),
        ]);

        Packaging::create([
            'relawan_id' => $volunteer->id,
            'stok_id' => $stockB->id,
            'jumlah' => 100,
            'waktu_proses' => now(),
        ]);

        // 4. Create a recipient
        $recipient = PenerimaBantuan::create([
            'nama_penerima' => 'Panti Test',
            'alamat' => 'Alamat Test',
            'kontak' => '0812345',
        ]);

        // 5. Simulate warehouse capacity so distribution can be created.
        SimulasiGudang::create([
            'jumlah_barang' => 160,
            'jumlah_relawan' => 20,
            'kapasitas_sorting' => 200,
            'kapasitas_packaging' => 200,
            'status_bottleneck' => 'Optimal',
        ]);

        // 6. Create a distribution of 160 packages of Beras Pandan.
        // System must consume the earliest expiration batch first.
        $distribusi = Distribusi::create([
            'relawan_id' => $volunteer->id,
            'penerima_id' => $recipient->id,
            'nama_barang' => 'Beras Pandan',
            'jumlah_paket' => 160,
            'status' => 'pending',
            'tanggal' => now()->toDateString(),
        ]);

        // 6. Verify FEFO allocations in pivot table
        $allocationA = $distribusi->stokPangans()->where('stok_pangan_id', $stockA->id)->first();
        $allocationB = $distribusi->stokPangans()->where('stok_pangan_id', $stockB->id)->first();

        $this->assertNotNull($allocationA);
        $this->assertNotNull($allocationB);
        $this->assertEquals(100, $allocationA->pivot->jumlah);
        $this->assertEquals(60, $allocationB->pivot->jumlah);

        // Verify canonical available stock and history logs are updated correctly
        $this->assertEquals(0, $stockA->fresh()->jumlah_tersedia);
        $this->assertEquals(40, $stockB->fresh()->jumlah_tersedia);
        $this->assertDatabaseHas('riwayat_stok_pangan', [
            'stok_pangan_id' => $stockA->id,
            'reference_id' => $distribusi->id,
            'tipe' => 'alokasi_fefo',
            'jumlah_perubahan' => -100,
        ]);
    }
}
