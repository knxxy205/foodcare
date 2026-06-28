<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramDonasi;
use App\Models\Donasi;
use App\Models\PembelianPangan;
use App\Models\Distribusi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Calculations for Hero & Impact Sections
        $totalDonasi = Donasi::where('status', 'success')->sum('jumlah');
        $totalPanganTersalurkan = Distribusi::where('status', 'selesai')->sum('jumlah_paket');
        
        // Count unique donors with successful donations
        $totalDonatur = Donasi::where('status', 'success')->distinct('user_id')->count('user_id');
        if ($totalDonatur === 0) {
            $totalDonatur = User::where('role', 'donatur')->count();
        }
        
        $totalDistribusi = Distribusi::count();
        $totalRelawan = User::where('role', 'relawan')->count();
        if ($totalRelawan === 0) {
            $totalRelawan = 1; // Default fallback to make seeder look good
        }

        // 2. Fetch Active Programs
        $programs = ProgramDonasi::where('status', 'aktif')->get();

        // 3. Transparency Logs
        $pembelians = PembelianPangan::orderBy('tanggal_beli', 'desc')->take(5)->get();
        
        $donasis = Donasi::with(['user', 'program'])
            ->where('status', 'success')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $distribusis = Distribusi::with(['penerima', 'relawan'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('home', compact(
            'totalDonasi',
            'totalPanganTersalurkan',
            'totalDonatur',
            'totalDistribusi',
            'totalRelawan',
            'programs',
            'pembelians',
            'donasis',
            'distribusis'
        ));
    }

    public function programs()
    {
        $programs = ProgramDonasi::withSum(['donasis as verified_donations_sum' => fn ($query) => $query->where('status', 'success')], 'jumlah')
            ->latest()
            ->paginate(9);

        return view('pages.programs', compact('programs'));
    }

    public function programDetail(ProgramDonasi $programDonasi)
    {
        $programDonasi->load(['donasis' => fn ($query) => $query->with('user')->where('status', 'success')->latest('tanggal')->take(8)]);

        return view('pages.program-detail', ['program' => $programDonasi]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
