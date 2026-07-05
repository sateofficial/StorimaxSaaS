<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Crew</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }
        h1 { color: #2563eb; font-size: 18px; margin-bottom: 4px; }
        .tagline { color: #999; font-size: 10px; margin-top: 0; margin-bottom: 20px; }
        .subtitle { font-size: 13px; color: #666; margin-bottom: 16px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f3f4f6; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; color: #666; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }

        .summary-grid { display: flex; gap: 12px; margin-bottom: 20px; }
        .summary-item {
            flex: 1; border: 1px solid #e5e7eb; border-radius: 6px;
            padding: 10px 14px; text-align: center;
        }
        .summary-item .number { font-size: 20px; font-weight: bold; color: #111; }
        .summary-item .label { font-size: 10px; color: #888; margin-top: 2px; }

        .footer { margin-top: 24px; font-size: 9px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
    <h1>STORIMAX</h1>
    <p class="tagline">Story in Motion. Maxed to Perfection.</p>
    <p class="subtitle">Laporan Kinerja Crew — {{ now()->format('d M Y') }}</p>

    {{-- Summary --}}
    <div class="summary-grid">
        <div class="summary-item">
            <div class="number">{{ $crewMembers->count() }}</div>
            <div class="label">Total Crew</div>
        </div>
        <div class="summary-item">
            <div class="number">{{ $totalJobs }}</div>
            <div class="label">Total Job</div>
        </div>
        <div class="summary-item">
            <div class="number">{{ $doneJobs }}</div>
            <div class="label">Job Done</div>
        </div>
        <div class="summary-item">
            <div class="number">{{ $completionRate }}%</div>
            <div class="label">Completion Rate</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>Crew</th>
                <th>Departemen</th>
                <th class="text-center">Total</th>
                <th class="text-center">To Do</th>
                <th class="text-center">Progress</th>
                <th class="text-center">Review</th>
                <th class="text-center">Done</th>
                <th class="text-center">Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($crewMembers as $crew)
            <tr>
                <td class="bold">{{ $crew->name }}</td>
                <td>{{ $crew->department?->name ?? '-' }}</td>
                <td class="text-center">{{ $crew->jobs_count }}</td>
                <td class="text-center">{{ $crew->todo_count }}</td>
                <td class="text-center">{{ $crew->inprogress_count }}</td>
                <td class="text-center">{{ $crew->review_count }}</td>
                <td class="text-center">{{ $crew->done_count }}</td>
                <td class="text-center bold">{{ $crew->completion_rate }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #999; padding: 20px;">
                    Belum ada data crew.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }} — Storimax Agency Management System
    </div>
</body>
</html>
