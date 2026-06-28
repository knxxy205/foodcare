<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramDonasi;
use App\Models\Donasi;
use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DonasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $pendingDonations = Donasi::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereNotNull('order_id')
            ->latest('tanggal')
            ->take(5)
            ->get();

        foreach ($pendingDonations as $pendingDonation) {
            try {
                app(DonationService::class)->syncMidtransStatus($pendingDonation);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
        
        // Donatur Area Statistics
        $totalDonasiSaya = Donasi::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('jumlah');
            
        $jumlahTransaksi = Donasi::where('user_id', $user->id)
            ->count();
            
        $donasis = Donasi::with('program')
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // For the donation form
        $selectedProgramId = $request->query('program_id');
        $activePrograms = ProgramDonasi::where('status', 'aktif')
            ->where(function ($query) {
                $query->whereNull('tanggal_berakhir')
                    ->orWhere('tanggal_berakhir', '>=', now()->toDateString());
            })
            ->get();

        return view('donatur.index', compact(
            'totalDonasiSaya',
            'jumlahTransaksi',
            'donasis',
            'activePrograms',
            'selectedProgramId'
        ));
    }

    public function store(Request $request, DonationService $donationService)
    {
        $request->validate([
            'program_id' => ['required', 'exists:program_donasi,id'],
            'jumlah' => ['required', 'numeric', 'min:10000'],
        ]);

        $program = ProgramDonasi::findOrFail($request->program_id);

        // Validasi status program
        if ($program->status !== 'aktif') {
            return redirect()->back()->with('error', 'Program donasi tidak aktif.');
        }

        // Validasi tanggal berakhir
        if ($program->tanggal_berakhir && now()->isAfter($program->tanggal_berakhir)) {
            return redirect()->back()->with('error', 'Masa donasi program ini telah berakhir.');
        }

        try {
            $donasi = $donationService->createMidtransDonation(
                $request->user(),
                $program,
                (float) $request->jumlah
            );
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pembayaran Midtrans belum bisa dibuat. Silakan coba lagi beberapa saat.');
        }

        return redirect()->route('donatur.index')->with([
            'success' => 'Donasi Anda sebesar Rp ' . number_format($donasi->jumlah, 0, ',', '.') . ' sudah dibuat. Silakan lanjutkan pembayaran di Midtrans.',
            'midtrans_snap_token' => $donasi->snap_token,
        ]);
    }

    public function midtransNotification(Request $request, DonationService $donationService)
    {
        $donasi = $donationService->handleMidtransNotification($request->all());

        if (! $donasi) {
            return response()->json([
                'message' => 'Notification ignored.',
            ], 400);
        }

        return response()->json([
            'message' => 'Notification processed.',
            'donation_status' => $donasi->status,
        ]);
    }

    public function profil()
    {
        $user = auth()->user();
        return view('donatur.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('donatur.profil')->with('success', 'Profil Anda telah berhasil diperbarui.');
    }
}
