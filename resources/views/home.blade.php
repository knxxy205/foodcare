@extends('layouts/app')

@section('title', 'FoodCare - Sambungkan Kebaikan, Salurkan Pangan')

@section('content')
<!-- Hero Section -->
<section id="beranda" class="scroll-mt-28 relative py-20 lg:py-24 bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 text-white overflow-hidden">
    <!-- Overlay effects -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(16,185,129,0.08),transparent_50%)]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Text Column -->
            <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Platform Transparan Penyaluran Pangan
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Sambungkan Kebaikan, <br>
                    <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Salurkan Pangan</span> Untuk Rakyat
                </h1>
                
                <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    FoodCare membantu Anda berdonasi uang secara transparan yang dikelola langsung untuk dibelikan bahan makanan segar, disortir, dikemas, dan didistribusikan secara adil menggunakan metode FEFO.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#program" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-extrabold shadow-lg shadow-emerald-500/20 transition-all hover:shadow-emerald-500/40 hover:-translate-y-0.5 text-center">
                        Donasi Sekarang <i class="fa-solid fa-heart ml-2"></i>
                    </a>
                    <a href="#cara-kerja" class="px-8 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-bold transition-all text-center">
                        Pelajari Cara Kerja
                    </a>
                </div>
            </div>
            
            <!-- Right Banner Column -->
            <div class="lg:col-span-5 relative group">
                <!-- Floating decorative circles -->
                <div class="absolute -top-6 -left-6 w-24 h-24 bg-emerald-500/20 rounded-full blur-xl animate-pulse"></div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-teal-500/20 rounded-full blur-xl animate-pulse delay-700"></div>
                
                <!-- Main Image Wrapper with modern borders and glassmorphism outline -->
                <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl transition-all duration-500 hover:scale-[1.02] hover:shadow-emerald-500/10">
                    <img src="{{ asset('img/photo.jpg') }}" alt="FoodCare Banner" class="w-full h-[360px] object-cover filter brightness-95 contrast-105">
                    
                    <!-- Decorative Gradient overlay on the image -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-slate-900/85 backdrop-blur-md border border-white/10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-bold shrink-0">
                            <i class="fa-solid fa-seedling text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">100% Transparan</p>
                            <p class="text-xs text-slate-300">Setiap rupiah disalurkan dengan FEFO</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Stats Horizontal Row (Moved & Modernized) -->
        <div class="mt-32 lg:mt-40 p-6 sm:p-8 rounded-2xl bg-slate-800/30 backdrop-blur-md border border-slate-700/50 shadow-2xl">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                <!-- Stat 1 -->
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Total Donasi</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-400">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</span>
                </div>
                <!-- Stat 2 -->
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Pangan Tersalurkan</span>
                    <span class="text-2xl sm:text-3xl font-black text-teal-400">{{ number_format($totalPanganTersalurkan, 0, ',', '.') }} Paket</span>
                </div>
                <!-- Stat 3 -->
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Donatur Terdaftar</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-400">{{ number_format($totalDonatur, 0, ',', '.') }} Jiwa</span>
                </div>
                <!-- Stat 4 -->
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 block font-medium uppercase tracking-wider">Distribusi Aktif</span>
                    <span class="text-2xl sm:text-3xl font-black text-sky-400">{{ number_format($totalDistribusi, 0, ',', '.') }} Titik</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Program Donasi Section -->
