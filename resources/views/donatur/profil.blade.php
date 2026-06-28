@extends('layouts/app')

@section('title', 'Profil Saya - FoodCare')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100 overflow-hidden">
        
        <!-- Header banner -->
        <div class="bg-gradient-to-r from-emerald-800 to-teal-700 p-8 text-white relative">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_120%,rgba(16,185,129,0.15),transparent_50%)]"></div>
            <div class="relative z-10 flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">Pengaturan Profil</h1>
                    <p class="text-emerald-100 text-sm mt-1">
                        Kelola data pribadi Anda, kata sandi, dan alamat untuk penyaluran kuitansi atau donasi fisik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Body Form -->
        <div class="p-8 sm:p-10">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm mb-8 flex items-center gap-3.5 shadow-sm">
                    <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-sm mb-8">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('donatur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                            class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm font-medium">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                            class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm font-medium">
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm font-medium"
                            placeholder="0812xxxxxxxx">
                    </div>

                    <!-- Role (Readonly) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-500 mb-1.5">Tipe Akun (Tidak Dapat Diubah)</label>
                        <input type="text" disabled value="{{ ucfirst($user->role) }}"
                            class="block w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-400 text-sm font-medium capitalize">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Rumah</label>
                    <textarea name="address" id="address" rows="3"
                        class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm font-medium"
                        placeholder="Alamat Lengkap">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="border-t border-slate-100 pt-8 mt-8">
                    <h3 class="text-base font-bold text-slate-800 mb-4"><i class="fa-solid fa-key text-emerald-600 mr-1.5"></i> Ubah Kata Sandi (Kosongkan jika tidak ingin diubah)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password"
                                class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                                placeholder="Minimal 8 karakter">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full py-3 px-4 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 text-slate-800 transition-all text-sm"
                                placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="/donasi-saya" class="px-6 py-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-bold transition-all">
                        Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white text-sm font-bold shadow-lg shadow-emerald-100 transition-all cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
