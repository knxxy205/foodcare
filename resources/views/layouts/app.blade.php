<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodCare - Platform Donasi Pangan')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        html {
            scroll-behavior: smooth;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-900 selection:bg-emerald-500 selection:text-white antialiased">

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-200 transition-all duration-300 group-hover:scale-110">
                            <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent group-hover:from-emerald-700">FoodCare</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-5 lg:gap-7">
                    <a href="/#beranda" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">Beranda</a>
                    <a href="/#program" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">Program Donasi</a>
                    <a href="/#dampak" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">Dampak</a>
                    <a href="{{ route('about') }}" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">Tentang Kami</a>
                    <a href="{{ route('faq') }}" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">FAQ</a>
                    
                    <div class="h-6 w-[1px] bg-slate-200 shrink-0"></div>

                    @auth
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ openProfile: false }" @click.outside="openProfile = false">
                            <button @click="openProfile = !openProfile" class="flex items-center gap-2 focus:outline-none cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 transition-all whitespace-nowrap">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-xs border border-emerald-500/20 shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="text-left hidden lg:block max-w-[100px]">
                                    <p class="text-xs font-bold text-slate-800 leading-none truncate">{{ auth()->user()->name }}</p>
                                    <span class="text-[9px] text-slate-400 capitalize font-semibold">{{ auth()->user()->role }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200 shrink-0" :class="openProfile ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openProfile" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2.5 w-52 rounded-2xl bg-white border border-slate-100 shadow-xl shadow-slate-200/50 py-2 z-50 text-xs"
                                 style="display: none;">
                                <div class="px-4 py-2.5 border-b border-slate-100 mb-1.5">
                                    <p class="font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->role === 'donatur')
                                    <a href="/donasi-saya" class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-semibold transition-colors">
                                        <i class="fa-solid fa-hand-holding-heart w-4 text-slate-400 text-sm"></i> Donasi Saya
                                    </a>
                                @endif
                                <a href="/profil" class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-semibold transition-colors">
                                    <i class="fa-solid fa-user-gear w-4 text-slate-400 text-sm"></i> Profil Saya
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="/admin" class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-semibold transition-colors">
                                        <i class="fa-solid fa-gauge w-4 text-slate-400 text-sm"></i> Admin Panel
                                    </a>
                                @endif
                                <div class="border-t border-slate-100 my-1.5"></div>
                                <form method="POST" action="/logout" class="block w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2 text-left text-rose-600 hover:bg-rose-50 font-bold transition-all cursor-pointer">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-sm"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="text-slate-600 hover:text-emerald-600 font-semibold text-sm transition-colors whitespace-nowrap">Masuk</a>
                        <a href="/register" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition-all whitespace-nowrap">Daftar</a>
                    @endauth

                    <a href="/#program" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold text-sm shadow-md shadow-emerald-100 transition-all hover:-translate-y-0.5 whitespace-nowrap shrink-0">Donasi Sekarang</a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none transition-colors">
                        <i class="fa-solid text-xl" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition class="md:hidden border-t border-slate-100 bg-white">
            <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
                <a href="/#beranda" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Beranda</a>
                <a href="/#program" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Program Donasi</a>
                <a href="/#dampak" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Dampak</a>
                <a href="{{ route('about') }}" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Tentang Kami</a>
                <a href="{{ route('faq') }}" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">FAQ</a>
                
                @auth
                    @if(auth()->user()->role === 'donatur')
                        <a href="/donasi-saya" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Donasi Saya</a>
                    @endif
                    <a href="/profil" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Profil</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="/admin" @click="open = false" class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-medium transition-all">Admin Panel</a>
                    @endif
                    <div class="border-t border-slate-100 my-2 pt-2">
                        <div class="px-3 py-2 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                <span class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</span>
                            </div>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg text-rose-500 hover:bg-rose-50 font-semibold transition-all">
                                    Logout <i class="fa-solid fa-arrow-right-from-bracket ml-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-t border-slate-100 my-2 pt-2 grid grid-cols-2 gap-2 px-3">
                        <a href="/login" @click="open = false" class="py-2.5 text-center rounded-lg text-slate-700 hover:bg-slate-50 font-semibold border border-slate-200">Masuk</a>
                        <a href="/register" @click="open = false" class="py-2.5 text-center rounded-lg bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">Daftar</a>
                    </div>
                @endauth
                <div class="px-3 pt-2">
                    <a href="/#program" @click="open = false" class="block text-center py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-bold shadow-md shadow-emerald-100">Donasi Sekarang</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-white">FoodCare</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        FoodCare adalah platform donasi pangan transparan, menyalurkan bantuan makanan dari donatur secara cepat dan akurat menggunakan FEFO & rute logistik optimal.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-base mb-4">Navigasi</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/#beranda" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="/#program" class="hover:text-white transition-colors">Program Donasi</a></li>
                        <li><a href="/#dampak" class="hover:text-white transition-colors">Dampak Sosial</a></li>
                        <li><a href="/#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-base mb-4">Metode FEFO</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Kami menjamin kualitas makanan bantuan dengan menyalurkan stok makanan dengan masa kadaluarsa terdekat terlebih dahulu (First Expired First Out).
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-base mb-4">Kontak Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-envelope mt-0.5 text-emerald-500"></i>
                            <span>support@foodcare.org</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-phone mt-0.5 text-emerald-500"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-location-dot mt-0.5 text-emerald-500"></i>
                            <span>Jl. Raya Jatiwaringin No.140, RT.001/RW.008, Kelurahan Jatiwaringin, Kecamatan Pondokgede, Kota Bekasi, Jawa Barat 17411</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <p>&copy; {{ date('Y') }} FoodCare. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-twitter text-lg"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-youtube text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>