<section id="program" class="scroll-mt-28 py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h2 class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Program Aktif</h2>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Pilih Program Bantuan Pangan Anda</h2>
            <p class="text-slate-500">
                Pilih program donasi di bawah ini untuk membantu saudara-saudara kita yang membutuhkan. Setiap rupiah akan kami catat dan kelola secara transparan.
            </p>
        </div>

        <!-- Program Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($programs as $program)
                @php
                    $percentage = $program->target_dana > 0 ? ($program->dana_terkumpul / $program->target_dana) * 100 : 0;
                    $percentage = min($percentage, 100);
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-100 overflow-hidden flex flex-col group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl">
                    <!-- Image -->
                    <div class="h-48 bg-slate-200 relative">
                        @if($program->foto)
                            <img src="{{ asset('storage/' . $program->foto) }}" alt="{{ $program->nama_program }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-700 to-teal-500 flex items-center justify-center text-white">
                                <i class="fa-solid fa-utensils text-5xl opacity-40"></i>
                            </div>
                        @endif
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

                    <!-- Body -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-6">
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                {{ $program->nama_program }}
                            </h3>
                            <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed">
                                {{ $program->deskripsi }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <!-- Progress Bar -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-slate-400">Terkumpul {{ number_format($percentage, 1) }}%</span>
                                    <span class="text-slate-800">Target Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-xs pt-2 border-t border-slate-50">
                                <div>
                                    <span class="text-slate-400 block">Dana Terkumpul</span>
                                    <span class="font-bold text-slate-800 text-sm">Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 block"><i class="fa-regular fa-clock mr-1"></i> Batas Waktu</span>
                                    <span class="font-bold text-xs px-2 py-0.5 rounded-full
                                        @php
                                            $daysRemaining = $program->getDaysRemaining();
                                            if ($daysRemaining !== null && $daysRemaining > 0) {
                                                echo 'text-emerald-600 bg-emerald-50';
                                            } elseif ($daysRemaining !== null && $daysRemaining <= 0) {
                                                echo 'text-rose-600 bg-rose-50';
                                            } else {
                                                echo 'text-slate-500 bg-slate-100';
                                            }
                                        @endphp
                                    ">
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
                            </div>
                            
                            @auth
                            @if(auth()->user()->role === 'donatur')
                                @php
                                    $isActive = $program->isActive();
                                @endphp
                                @if(!$isActive)
                                    <button disabled class="block text-center w-full py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed">
                                        Donasi Ditutup
                                    </button>
                                @else
                                    <a href="/donasi-saya?program_id={{ $program->id }}" class="block text-center w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition-all">
                                        Berdonasi Sekarang
                                    </a>
                                @endif
                            @else
                                <button disabled class="block text-center w-full py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed">
                                    Donasi Hanya Untuk Akun Donatur
                                </button>
                            @endif
                        @else
                            <a href="/login" class="block text-center w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition-all">
                                Masuk Untuk Berdonasi
                            </a>
                        @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-100">
                    <i class="fa-solid fa-hourglass-empty text-4xl mb-3 block"></i>
                    Belum ada program donasi pangan aktif saat ini.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Cara Kerja Section -->
<section id="cara-kerja" class="py-20 bg-white border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h2 class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Alur Distribusi</h2>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Bagaimana FoodCare Bekerja?</h2>
            <p class="text-slate-500">
                Kami membangun rantai pasokan pangan terintegrasi agar setiap donasi Anda sampai ke penerima manfaat dalam kondisi terbaik dan tepat sasaran.
            </p>
        </div>

        <!-- Steps Layout -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connector Line (Desktop) -->
            <div class="hidden md:block absolute top-1/3 left-[12%] right-[12%] h-[2px] bg-slate-100 z-0"></div>

            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl text-center space-y-4 relative z-10 border border-slate-50 shadow-lg shadow-slate-100/50">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
                    <i class="fa-solid fa-wallet text-2xl"></i>
                </div>
                <div class="text-slate-300 font-bold text-xs uppercase tracking-wider">Langkah 1</div>
                <h3 class="font-bold text-slate-900">Donasi Uang</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Donatur berdonasi uang secara instan melalui sistem pembayaran yang terdaftar di program donasi.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl text-center space-y-4 relative z-10 border border-slate-50 shadow-lg shadow-slate-100/50">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto shadow-md">
                    <i class="fa-solid fa-cart-shopping text-2xl"></i>
                </div>
                <div class="text-slate-300 font-bold text-xs uppercase tracking-wider">Langkah 2</div>
                <h3 class="font-bold text-slate-900">Pembelian Pangan</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Admin membelanjakan dana terkumpul untuk membeli komoditas pangan pokok berkualitas secara transparan.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl text-center space-y-4 relative z-10 border border-slate-50 shadow-lg shadow-slate-100/50">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto shadow-md">
                    <i class="fa-solid fa-boxes-packing text-2xl"></i>
                </div>
                <div class="text-slate-300 font-bold text-xs uppercase tracking-wider">Langkah 3</div>
                <h3 class="font-bold text-slate-900">Sorting & Packaging</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Relawan melakukan penyortiran kelayakan (FEFO) dan pengemasan ke dalam paket bantuan sembako.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl text-center space-y-4 relative z-10 border border-slate-50 shadow-lg shadow-slate-100/50">
                <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto shadow-md">
                    <i class="fa-solid fa-truck-fast text-2xl"></i>
                </div>
                <div class="text-slate-300 font-bold text-xs uppercase tracking-wider">Langkah 4</div>
                <h3 class="font-bold text-slate-900">Distribusi Bantuan</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Paket bantuan dikirim ke penerima manfaat berdasarkan rute optimal yang dihitung secara cerdas.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Dampak Sosial Section -->
<section id="dampak" class="py-20 bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_70%,rgba(20,184,166,0.1),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h2 class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Dampak Sosial</h2>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Kebaikan Anda dalam Angka Nyata</h2>
            <p class="text-slate-300">
                Setiap donasi yang disalurkan memberikan dampak langsung kepada keluarga-keluarga penerima manfaat pangan di berbagai wilayah kota.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            <!-- Stat 1 -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative text-center p-6 sm:p-8 lg:p-10 bg-slate-800/60 border border-emerald-500/30 rounded-2xl backdrop-blur-sm hover:border-emerald-500/60 hover:bg-slate-800/80 transition-all duration-300">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-14 h-14 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-hand-holding-heart text-emerald-400 text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-black text-emerald-400 block">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</span>
                            <span class="text-xs sm:text-xs lg:text-sm text-slate-300 font-bold uppercase tracking-wider block">Dana Donasi<span class="hidden lg:inline"> Disalurkan</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stat 2 -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/20 to-cyan-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative text-center p-6 sm:p-8 lg:p-10 bg-slate-800/60 border border-teal-500/30 rounded-2xl backdrop-blur-sm hover:border-teal-500/60 hover:bg-slate-800/80 transition-all duration-300">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-14 h-14 rounded-xl bg-teal-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-box-open text-teal-400 text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-black text-teal-400 block">{{ number_format($totalPanganTersalurkan, 0, ',', '.') }}</span>
                            <span class="text-xs sm:text-xs lg:text-sm text-slate-300 font-bold uppercase tracking-wider block">Paket Sembako<span class="hidden lg:inline"> Tersebar</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stat 3 -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative text-center p-6 sm:p-8 lg:p-10 bg-slate-800/60 border border-amber-500/30 rounded-2xl backdrop-blur-sm hover:border-amber-500/60 hover:bg-slate-800/80 transition-all duration-300">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-14 h-14 rounded-xl bg-amber-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-map-location-dot text-amber-400 text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-black text-amber-400 block">{{ number_format($totalDistribusi, 0, ',', '.') }}</span>
                            <span class="text-xs sm:text-xs lg:text-sm text-slate-300 font-bold uppercase tracking-wider block">Titik Distribusi<span class="hidden lg:inline"> Terjangkau</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stat 4 -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/20 to-blue-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative text-center p-6 sm:p-8 lg:p-10 bg-slate-800/60 border border-sky-500/30 rounded-2xl backdrop-blur-sm hover:border-sky-500/60 hover:bg-slate-800/80 transition-all duration-300">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-14 h-14 rounded-xl bg-sky-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-people-group text-sky-400 text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-black text-sky-400 block">{{ number_format($totalRelawan, 0, ',', '.') }}</span>
                            <span class="text-xs sm:text-xs lg:text-sm text-slate-300 font-bold uppercase tracking-wider block">Relawan Lapangan<span class="hidden lg:inline"> Aktif</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Transparansi Penyaluran Donasi Section -->
<section id="transparansi" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h2 class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Prinsip Transparansi</h2>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Laporan & Audit Terbuka</h2>
            <p class="text-slate-500">
                Kami mencatat setiap transaksi donasi masuk, pembelanjaan pangan logistik, dan laporan penyaluran di lapangan secara real-time.
            </p>
        </div>

        <!-- Transparency Tabs (Alpine.js) -->
        <div x-data="{ activeTab: 'donasi' }" class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 overflow-hidden">
            <!-- Tabs Headers -->
            <div class="flex border-b border-slate-100 bg-slate-50/50 p-2 gap-2 flex-wrap sm:flex-nowrap">
                <button @click="activeTab = 'donasi'" 
                    :class="activeTab === 'donasi' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-emerald-500 hover:bg-white/50'"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                    <i class="fa-solid fa-hand-holding-dollar mr-2"></i> Donasi Masuk
                </button>
                <button @click="activeTab = 'pembelian'" 
                    :class="activeTab === 'pembelian' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-emerald-500 hover:bg-white/50'"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                    <i class="fa-solid fa-cart-shopping mr-2"></i> Pembelian Pangan
                </button>
                <button @click="activeTab = 'distribusi'" 
                    :class="activeTab === 'distribusi' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-emerald-500 hover:bg-white/50'"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                    <i class="fa-solid fa-truck-ramp-box mr-2"></i> Distribusi Bantuan
                </button>
            </div>

            <!-- Tab Content 1: Donasi Masuk -->
            <div x-show="activeTab === 'donasi'" class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-bold text-slate-700 bg-slate-50/30">
                            <th class="px-6 py-4 rounded-l-xl">Donatur</th>
                            <th class="px-6 py-4">Program Donasi</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4 text-right rounded-r-xl">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($donasis as $d)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $d->user->name }}</td>
                                <td class="px-6 py-4 line-clamp-1 max-w-xs">{{ $d->program->nama_program }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-600 font-semibold">{{ $d->metode_pembayaran }}</span></td>
                                <td class="px-6 py-4">{{ $d->tanggal->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada donasi masuk yang terverifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab Content 2: Pembelian Pangan -->
            <div x-show="activeTab === 'pembelian'" class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-bold text-slate-700 bg-slate-50/30">
                            <th class="px-6 py-4 rounded-l-xl">Nama Barang</th>
                            <th class="px-6 py-4 text-center">Jumlah</th>
                            <th class="px-6 py-4">Harga Satuan</th>
                            <th class="px-6 py-4">Tanggal Pembelian</th>
                            <th class="px-6 py-4 text-right rounded-r-xl">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($pembelians as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $p->nama_barang }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($p->jumlah) }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $p->tanggal_beli->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-800">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada catatan pembelian pangan pokok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab Content 3: Distribusi Bantuan -->
            <div x-show="activeTab === 'distribusi'" class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-bold text-slate-700 bg-slate-50/30">
                            <th class="px-6 py-4 rounded-l-xl">Penerima Manfaat</th>
                            <th class="px-6 py-4">Alamat Penyaluran</th>
                            <th class="px-6 py-4 text-center">Paket</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right rounded-r-xl">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($distribusis as $dist)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $dist->penerima->nama_penerima }}</td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $dist->penerima->alamat }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">{{ $dist->jumlah_paket }} Paket</td>
                                <td class="px-6 py-4">
                                    @if($dist->status === 'selesai')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700"><i class="fa-solid fa-circle-check mr-1"></i> Tersalurkan</span>
                                    @elseif($dist->status === 'dalam_proses' || $dist->status === 'dikirim')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700"><i class="fa-solid fa-truck-fast mr-1"></i> Pengiriman</span>
                                    @elseif($dist->status === 'gagal')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Gagal</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600"><i class="fa-solid fa-clock mr-1"></i> Antrean</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">{{ $dist->tanggal->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada laporan penyaluran distribusi paket pangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-20 bg-white" x-data="{ activeFaq: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Pertanyaan Umum</h2>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sering Ditanyakan (FAQ)</h2>
            <p class="text-slate-500">
                Temukan jawaban seputar teknis donasi, transparansi, dan operasional pengemasan pangan FoodCare.
            </p>
        </div>

        <div class="space-y-4">
            <!-- FAQ 1 -->
            <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full flex justify-between items-center p-6 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                    <span>Apakah donatur bisa mendonasikan makanan langsung?</span>
                    <i class="fa-solid" :class="activeFaq === 1 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 1" x-collapse class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 bg-slate-50/30">
                    Saat ini platform FoodCare berfokus pada donasi dana tunai agar pembelanjaan pangan bisa distandarisasi, diaudit, serta dikelola secara terpusat di gudang logistik kami untuk menjaga kesegaran bahan pangan pokok.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full flex justify-between items-center p-6 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                    <span>Bagaimana metode FEFO dijalankan dalam penyaluran?</span>
                    <i class="fa-solid" :class="activeFaq === 2 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 2" x-collapse class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 bg-slate-50/30">
                    FEFO (First Expired First Out) adalah aturan gudang di mana stok makanan yang memiliki masa kedaluwarsa paling dekat wajib didistribusikan terlebih dahulu. Sistem kami secara otomatis mengurutkan dan memilih stok paling kritis saat relawan membuat rute distribusi.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full flex justify-between items-center p-6 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                    <span>Bagaimana saya tahu uang donasi saya benar-benar dibelikan makanan?</span>
                    <i class="fa-solid" :class="activeFaq === 3 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 3" x-collapse class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 bg-slate-50/30">
                    Anda dapat memantau Tab "Pembelian Pangan" pada halaman Transparansi di Beranda ini. Setiap nota pembelian di-input secara akurat oleh Admin, lengkap dengan jumlah barang, harga satuan, dan total harga belanjaan logistik yang bersumber langsung dari rekening donasi.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                <button @click="activeFaq = activeFaq === 4 ? null : 4" class="w-full flex justify-between items-center p-6 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                    <span>Siapa saja yang berhak menerima bantuan pangan?</span>
                    <i class="fa-solid" :class="activeFaq === 4 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 4" x-collapse class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 bg-slate-50/30">
                    Penerima manfaat terdaftar di sistem meliputi panti asuhan, posko lansia sebatang kara, yayasan disabilitas, serta keluarga miskin perkotaan yang telah disurvei dan diverifikasi kelayakannya oleh tim relawan kami.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection