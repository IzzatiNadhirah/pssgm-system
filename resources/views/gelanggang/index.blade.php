<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Gelanggangs</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        .badge-active { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; }
        .empty-state { background-color: #e9ecef; padding: 40px; text-align: center; border-radius: 8px; color: #6c757d; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 style="margin-top: 0;">Active Gelanggang Directory</h2>
        <p style="color: #666;">This list contains all currently approved and operational training centers.</p>
        
        @if($activeGelanggangs->isEmpty())
            <div class="empty-state">
                <p style="margin-top: 0;">There are no active Gelanggangs yet. Approve pending applications to see them here.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Gelanggang Code</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Cawangan</th>
                            <th>Instructor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeGelanggangs as $gelanggang)
                        <tr>
                            <td><b>{{ $gelanggang->gel_code ?? 'Pending Trigger' }}</b></td> 
                            
                            <td>{{ $gelanggang->gel_name }}</td>
                            <td>{{ $gelanggang->gel_address }}</td>
                            <td>{{ $gelanggang->cawangan->caw_code ?? $gelanggang->caw_ID }}</td>
                            <td>{{ $gelanggang->instructor->instructor_code ?? $gelanggang->instructor_ID }}</td>
                            <td><span class="badge-active">Active</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('staff.dashboard') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">&larr; Back to Admin Dashboard</a>
        </div>

    </div>
</body>
</html>