@extends('layouts/app')

@section('title', 'FAQ - FoodCare')

@section('content')
<section class="bg-slate-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-700">FAQ</p>
        <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-950">Pertanyaan yang sering diajukan.</h1>

        <div class="mt-10 divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white">
            @foreach([
                ['Ke mana dana donasi disalurkan?', 'Dana donasi digunakan untuk membeli kebutuhan pangan yang kemudian dikelola, dikemas, dan disalurkan kepada penerima manfaat sesuai program donasi.'],
                ['Bagaimana donasi dicatat?', 'Donasi simulasi pembayaran langsung dicatat sebagai berhasil dan otomatis memperbarui total dana program.'],
                ['Apa itu FEFO?', 'FEFO adalah First Expired First Out. Stok dengan tanggal kedaluwarsa paling awal harus dialokasikan terlebih dahulu.'],
                ['Kapan rute distribusi bisa dibuat?', 'Rute hanya bisa dibuat setelah status simulasi gudang terakhir bernilai Optimal.'],
            ] as [$question, $answer])
                <div class="p-6">
                    <h2 class="font-black text-slate-950">{{ $question }}</h2>
                    <p class="mt-2 text-slate-600 leading-7">{{ $answer }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

