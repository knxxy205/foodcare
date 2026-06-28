@extends('layouts/app')

@section('title', 'Kontak - FoodCare')

@section('content')
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-10 lg:grid-cols-[1fr_420px]">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-700">Kontak</p>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight text-slate-950">Bicarakan kebutuhan pangan komunitas Anda dengan FoodCare.</h1>
            <p class="mt-5 text-slate-600 leading-8">Hubungi tim admin untuk kerja sama program, data penerima bantuan, atau koordinasi relawan.</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Email</p>
                    <p class="mt-1 font-black text-slate-950">hello@foodcare.org</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Telepon</p>
                    <p class="mt-1 font-black text-slate-950">0812-3456-7890</p>
                </div>
            </div>
        </div>

        <form class="rounded-lg border border-slate-200 bg-slate-50 p-6 space-y-4" method="GET" action="{{ route('contact') }}">
            <div>
                <label class="block text-sm font-bold text-slate-700" for="name">Nama</label>
                <input class="mt-1 w-full rounded-md border-slate-300 px-3 py-3 text-sm" id="name" name="name" type="text" placeholder="Nama lengkap">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700" for="email">Email</label>
                <input class="mt-1 w-full rounded-md border-slate-300 px-3 py-3 text-sm" id="email" name="email" type="email" placeholder="nama@email.com">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700" for="message">Pesan</label>
                <textarea class="mt-1 w-full rounded-md border-slate-300 px-3 py-3 text-sm" id="message" name="message" rows="5" placeholder="Ceritakan kebutuhan Anda"></textarea>
            </div>
            <button type="submit" class="w-full rounded-md bg-slate-950 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Kirim Pesan</button>
        </form>
    </div>
</section>
@endsection

