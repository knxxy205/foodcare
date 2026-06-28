<x-filament-panels::page>
    <style>
        .fc-route-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .fc-route-status {
            align-items: flex-start;
            background: #151515;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding: 22px;
        }

        .fc-route-status.is-ready {
            border-color: rgba(34, 197, 94, .28);
            box-shadow: inset 4px 0 0 #22c55e;
        }

        .fc-route-status.is-locked {
            border-color: rgba(244, 63, 94, .28);
            box-shadow: inset 4px 0 0 #f43f5e;
        }

        .fc-route-status-content {
            align-items: flex-start;
            display: flex;
            gap: 14px;
            min-width: 0;
        }

        .fc-route-icon {
            align-items: center;
            border-radius: 12px;
            display: flex;
            flex: 0 0 auto;
            height: 44px;
            justify-content: center;
            width: 44px;
        }

        .fc-route-icon.is-ready {
            background: rgba(34, 197, 94, .12);
            color: #4ade80;
        }

        .fc-route-icon.is-locked {
            background: rgba(244, 63, 94, .12);
            color: #fb7185;
        }

        .fc-route-status h3,
        .fc-route-card h3 {
            color: #f8fafc;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.25;
            margin: 0;
        }

        .fc-route-status p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
            margin: 6px 0 0;
            max-width: 980px;
        }

        .fc-route-status a {
            color: #fda4af;
            font-weight: 800;
            text-decoration: underline;
        }

        .fc-route-button {
            align-items: center;
            background: #22c55e;
            border: 0;
            border-radius: 10px;
            color: #04130a;
            cursor: pointer;
            display: inline-flex;
            flex: 0 0 auto;
            font-size: 14px;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            white-space: nowrap;
        }

        .fc-route-button:hover {
            background: #4ade80;
        }

        .fc-route-grid {
            display: grid;
            gap: 24px;
            grid-template-columns: minmax(0, 1fr);
        }

        .fc-route-card {
            background: #151515;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            overflow: hidden;
        }

        .fc-route-card-header {
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            gap: 10px;
            justify-content: space-between;
            padding: 16px 18px;
        }

        .fc-route-card-title {
            align-items: center;
            display: flex;
            gap: 10px;
            min-width: 0;
        }

        .fc-route-card-title i {
            color: #4ade80;
        }

        .fc-route-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .fc-route-link {
            align-items: center;
            background: rgba(34, 197, 94, .12);
            border: 1px solid rgba(34, 197, 94, .24);
            border-radius: 9px;
            color: #86efac;
            display: inline-flex;
            font-size: 13px;
            font-weight: 800;
            gap: 8px;
            min-height: 36px;
            padding: 0 12px;
            text-decoration: none;
        }

        .fc-route-link:hover {
            background: rgba(34, 197, 94, .18);
            color: #bbf7d0;
        }

        .fc-route-summary {
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            padding: 18px;
        }

        .fc-route-metric {
            background: rgba(255, 255, 255, .035);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 12px;
            padding: 14px;
        }

        .fc-route-metric span {
            color: #a1a1aa;
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .fc-route-metric strong {
            color: #f8fafc;
            display: block;
            font-size: 22px;
            line-height: 1.1;
            margin-top: 7px;
        }

        .fc-route-list {
            padding: 20px 18px 22px;
        }

        .fc-route-timeline {
            display: flex;
            flex-direction: column;
            gap: 18px;
            position: relative;
        }

        .fc-route-timeline::before {
            background: rgba(255, 255, 255, .12);
            bottom: 18px;
            content: "";
            left: 17px;
            position: absolute;
            top: 18px;
            width: 2px;
        }

        .fc-route-stop {
            display: grid;
            gap: 12px;
            grid-template-columns: 36px minmax(0, 1fr);
            position: relative;
        }

        .fc-route-marker {
            align-items: center;
            background: #22c55e;
            border: 3px solid #151515;
            border-radius: 999px;
            color: #06130a;
            display: flex;
            font-size: 13px;
            font-weight: 900;
            height: 36px;
            justify-content: center;
            position: relative;
            width: 36px;
            z-index: 1;
        }

        .fc-route-marker.is-warehouse {
            background: #f43f5e;
            color: #fff;
        }

        .fc-route-stop-body {
            min-width: 0;
            padding-top: 2px;
        }

        .fc-route-stop-top {
            align-items: flex-start;
            display: flex;
            gap: 12px;
            justify-content: space-between;
        }

        .fc-route-stop h4 {
            color: #f8fafc;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.35;
            margin: 0;
        }

        .fc-route-stop p,
        .fc-route-phone {
            color: #a1a1aa;
            display: block;
            font-size: 13px;
            line-height: 1.45;
            margin: 5px 0 0;
        }

        .fc-route-distance {
            background: rgba(34, 197, 94, .12);
            border: 1px solid rgba(34, 197, 94, .2);
            border-radius: 999px;
            color: #86efac;
            flex: 0 0 auto;
            font-size: 12px;
            font-weight: 800;
            padding: 4px 9px;
            white-space: nowrap;
        }

        @media (max-width: 1100px) {
            .fc-route-grid {
                grid-template-columns: 1fr;
            }

            .fc-route-summary {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .fc-route-status {
                flex-direction: column;
            }

            .fc-route-button {
                width: 100%;
            }

            .fc-route-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .fc-route-status h3,
            .fc-route-card h3 {
                font-size: 18px;
            }
        }
    </style>

    <div class="fc-route-page">
        @if(!$isOptimal)
            <div class="fc-route-status is-locked">
                <div class="fc-route-status-content">
                    <div class="fc-route-icon is-locked">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div>
                        <h3>Optimalisasi Rute Terkunci</h3>
                        <p>
                            Generate Route hanya diperbolehkan jika status bottleneck gudang adalah <strong>Optimal</strong>.
                            Saat ini status gudang masih <strong>Bottleneck</strong> atau simulasi belum dijalankan.
                            Sesuaikan jumlah relawan atau antrean barang di menu
                            <a href="/admin/simulasi-gudang">Simulasi Gudang</a> terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="fc-route-status is-ready">
                <div class="fc-route-status-content">
                    <div class="fc-route-icon is-ready">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                    <div>
                        <h3>Optimalisasi Rute Siap Dijalankan</h3>
                        <p>
                            Alur gudang dalam status optimal. Kapasitas relawan memadai untuk menyiapkan bantuan,
                            sehingga pengiriman dapat direncanakan memakai urutan titik terdekat.
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="generateRoute" class="fc-route-button">
                    Generate Rute Distribusi
                    <i class="fa-solid fa-map-location-dot"></i>
                </button>
            </div>
        @endif

        @if($routeGenerated)
            @php
                $googleMapsPoints = collect($optimizedRoute)
                    ->filter(fn ($stop) => !empty($stop['latitude']) && !empty($stop['longitude']))
                    ->map(fn ($stop) => $stop['latitude'] . ',' . $stop['longitude'])
                    ->values();

                $googleMapsUrl = 'https://www.google.com/maps/dir/' . $warehouseLat . ',' . $warehouseLng;

                if ($googleMapsPoints->isNotEmpty()) {
                    $googleMapsUrl .= '/' . $googleMapsPoints->implode('/');
                }

                $totalDistance = collect($optimizedRoute)->sum('distance');
            @endphp

            <div class="fc-route-grid">
                <section class="fc-route-card">
                    <div class="fc-route-card-header">
                        <div class="fc-route-card-title">
                            <i class="fa-solid fa-route"></i>
                            <h3>Urutan Penyaluran Terpendek</h3>
                        </div>
                        <div class="fc-route-toolbar">
                            <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener" class="fc-route-link">
                                <i class="fa-solid fa-map-location-dot"></i>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>

                    <div class="fc-route-summary">
                        <div class="fc-route-metric">
                            <span>Titik Penyaluran</span>
                            <strong>{{ count($optimizedRoute) }}</strong>
                        </div>
                        <div class="fc-route-metric">
                            <span>Estimasi Jarak</span>
                            <strong>{{ number_format($totalDistance, 2) }} km</strong>
                        </div>
                        <div class="fc-route-metric">
                            <span>Metode</span>
                            <strong>Nearest</strong>
                        </div>
                    </div>

                    <div class="fc-route-list">
                        <div class="fc-route-timeline">
                            <div class="fc-route-stop">
                                <div class="fc-route-marker is-warehouse">
                                    <i class="fa-solid fa-warehouse"></i>
                                </div>
                                <div class="fc-route-stop-body">
                                    <h4>Hub Utama FoodCare (Gudang)</h4>
                                    <p>Jl. Riau No. 101, Bandung (Titik Mulai)</p>
                                </div>
                            </div>

                            @foreach($optimizedRoute as $stop)
                                <div class="fc-route-stop">
                                    <div class="fc-route-marker">{{ $stop['step'] }}</div>
                                    <div class="fc-route-stop-body">
                                        <div class="fc-route-stop-top">
                                            <h4>{{ $stop['nama_penerima'] }}</h4>
                                            <span class="fc-route-distance">+{{ $stop['distance'] }} km</span>
                                        </div>
                                        <p>{{ $stop['alamat'] }}</p>
                                        <span class="fc-route-phone">
                                            <i class="fa-solid fa-phone"></i>
                                            {{ $stop['kontak'] ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>
</x-filament-panels::page>
