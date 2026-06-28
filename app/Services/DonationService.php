<?php

namespace App\Services;

use App\Models\Donasi;
use App\Models\ProgramDonasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;

class DonationService
{
    public function createMidtransDonation(User $donor, ProgramDonasi $program, float $amount): Donasi
    {
        $donation = DB::transaction(function () use ($donor, $program, $amount) {
            $donation = Donasi::create([
                'user_id' => $donor->id,
                'program_id' => $program->id,
                'jumlah' => $amount,
                'metode_pembayaran' => 'Midtrans',
                'status' => 'pending',
                'tanggal' => now(),
            ]);

            $donation->forceFill([
                'order_id' => 'DONASI-' . $donation->id . '-' . Str::upper(Str::random(8)),
            ])->save();

            return $donation;
        });

        $snapToken = $this->createSnapToken($donation);

        $donation->forceFill([
            'snap_token' => $snapToken,
        ])->save();

        return $donation->fresh(['user', 'program']);
    }

    public function createSuccessfulDonation(User $donor, ProgramDonasi $program, float $amount, string $paymentMethod): Donasi
    {
        return DB::transaction(function () use ($donor, $program, $amount, $paymentMethod) {
            $donation = Donasi::create([
                'user_id' => $donor->id,
                'program_id' => $program->id,
                'jumlah' => $amount,
                'metode_pembayaran' => $paymentMethod,
                'status' => 'success',
                'tanggal' => now(),
            ]);

            $this->syncProgramTotal($program);

            return $donation;
        });
    }

    public function handleMidtransNotification(array $payload): ?Donasi
    {
        if (! $this->isValidMidtransSignature($payload)) {
            return null;
        }

        $donation = Donasi::where('order_id', $payload['order_id'] ?? null)->first();

        if (! $donation) {
            return null;
        }

        return $this->applyMidtransStatus($donation, $payload);
    }

    public function syncMidtransStatus(Donasi $donation): Donasi
    {
        if (app()->environment('testing') || ! $donation->order_id || $donation->status !== 'pending') {
            return $donation;
        }

        $this->configureMidtrans();

        $payload = json_decode(json_encode(Transaction::status($donation->order_id)), true);

        return $this->applyMidtransStatus($donation, $payload);
    }

    private function applyMidtransStatus(Donasi $donation, array $payload): Donasi
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $status = match ($transactionStatus) {
            'settlement' => 'success',
            'capture' => $fraudStatus === 'challenge' ? 'pending' : 'success',
            'pending' => 'pending',
            'deny', 'expire', 'cancel', 'failure' => 'failed',
            default => $donation->status,
        };

        $donation->forceFill([
            'status' => $status,
            'metode_pembayaran' => $payload['payment_type'] ?? $donation->metode_pembayaran,
            'payment_type' => $payload['payment_type'] ?? $donation->payment_type,
            'midtrans_status' => $transactionStatus,
            'paid_at' => $status === 'success' ? now() : $donation->paid_at,
        ])->save();

        return $donation;
    }

    public function syncProgramTotal(ProgramDonasi $program): void
    {
        $program->forceFill([
            'dana_terkumpul' => $program->donasis()->where('status', 'success')->sum('jumlah'),
        ])->save();
    }

    private function createSnapToken(Donasi $donation): string
    {
        if (app()->environment('testing')) {
            return 'test-snap-token-' . $donation->id;
        }

        $this->configureMidtrans();

        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $donation->order_id,
                'gross_amount' => (int) $donation->jumlah,
            ],
            'customer_details' => [
                'first_name' => $donation->user->name,
                'email' => $donation->user->email,
                'phone' => $donation->user->phone,
            ],
            'item_details' => [
                [
                    'id' => 'program-' . $donation->program_id,
                    'price' => (int) $donation->jumlah,
                    'quantity' => 1,
                    'name' => Str::limit($donation->program->nama_program, 50, ''),
                ],
            ],
            'callbacks' => [
                'finish' => route('donatur.index'),
            ],
        ]);
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = (string) config('services.midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production');
        MidtransConfig::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        MidtransConfig::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    private function isValidMidtransSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if ($serverKey === '') {
            return false;
        }

        foreach (['order_id', 'status_code', 'gross_amount', 'signature_key'] as $key) {
            if (! isset($payload[$key])) {
                return false;
            }
        }

        $signature = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey
        );

        return hash_equals($signature, $payload['signature_key']);
    }
}
