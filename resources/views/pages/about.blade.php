@extends('layouts/app')

@section('title', 'Tentang FoodCare')

@section('content')
<section class="bg-slate-950 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-300">Tentang Kami</p>
        <h1 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">FoodCare mengubah donasi menjadi pangan yang tercatat, aman, dan tepat sasaran.</h1>
    </div>
</section>

<section class="bg-white py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 md:grid-cols-3">
        <div class="md:col-span-2 text-slate-600 leading-8">
            <p>FoodCare membantu donatur mendukung program pangan, lalu admin mengubah dana tersebut menjadi pembelian pangan. Setiap stok dikelola berdasarkan tanggal kedaluwarsa dan dialokasikan dengan FEFO agar pangan paling dekat kedaluwarsa disalurkan terlebih dahulu.</p>
            <p class="mt-5">Relawan menggunakan panel operasional untuk sorting, packaging, dan distribusi. Bukti distribusi, riwayat stok, dan laporan dampak menjaga proses tetap transparan.</p>
        </div>
        <div class="rounded-lg bg-emerald-50 border border-emerald-100 p-6">
            <h2 class="font-black text-slate-950">Prinsip Operasional</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-700">
                <li>Transparansi dana dan pembelian.</li>
                <li>FEFO untuk keamanan stok pangan.</li>
                <li>Distribusi berbasis data penerima.</li>
                <li>Relawan fokus pada tugas lapangan.</li>
            </ul>
        </div>
    </div>
</section>
@endsection

