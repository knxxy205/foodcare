@extends('layouts/app')

@section('title', $program->nama_program . ' - FoodCare')

@section('content')
@php
    $progress = $program->target_dana > 0 ? min(100, ($program->dana_terkumpul / $program->target_dana) * 100) : 0;
@endphp

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-[1fr_380px]">
        <div>
            <a href="{{ route('programs.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-600">Kembali ke program</a>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight text-slate-950">{{ $program->nama_program }}</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">{{ $program->deskripsi }}</p>

            <div class="mt-10 rounded-lg border border-slate-200 bg-slate-50 p-6">
                <h2 class="text-xl font-black text-slate-950">Transparansi Donasi Terbaru</h2>
                <div class="mt-5 divide-y divide-slate-200">
                    @forelse($program->donasis as $donasi)
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-slate-900">{{ $donasi->user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $donasi->tanggal->format('d M Y, H:i') }} via {{ $donasi->metode_pembayaran }}</p>
                            </div>
                            <p class="font-black text-emerald-700">Rp {{ number_format($donasi->jumlah, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <div class="py-8 text-slate-500">Belum ada donasi terverifikasi untuk program ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="lg:sticky lg:top-24 h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ ucfirst($program->status) }}</span>
            <div class="mt-4 h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-emerald-600" style="width: {{ $progress }}%"></div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-slate-500">Terkumpul</p>
                <p class="text-3xl font-black text-slate-950">Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</p>
                <p class="mt-1 text-sm text-slate-500">Target Rp {{ number_format($program->target_dana, 0, ',', '.') }}</p>
            </div>
            <a href="{{ auth()->check() ? route('donatur.index', ['program_id' => $program->id]) : route('login') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Donasi Sekarang</a>
        </aside>
    </div>
</section>
@endsection

