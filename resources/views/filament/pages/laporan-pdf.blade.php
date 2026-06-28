<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 11px;
            line-height: 1.45;
        }

        .header {
            border-bottom: 3px solid #16a34a;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .brand {
            color: #16a34a;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 4px 0 8px;
            font-size: 22px;
            line-height: 1.2;
        }

        .meta {
            color: #4b5563;
            font-size: 10px;
        }

        .summary {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-bottom: 16px;
            padding: 12px 14px;
        }

        .summary-label {
            color: #166534;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .summary-value {
            color: #052e16;
            font-size: 18px;
            font-weight: 800;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #111827;
            color: #ffffff;
            font-size: 9px;
            letter-spacing: .04em;
            padding: 8px 7px;
            text-align: left;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background: #f9fafb;
        }

        .empty {
            border: 1px dashed #d1d5db;
            color: #6b7280;
            padding: 28px;
            text-align: center;
        }

        .footer {
            color: #6b7280;
            font-size: 9px;
            margin-top: 18px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">FoodCare Platform</div>
        <h1>{{ $title }}</h1>
        <div class="meta">
            Periode {{ $startDate }} sampai {{ $endDate }} &middot;
            Dibuat {{ $generatedAt }} &middot;
            Total data {{ count($rows) }}
        </div>
    </div>

    <div class="summary">
        <div class="summary-label">{{ $summary['label'] }}</div>
        <div class="summary-value">{{ $summary['value'] }}</div>
    </div>

    @if(empty($rows))
        <div class="empty">Tidak ditemukan data untuk rentang tanggal yang dipilih.</div>
    @else
        <table>
            <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh sistem FoodCare.
    </div>
</body>
</html>
