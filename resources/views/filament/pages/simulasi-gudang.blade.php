<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left: Form Input Column -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-100 dark:border-gray-700 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-50 dark:border-gray-700 pb-3 mb-5">
                    <i class="fa-solid fa-sliders text-emerald-600 dark:text-emerald-400 mr-2"></i> Parameter Simulasi
                </h3>

                <form wire:submit.prevent="runSimulation" class="space-y-5">
                    <!-- Jumlah Barang -->
                    <div>
                        <label for="jumlah_barang" class="block text-xs font-semibold text-slate-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Antrean Barang (Belum Disortir)</label>
                        <input type="number" wire:model="jumlah_barang" id="jumlah_barang" required
                            class="block w-full py-2.5 px-4 border border-slate-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40 text-slate-800 dark:text-white text-sm font-semibold">
                    </div>

                    <!-- Jumlah Relawan -->
                    <div>
                        <label for="jumlah_relawan" class="block text-xs font-semibold text-slate-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Relawan Aktif</label>
                        <input type="number" wire:model="jumlah_relawan" id="jumlah_relawan" required
                            class="block w-full py-2.5 px-4 border border-slate-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40 text-slate-800 dark:text-white text-sm font-semibold">
                    </div>

                    <!-- Kecepatan Sorting -->
                    <div>
                        <label for="sorting_rate" class="block text-xs font-semibold text-slate-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kapasitas Sorting Relawan (Unit/Jam/Orang)</label>
                        <input type="number" wire:model="sorting_rate" id="sorting_rate" required
                            class="block w-full py-2.5 px-4 border border-slate-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40 text-slate-800 dark:text-white text-sm font-semibold">
                    </div>

                    <!-- Kecepatan Packaging -->
                    <div>
                        <label for="packaging_rate" class="block text-xs font-semibold text-slate-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kapasitas Packaging Relawan (Unit/Jam/Orang)</label>
                        <input type="number" wire:model="packaging_rate" id="packaging_rate" required
                            class="block w-full py-2.5 px-4 border border-slate-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40 text-slate-800 dark:text-white text-sm font-semibold">
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-emerald-500 shadow-md shadow-emerald-100 dark:shadow-none transition-all cursor-pointer">
                        Hitung Bottleneck Gudang <i class="fa-solid fa-calculator ml-2 mt-0.5"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Results and History Column -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Simulation Results Card -->
            @if($lastSimulation)
                @php
                    $isBottleneck = $lastSimulation->status_bottleneck === 'Bottleneck';
                @endphp
                <div class="rounded-3xl border p-6 shadow-sm bg-white dark:bg-gray-900 space-y-5 transition-all {{ $isBottleneck ? 'border-rose-100 dark:border-rose-900/40' : 'border-emerald-100 dark:border-emerald-900/40' }}">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-50 dark:border-gray-700 pb-3">
                        <i class="fa-solid fa-square-poll-vertical text-emerald-600 dark:text-emerald-400 mr-2"></i> Hasil Simulasi Terakhir
                    </h3>

                    <!-- Status Banner -->
                    <div class="p-5 rounded-2xl flex items-center justify-between {{ $isBottleneck ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-800 dark:text-rose-300' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300' }}">
                        <div class="space-y-1">
                            <span class="text-xs uppercase font-bold tracking-wider opacity-70">Status Alur Logistik</span>
                            <h4 class="text-2xl font-black">{{ $lastSimulation->status_bottleneck }}</h4>
                        </div>
                        <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl {{ $isBottleneck ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' }}">
                            <i class="fa-solid {{ $isBottleneck ? 'fa-triangle-exclamation animate-bounce' : 'fa-circle-check' }}"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="p-4 bg-slate-50 dark:bg-gray-800 rounded-xl space-y-1">
                            <span class="text-slate-400 dark:text-gray-400 font-medium block">Total Antrean Barang</span>
                            <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ number_format($lastSimulation->jumlah_barang) }} Unit</span>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-gray-800 rounded-xl space-y-1">
                            <span class="text-slate-400 dark:text-gray-400 font-medium block">Jumlah Relawan</span>
                            <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ number_format($lastSimulation->jumlah_relawan) }} Orang</span>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-gray-800 rounded-xl space-y-1">
                            <span class="text-slate-400 dark:text-gray-400 font-medium block">Kapasitas Sorting / Jam</span>
                            <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ number_format($lastSimulation->kapasitas_sorting) }} Unit/Jam</span>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-gray-800 rounded-xl space-y-1">
                            <span class="text-slate-400 dark:text-gray-400 font-medium block">Kapasitas Packaging / Jam</span>
                            <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ number_format($lastSimulation->kapasitas_packaging) }} Unit/Jam</span>
                        </div>
                    </div>

                    <!-- Informational advice -->
                    <p class="text-xs leading-relaxed {{ $isBottleneck ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-700 dark:text-emerald-400' }}">
                        @if($isBottleneck)
                            <strong>Saran Logistik:</strong> Kapasitas sorting per jam lebih kecil dibandingkan jumlah antrean barang mentah yang belum disortir. Harap rekrut/tugaskan relawan tambahan untuk mempercepat penyelesaian dan menghindari kebusukan bahan pangan pokok.
                        @else
                            <strong>Saran Logistik:</strong> Alur logistik dalam kondisi aman dan optimal! Jumlah relawan dan kecepatan kerja memadai untuk memproses antrean barang dalam batas waktu yang aman.
                        @endif
                    </p>
                </div>
            @else
                <div class="p-8 text-center text-slate-400 dark:text-gray-500 bg-white dark:bg-gray-900 rounded-3xl border border-slate-100 dark:border-gray-700 shadow-sm">
                    <i class="fa-solid fa-chart-line text-4xl mb-3 block text-slate-300 dark:text-gray-600"></i>
                    Belum ada data simulasi yang dijalankan. Masukkan parameter di sebelah kiri lalu klik tombol.
                </div>
            @endif

            <!-- History Logs Table -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-100 dark:border-gray-700 p-6 shadow-sm overflow-hidden">
                <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-50 dark:border-gray-700 pb-3 mb-5">
                    <i class="fa-solid fa-history text-emerald-600 dark:text-emerald-400 mr-2"></i> Log Riwayat Simulasi
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-gray-700 text-xs">
                        <thead>
                            <tr class="text-left font-bold text-slate-500 dark:text-gray-400 bg-slate-50 dark:bg-gray-800">
                                <th class="px-4 py-3 rounded-l-xl">Waktu</th>
                                <th class="px-4 py-3 text-center">Barang</th>
                                <th class="px-4 py-3 text-center">Relawan</th>
                                <th class="px-4 py-3 text-center">Sorting</th>
                                <th class="px-4 py-3 text-center">Packaging</th>
                                <th class="px-4 py-3 text-right rounded-r-xl">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-gray-700 text-slate-600 dark:text-gray-300">
                            @forelse($history as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-3 font-semibold">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item->jumlah_barang) }}</td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item->jumlah_relawan) }}</td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item->kapasitas_sorting) }}/h</td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item->kapasitas_packaging) }}/h</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase {{ $item->status_bottleneck === 'Bottleneck' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' }}">
                                            {{ $item->status_bottleneck }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-400 dark:text-gray-500">Belum ada riwayat simulasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>