<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Gelanggang Approvals</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        
        .btn { padding: 8px 12px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; transition: 0.2s; }
        .btn-approve { background-color: #28a745; color: white; }
        .btn-approve:hover { background-color: #218838; }
        .btn-reject { background-color: #dc3545; color: white; }
        .btn-reject:hover { background-color: #c82333; }
        
        .badge { background-color: #ffc107; color: #212529; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; }
        .empty-state { background-color: #e9ecef; padding: 40px; text-align: center; border-radius: 8px; color: #6c757d; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 style="margin-top: 0;">Review Pending Gelanggang Applications</h2>
        <p style="color: #666;">Approve or reject training center registrations submitted by branch managers.</p>
        
        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        @if($pendingGelanggangs->isEmpty())
            <div class="empty-state">
                <h3 style="margin-bottom: 5px;">All caught up!</h3>
                <p style="margin-top: 0;">There are no pending Gelanggang applications at this time.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Cawangan ID</th>
                            <th>Instructor ID</th>
                            <th>Status</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingGelanggangs as $gelanggang)
                        <tr>
                            <td><b>{{ $gelanggang->gel_name }}</b></td>
                            <td>{{ $gelanggang->gel_address }}</td>
                            
                            <td>{{ $gelanggang->cawangan->caw_code ?? $gelanggang->caw_ID }}</td>
                            <td>{{ $gelanggang->instructor->instructor_code ?? $gelanggang->instructor_ID }}</td>
                            
                            <td><span class="badge">Pending</span></td>
                            <td style="display: flex; gap: 10px; justify-content: center;">
                                
                                <form action="{{ route('gelanggangs.approve', $gelanggang->gel_ID) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-approve" onclick="return confirm('Approve this Gelanggang? It will become active immediately.');">
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('gelanggangs.reject', $gelanggang->gel_ID) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-reject" onclick="return confirm('Are you sure you want to REJECT this application?');">
                                        Reject
                                    </button>
                                </form>

                            </td>
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