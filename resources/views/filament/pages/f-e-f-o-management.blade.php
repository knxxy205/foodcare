<x-filament-panels::page>
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Banner --}}
        <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;">
            <div style="width:40px;height:40px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-clock-rotate-left" style="color:#16a34a;font-size:16px;"></i>
            </div>
            <div>
                <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">FEFO — First Expired, First Out</p>
                <p style="font-size:12px;color:#64748b;margin:4px 0 0;line-height:1.5;">Pangan dengan masa simpan terpendek diprioritaskan untuk distribusi.</p>
            </div>
        </div>

        {{-- Grid Cards --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
            @foreach($stocks as $stock)
                @php
                    $days = (int) now()->diffInDays($stock->tanggal_kadaluarsa, false);
                    $isExpired = $days <= 0;

                    if ($isExpired) {
                        $cardBorder  = '2px solid #f43f5e';
                        $badgeBg     = '#fff1f2';
                        $badgeColor  = '#be123c';
                        $badgeText   = 'Kedaluwarsa';
                        $shelfBg     = '#fff1f2';
                        $shelfColor  = '#be123c';
                    } elseif ($days <= 30) {
                        $cardBorder  = '2px solid #fb7185';
                        $badgeBg     = '#fff1f2';
                        $badgeColor  = '#e11d48';
                        $badgeText   = 'Sangat Kritis';
                        $shelfBg     = '#fff1f2';
                        $shelfColor  = '#e11d48';
                    } elseif ($days <= 60) {
                        $cardBorder  = '2px solid #fbbf24';
                        $badgeBg     = '#fef3c7';
                        $badgeColor  = '#b45309';
                        $badgeText   = 'Kritis < 60 hari';
                        $shelfBg     = '#fef9c3';
                        $shelfColor  = '#b45309';
                    } else {
                        $cardBorder  = '2px solid #4ade80';
                        $badgeBg     = '#f0fdf4';
                        $badgeColor  = '#15803d';
                        $badgeText   = 'Aman';
                        $shelfBg     = '#f0fdf4';
                        $shelfColor  = '#15803d';
                    }
                @endphp

                <div style="background:#1e293b;border:{{ $cardBorder }};border-radius:16px;padding:20px;display:flex;flex-direction:column;gap:16px;">

                    {{-- Header --}}
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                        <div style="min-width:0;">
                            <p style="font-size:16px;font-weight:700;color:#f1f5f9;margin:0;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $stock->nama_barang }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;font-family:monospace;">#ST-{{ str_pad($stock->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span style="background:{{ $badgeBg }};color:{{ $badgeColor }};font-size:11px;font-weight:700;padding:4px 12px;border-radius:99px;white-space:nowrap;flex-shrink:0;">
                            {{ $badgeText }}
                        </span>
                    </div>

                    <div style="height:1px;background:#334155;"></div>

                    {{-- Dates --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div style="background:#0f172a;border-radius:10px;padding:12px 14px;">
                            <p style="font-size:11px;color:#64748b;margin:0 0 4px;">Masuk gudang</p>
                            <p style="font-size:13px;font-weight:700;color:#e2e8f0;margin:0;">{{ $stock->tanggal_masuk->format('d M Y') }}</p>
                        </div>
                        <div style="background:#0f172a;border-radius:10px;padding:12px 14px;">
                            <p style="font-size:11px;color:#64748b;margin:0 0 4px;">Kadaluarsa</p>
                            <p style="font-size:13px;font-weight:700;color:#e2e8f0;margin:0;">{{ $stock->tanggal_kadaluarsa->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div style="height:1px;background:#334155;"></div>

                    {{-- Stock Levels --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#94a3b8;">Belum disortir</span>
                            <span style="font-size:12px;font-weight:600;color:#e2e8f0;">{{ $stock->raw_stock }} unit</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#94a3b8;">Belum dikemas</span>
                            <span style="font-size:12px;font-weight:600;color:#e2e8f0;">{{ $stock->sorted_stock }} unit</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#94a3b8;">Siap penyaluran</span>
                            <span style="font-size:12px;font-weight:600;color:#4ade80;">{{ $stock->packaged_stock }} unit</span>
                        </div>
                    </div>

                    {{-- Shelf Life --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;background:{{ $shelfBg }};border-radius:10px;padding:10px 14px;">
                        <span style="font-size:12px;font-weight:600;color:{{ $shelfColor }};">Sisa masa simpan</span>
                        <span style="font-size:12px;font-weight:700;color:{{ $shelfColor }};">{{ $isExpired ? 'Habis' : $days . ' hari lagi' }}</span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>