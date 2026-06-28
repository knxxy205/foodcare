@extends('layouts/app')

@section('title', 'Daftar Donatur - FoodCare')

@section('content')
<div class="min-h-[calc(100vh-80px-344px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 relative overflow-hidden">
    <!-- Background Gradient Blobs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-emerald-100 blur-3xl opacity-50"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-teal-100 blur-3xl opacity-50"></div>

    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-slate-100 shadow-xl shadow-slate-100 relative z-10">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Gabung Sebagai Donatur</h2>
            <p class="mt-2.5 text-sm text-slate-500">
                Buat akun untuk mulai berdonasi pangan dan memantau dampak donasi Anda.
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-5" action="/register" method="POST">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="Nama Lengkap Anda">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="0812xxxxxxxx">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Rumah</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 pt-3.5 flex items-start text-slate-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <textarea id="address" name="address" rows="3"
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="Alamat lengkap Anda">{{ old('address') }}</textarea>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" name="password" type="password" required
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-circle-check"></i>
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                        placeholder="Ketik ulang kata sandi">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-100 transition-all cursor-pointer">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-500">
                Sudah punya akun? 
                <a href="/login" class="font-bold text-emerald-600 hover:text-emerald-500 transition-colors">Masuk Di Sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
