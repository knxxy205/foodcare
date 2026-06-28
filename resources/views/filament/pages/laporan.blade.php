<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Filter Controls Card -->
        <div style="background-color:#181818; border-radius:1rem; border:1px solid rgba(255,255,255,0.05); padding:1.5rem;" class="no-print">
            <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:1rem; margin-bottom:1.5rem;">
                <h3 style="font-size:0.875rem; font-weight:700; color:#ffffff; text-transform:uppercase; letter-spacing:0.05em;">
                    <i class="fa-solid fa-filter" style="color:#1DB954; margin-right:0.5rem;"></i> Filter Laporan
                </h3>
                <span style="font-size:10px; font-family:monospace; color:#6b7280; background-color:rgba(255,255,255,0.05); padding:0.25rem 0.5rem; border-radius:0.25rem;">FILTER</span>
            </div>

            <form wire:submit.prevent="generate" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <!-- Report Type -->
                <div>
                    <label for="reportType" style="display:block; font-size:0.75rem; font-weight:500; color:#9ca3af; margin-bottom:0.5rem;">Tipe Laporan</label>
                    <select wire:model="reportType" id="reportType" required
                        style="background-color:#0e0e0e; border:1px solid rgba(255,255,255,0.1); color:#ffffff; border-radius:0.5rem; padding:0.625rem 1rem; width:100%; font-size:0.875rem; font-weight:500; cursor:pointer; appearance:none; background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%239ca3af'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-repeat:no-repeat; background-position:right 0.75rem center; background-size:1.25em;">
                        <option value="donasi" style="background-color:#0e0e0e; color:#ffffff;">Laporan Donasi Masuk</option>
                        <option value="pembelian" style="background-color:#0e0e0e; color:#ffffff;">Laporan Pembelian Pangan</option>
                        <option value="stok" style="background-color:#0e0e0e; color:#ffffff;">Laporan Stok Gudang</option>
                        <option value="distribusi" style="background-color:#0e0e0e; color:#ffffff;">Laporan Distribusi Bantuan</option>
                        <option value="relawan" style="background-color:#0e0e0e; color:#ffffff;">Laporan Aktivitas Relawan</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="startDate" class="block text-xs font-medium text-gray-400 mb-2">Tanggal Mulai</label>
                    <input type="date" wire:model="startDate" id="startDate" required
                        style="background-color:#0e0e0e; border:1px solid rgba(255,255,255,0.1); color:#ffffff; border-radius:0.5rem; padding:0.625rem 1rem; width:100%; font-size:0.875rem; font-weight:500; color-scheme:dark;">
                </div>

                <!-- End Date -->
                <div>
                    <label for="endDate" class="block text-xs font-medium text-gray-400 mb-2">Tanggal Selesai</label>
                    <input type="date" wire:model="endDate" id="endDate" required
                        style="background-color:#0e0e0e; border:1px solid rgba(255,255,255,0.1); color:#ffffff; border-radius:0.5rem; padding:0.625rem 1rem; width:100%; font-size:0.875rem; font-weight:500; color-scheme:dark;">
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        style="flex-grow:1; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.625rem 1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:700; color:#000000; background-color:#1DB954; cursor:pointer; border:none; transition:background-color 0.15s;"
                        onmouseover="this.style.backgroundColor='#1ed760'" onmouseout="this.style.backgroundColor='#1DB954'">
                        Tampilkan <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    @if($isGenerated && !empty($reportData))
                        <button type="button" wire:click="exportExcel"
                            style="padding:0.625rem; border-radius:0.5rem; border:1px solid rgba(255,255,255,0.1); background-color:transparent; color:#d1d5db; cursor:pointer;" title="Export Excel CSV">
                            <i class="fa-solid fa-file-excel text-base"></i>
                        </button>
                        <button type="button" wire:click="exportPdf"
                            style="padding:0.625rem; border-radius:0.5rem; border:1px solid rgba(255,255,255,0.1); background-color:transparent; color:#d1d5db; cursor:pointer;" title="Export PDF">
                            <i class="fa-solid fa-file-pdf text-base"></i>
                        </button>
                        <button type="button" onclick="window.print()"
                            style="padding:0.625rem; border-radius:0.5rem; border:1px solid rgba(255,255,255,0.1); background-color:transparent; color:#d1d5db; cursor:pointer;" title="Cetak Laporan">
                            <i class="fa-solid fa-print text-base"></i>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Print-only Header -->
        <div class="hidden print:block text-center border-b pb-6 mb-8 space-y-2">
            <h1 class="text-2xl font-black text-slate-900">FoodCare Platform</h1>
            <p class="text-sm text-slate-500">Laporan Konsolidasi Operasional & Finansial</p>
            <div class="text-xs text-slate-400">
                Kategori: <span class="capitalize font-bold">{{ $reportType }}</span> | Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
        </div>

        <!-- Report Results -->
        @if($isGenerated)
            <div class="bg-[#181818] rounded-2xl border border-white/5 overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-white/5">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wide">
                        <i class="fa-solid fa-list-check text-[#1DB954] mr-2"></i> Hasil Laporan
                    </h3>
                    <span class="text-[10px] font-mono text-gray-500">{{ count($reportData) }} rekord</span>
                </div>

                @if(empty($reportData))
                    <div class="py-16 text-center">
                        <i class="fa-solid fa-circle-exclamation text-3xl text-gray-700 mb-4 block"></i>
                        <p class="text-sm text-gray-500">Tidak ditemukan data untuk rentang tanggal yang dipilih.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <!-- Tipe Laporan: DONASI -->
                        @if($reportType === 'donasi')
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b border-white/5">
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Tanggal</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Donatur</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Program Donasi</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Metode Pembayaran</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Status</th>
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px] text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @php $total = 0; @endphp
                                    @foreach($reportData as $row)
                                        @php if ($row['status'] === 'success') $total += $row['jumlah']; @endphp
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-3 text-gray-400 font-mono">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-3 font-semibold text-white">{{ $row['user']['name'] ?? 'Donatur' }}</td>
                                            <td class="px-4 py-3 text-gray-300 truncate max-w-xs">{{ $row['program']['nama_program'] ?? '' }}</td>
                                            <td class="px-4 py-3 text-gray-300">{{ $row['metode_pembayaran'] }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $row['status'] === 'success' ? 'bg-[#1DB954]/10 text-[#1DB954]' : ($row['status'] === 'pending' ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400') }}">
                                                    {{ $row['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-right font-bold text-white font-mono">Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-white/[0.03] font-bold text-white border-t border-white/10">
                                        <td colspan="5" class="px-6 py-3 text-right">Total Donasi Sukses</td>
                                        <td class="px-6 py-3 text-right text-[#1DB954] font-mono">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        <!-- Tipe Laporan: PEMBELIAN PANGAN -->
                        @elseif($reportType === 'pembelian')
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b border-white/5">
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Tanggal</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Nama Barang</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Jumlah (Unit)</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Harga Satuan</th>
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px] text-right">Total Belanja</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @php $total = 0; @endphp
                                    @foreach($reportData as $row)
                                        @php $total += $row['total_harga']; @endphp
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-3 text-gray-400 font-mono">{{ \Carbon\Carbon::parse($row['tanggal_beli'])->format('d M Y') }}</td>
                                            <td class="px-4 py-3 font-semibold text-white">{{ $row['nama_barang'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-300 font-mono">{{ number_format($row['jumlah']) }}</td>
                                            <td class="px-4 py-3 text-gray-300 font-mono">Rp {{ number_format($row['harga_satuan'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-3 text-right font-bold text-white font-mono">Rp {{ number_format($row['total_harga'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-white/[0.03] font-bold text-white border-t border-white/10">
                                        <td colspan="4" class="px-6 py-3 text-right">Total Belanja Logistik</td>
                                        <td class="px-6 py-3 text-right text-rose-400 font-mono">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        <!-- Tipe Laporan: STOK GUDANG -->
                        @elseif($reportType === 'stok')
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b border-white/5">
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Nama Barang</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Stok Awal</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Belum Disortir</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Belum Dikemas</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center text-[#1DB954]">Siap Salurkan</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Tanggal Masuk</th>
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Kadaluarsa</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($reportData as $row)
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-3 font-semibold text-white">{{ $row['nama_barang'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-300 font-mono">{{ number_format($row['jumlah']) }}</td>
                                            <td class="px-4 py-3 text-center text-gray-300 font-mono">{{ $row['raw_stock'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-300 font-mono">{{ $row['sorted_stock'] }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-[#1DB954] font-mono">{{ $row['packaged_stock'] }}</td>
                                            <td class="px-4 py-3 text-gray-400 font-mono">{{ \Carbon\Carbon::parse($row['tanggal_masuk'])->format('d M Y') }}</td>
                                            <td class="px-6 py-3 font-semibold text-gray-300 font-mono">{{ \Carbon\Carbon::parse($row['tanggal_kadaluarsa'])->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        <!-- Tipe Laporan: DISTRIBUSI -->
                        @elseif($reportType === 'distribusi')
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b border-white/5">
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Tanggal</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Relawan</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Penerima Bantuan</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Paket</th>
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px] text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @php $total = 0; @endphp
                                    @foreach($reportData as $row)
                                        @php if ($row['status'] === 'selesai') $total += $row['jumlah_paket']; @endphp
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-3 text-gray-400 font-mono">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                                            <td class="px-4 py-3 font-semibold text-white">{{ $row['relawan']['name'] ?? 'Relawan' }}</td>
                                            <td class="px-4 py-3 font-semibold text-white">{{ $row['penerima']['nama_penerima'] ?? '' }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-gray-300 font-mono">{{ $row['jumlah_paket'] }}</td>
                                            <td class="px-6 py-3 text-right">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $row['status'] === 'selesai' ? 'bg-[#1DB954]/10 text-[#1DB954]' : ($row['status'] === 'pending' ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400') }}">
                                                    {{ $row['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-white/[0.03] font-bold text-white border-t border-white/10">
                                        <td colspan="3" class="px-6 py-3 text-right">Total Paket Berhasil Tersalurkan</td>
                                        <td class="px-4 py-3 text-center text-[#1DB954] font-mono">{{ $total }} Paket</td>
                                        <td class="px-6 py-3"></td>
                                    </tr>
                                </tbody>
                            </table>

                        <!-- Tipe Laporan: AKTIVITAS RELAWAN -->
                        @elseif($reportType === 'relawan')
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b border-white/5">
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px]">Nama Relawan</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Email</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px]">Kontak</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Mensortir (Unit)</th>
                                        <th class="px-4 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Mengemas (Paket)</th>
                                        <th class="px-6 py-3 font-medium uppercase tracking-wide text-[10px] text-center">Pengiriman (Titik)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($reportData as $row)
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-3 font-semibold text-white">{{ $row['name'] }}</td>
                                            <td class="px-4 py-3 text-gray-300">{{ $row['email'] }}</td>
                                            <td class="px-4 py-3 text-gray-300 font-mono">{{ $row['phone'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-gray-300 font-mono">{{ number_format($row['total_sorting']) }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-gray-300 font-mono">{{ number_format($row['total_packaging']) }}</td>
                                            <td class="px-6 py-3 text-center font-bold text-[#1DB954] font-mono">{{ number_format($row['total_delivery']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Print styling -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .fi-main {
                padding: 0 !important;
            }
            .fi-sidebar {
                display: none !important;
            }
            .fi-topbar {
                display: none !important;
            }
            .fi-header {
                display: none !important;
            }
            table {
                font-size: 10px !important;
            }
        }
    </style>
</x-filament-panels::page>