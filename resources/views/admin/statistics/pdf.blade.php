<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistics Report</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }
        h1 { color: #111; font-size: 24px; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 30px; }
        .grid { width: 100%; margin-bottom: 30px; }
        .grid-item { width: 24%; display: inline-block; padding: 15px; box-sizing: border-box; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-right: 0.5%; vertical-align: top; }
        .metric-title { font-size: 10px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; font-weight: bold; margin-bottom: 10px; }
        .metric-value { font-size: 28px; font-weight: bold; color: #111; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .table th { background: #f3f4f6; font-size: 12px; text-transform: uppercase; color: #374151; }
        .table td { font-size: 14px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header p { color: #6b7280; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Executive Statistics Report</h1>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="grid">
        <div class="grid-item">
            <div class="metric-title">Total Volume</div>
            <div class="metric-value">{{ $totalTickets }}</div>
        </div>
        <div class="grid-item">
            <div class="metric-title">Performance</div>
            <div class="metric-value">{{ $resolutionRate }}%</div>
        </div>
        <div class="grid-item">
            <div class="metric-title">Audience</div>
            <div class="metric-value">{{ $totalUsers }}</div>
        </div>
        <div class="grid-item">
            <div class="metric-title">Backlog</div>
            <div class="metric-value">{{ ($statusCounts['open'] ?? 0) + ($statusCounts['in_progress'] ?? 0) }}</div>
        </div>
    </div>

    <h3 style="margin-top: 40px; font-size: 16px; color:#111;">Incidents by Department</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Current Load</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departmentStats as $dept)
                <tr>
                    <td>{{ $dept['name'] }}</td>
                    <td><strong>{{ $dept['count'] }}</strong> incidents</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
