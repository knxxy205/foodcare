@extends('layouts/app')

@section('title', 'Program Donasi - FoodCare')

@section('content')
<section class="bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-300">Program FoodCare</p>
        <h1 class="mt-4 max-w-3xl text-4xl sm:text-5xl font-black tracking-tight">Pilih program pangan yang ingin Anda dukung.</h1>
        <p class="mt-5 max-w-2xl text-slate-300 leading-7">Setiap donasi masuk tercatat transparan dan digunakan admin untuk membeli, mengelola, serta menyalurkan pangan dengan prinsip FEFO.</p>
    </div>
</section>

<section class="bg-slate-50 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($programs as $program)
                @php
                    $progress = $program->target_dana > 0 ? min(100, ($program->dana_terkumpul / $program->target_dana) * 100) : 0;
                @endphp
                <article class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="h-44 bg-emerald-700 flex items-center justify-center text-white relative">
                        <span class="text-5xl font-black">FC</span>
                        <span class="absolute top-4 right-4 px-3 py-1.5 rounded-full bg-slate-900/80 text-white text-xs font-bold">
                            @php
                                $daysRemaining = $program->getDaysRemaining();
                                if ($daysRemaining !== null && $daysRemaining > 0) {
                                    echo $daysRemaining . ' hari lagi';
                                } elseif ($daysRemaining !== null && $daysRemaining <= 0) {
                                    echo 'Donasi Ditutup';
                                } else {
                                    echo 'Donasi Aktif';
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ ucfirst($program->status) }}</span>
                            <span class="text-xs text-slate-500">#{{ str_pad($program->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h2 class="mt-3 text-xl font-black text-slate-950">{{ $program->nama_program }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600 line-clamp-3">{{ $program->deskripsi }}</p>
                        <div class="mt-5 h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-emerald-600" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="mt-3 flex justify-between text-sm">
                            <span class="font-bold text-slate-900">Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</span>
                            <span class="text-slate-500">Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('programs.show', $program) }}" class="mt-5 inline-flex w-full items-center justify-center rounded-md bg-slate-950 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Lihat Program</a>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">Belum ada program donasi.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $programs->links() }}</div>
    </div>
</section>
@endsection

