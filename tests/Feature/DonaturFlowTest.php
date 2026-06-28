<?php

namespace Tests\Feature;

use App\Models\ProgramDonasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonaturFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_always_creates_donatur(): void
    {
        $this->post('/register', [
            'name' => 'Donatur Baru',
            'email' => 'baru@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '081234567',
            'address' => 'Bandung',
            'role' => 'admin',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.com',
            'role' => 'donatur',
        ]);
    }

    public function test_donation_flow_records_pending_midtrans_payment(): void
    {
        $donor = User::factory()->donatur()->create();
        $program = ProgramDonasi::factory()->create([
            'dana_terkumpul' => 0,
            'status' => 'aktif',
        ]);

        $this->actingAs($donor)
            ->post('/donasi-saya', [
                'program_id' => $program->id,
                'jumlah' => 125000,
            ])
            ->assertRedirect(route('donatur.index'))
            ->assertSessionHas('midtrans_snap_token');

        $this->assertDatabaseHas('donasi', [
            'user_id' => $donor->id,
            'program_id' => $program->id,
            'metode_pembayaran' => 'Midtrans',
            'status' => 'pending',
        ]);

        $this->assertEquals(0.0, (float) $program->fresh()->dana_terkumpul);
    }

    public function test_midtrans_notification_marks_donation_success_and_updates_program_total(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $donor = User::factory()->donatur()->create();
        $program = ProgramDonasi::factory()->create([
            'dana_terkumpul' => 0,
            'status' => 'aktif',
        ]);

        $this->actingAs($donor)->post('/donasi-saya', [
            'program_id' => $program->id,
            'jumlah' => 125000,
        ]);

        $donation = $donor->donasis()->firstOrFail();
        $payload = [
            'order_id' => $donation->order_id,
            'status_code' => '200',
            'gross_amount' => '125000.00',
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ];
        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . 'test-server-key'
        );

        $this->postJson('/midtrans/notification', $payload)
            ->assertOk()
            ->assertJson([
                'donation_status' => 'success',
            ]);

        $this->assertDatabaseHas('donasi', [
            'id' => $donation->id,
            'status' => 'success',
            'metode_pembayaran' => 'qris',
            'payment_type' => 'qris',
            'midtrans_status' => 'settlement',
        ]);

        $this->assertEquals(125000.0, (float) $program->fresh()->dana_terkumpul);
    }
}
