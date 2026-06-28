@extends('layouts/app')

@section('title', 'Donasi Saya - FoodCare')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Hero Section Summary -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-700 rounded-2xl sm:rounded-3xl p-6 sm:p-10 text-white mb-8 sm:mb-10 shadow-xl shadow-emerald-950/10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_120%,rgba(16,185,129,0.15),transparent_50%)]"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kebaikan Anda Sangat Berharga</h1>
                <p class="text-emerald-100 text-sm mt-2">
                    Pantau riwayat donasi Anda dan bantu salurkan lebih banyak pangan bagi mereka yang membutuhkan.
                </p>
            </div>

            <!-- Stats: 2-col grid on mobile so labels/numbers never get clipped, inline flex on larger screens -->
            <div class="grid grid-cols-2 gap-4 sm:flex sm:gap-8 w-full sm:w-auto">
                <div class="min-w-0">
                    <span class="text-[11px] sm:text-xs text-emerald-200 block uppercase font-medium tracking-wider whitespace-nowrap">Total Donasi Saya</span>
                    <span class="text-xl sm:text-2xl lg:text-3xl font-black text-white break-words">Rp {{ number_format($totalDonasiSaya, 0, ',', '.') }}</span>
                </div>
                <div class="min-w-0 border-l border-emerald-600 pl-4 sm:pl-8">
                    <span class="text-[11px] sm:text-xs text-emerald-200 block uppercase font-medium tracking-wider whitespace-nowrap">Jumlah Transaksi</span>
                    <span class="text-xl sm:text-2xl lg:text-3xl font-black text-white whitespace-nowrap">{{ number_format($jumlahTransaksi) }} Kali</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm mb-8 sm:mb-10 flex items-start gap-3 sm:gap-3.5 shadow-sm shadow-emerald-50">
            <i class="fa-solid fa-circle-check text-lg sm:text-xl text-emerald-600 mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm sm:text-base mb-1">Transaksi Berhasil!</h4>
                <p class="text-emerald-700 leading-relaxed text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 text-sm mb-8 sm:mb-10 flex items-start gap-3 sm:gap-3.5 shadow-sm shadow-rose-50">
            <i class="fa-solid fa-circle-exclamation text-lg sm:text-xl text-rose-600 mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm sm:text-base mb-1">Transaksi Belum Bisa Diproses</h4>
                <p class="text-rose-700 leading-relaxed text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">

        <!-- Left Column: Donation Form -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 p-5 sm:p-8 shadow-xl shadow-slate-100 lg:sticky lg:top-28">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 border-b border-slate-100 pb-4 mb-6">
                    <i class="fa-solid fa-heart-circle-plus text-emerald-600 mr-2"></i> Form Donasi Baru
                </h2>

                <form action="{{ route('donatur.store') }}" method="POST" class="space-y-5 sm:space-y-6">
                    @csrf

                    <!-- Program -->
                    <div>
                        <label for="program_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Program Donasi</label>
                        <select name="program_id" id="program_id" required
                            class="block w-full py-3.5 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm cursor-pointer">
                            <option value="" disabled selected>-- Pilih Program Donasi --</option>
                            @foreach($activePrograms as $program)
                                <option value="{{ $program->id }}" {{ $selectedProgramId == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label for="jumlah" class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Donasi (Rupiah)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold text-sm">
                                Rp
                            </span>
                            <input id="jumlah" name="jumlah" type="number" min="10000" required inputmode="numeric"
                                class="block w-full pl-10 pr-4 py-3.5 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm font-semibold"
                                placeholder="Min. Rp 10.000">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-100 transition-all cursor-pointer">
                        Bayar dengan Midtrans <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Donation History -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 p-5 sm:p-8 shadow-xl shadow-slate-100">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 border-b border-slate-100 pb-4 mb-6">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600 mr-2"></i> Riwayat Donasi Saya
                </h2>

                <div class="space-y-4 sm:space-y-6">
                    @forelse($donasis as $donasi)
                        <div class="p-4 sm:p-5 border border-slate-100 rounded-2xl flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 sm:gap-4 transition-all duration-300 hover:bg-slate-50/50 hover:border-slate-200">

                            <!-- Info block -->
                            <div class="space-y-2 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 whitespace-nowrap">
                                        {{ $donasi->metode_pembayaran }}
                                    </span>
                                    <span class="text-xs text-slate-400 whitespace-nowrap">
                                        {{ $donasi->tanggal->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-tight">
                                    {{ $donasi->program->nama_program }}
                                </h4>
                                <div class="text-xs text-slate-400">
                                    Donasi ID: #DC-{{ str_pad($donasi->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>

                            <!-- Amount + status: a clean row on mobile, right-aligned column on larger screens -->
                            <div class="flex sm:flex-col items-center justify-between sm:items-end sm:justify-start gap-2 sm:gap-2.5 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                <span class="font-bold text-emerald-600 text-base sm:text-lg">
                                    +Rp {{ number_format($donasi->jumlah, 0, ',', '.') }}
                                </span>
                                @if($donasi->status === 'success')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                    </span>
                                @elseif($donasi->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Tertunda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-10 sm:py-12 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl px-4">
                            <i class="fa-solid fa-heart-crack text-4xl mb-3 block text-slate-300"></i>
                            Anda belum memiliki riwayat transaksi donasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    @if(session('midtrans_snap_token'))
        <script
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            window.addEventListener('load', function () {
                window.snap.pay(@json(session('midtrans_snap_token')), {
                    onSuccess: function () {
                        window.location.href = @json(route('donatur.index'));
                    },
                    onPending: function () {
                        window.location.href = @json(route('donatur.index'));
                    },
                    onError: function () {
                        window.location.href = @json(route('donatur.index'));
                    },
                    onClose: function () {
                        window.location.href = @json(route('donatur.index'));
                    }
                });
            });
        </script>
    @endif
@endsection