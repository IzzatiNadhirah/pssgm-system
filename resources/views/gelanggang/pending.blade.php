<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Gelanggang Approvals - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #111; 
            margin: 0; 
            min-height: 100vh;
        }

        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1200px; 
            width: 100%;
            background: white; 
            padding: 35px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000;
            border-bottom: 8px solid #ffcc00;
        }
        
        .header-area {
            display: flex; align-items: center; gap: 15px;
            border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;
        }

        .header-area .material-icons { font-size: 36px; color: #cc0000; }
        .header-text h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        .header-text p { margin: 5px 0 0 0; color: #666; font-size: 0.9em; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }
        
        .btn { 
            padding: 8px 12px; border: none; cursor: pointer; border-radius: 6px; 
            font-weight: bold; text-decoration: none; display: inline-flex; 
            align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s;
        }
        
        .btn-approve { background-color: #28a745; color: white; }
        .btn-approve:hover { background-color: #218838; transform: translateY(-2px); }
        
        .btn-reject { background-color: #dc3545; color: white; }
        .btn-reject:hover { background-color: #c82333; transform: translateY(-2px); }
        
        .badge-pending { background-color: #ffcc00; color: #111; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold; }
        
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px; }

        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; margin-top: 20px;}
        .empty-state .material-icons { font-size: 56px; color: #28a745; margin-bottom: 10px; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">domain_verification</span>
                <div class="header-text">
                    <h2>Review Pending Gelanggang</h2>
                    <p>Approve or reject training center registrations submitted by branch managers.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 18px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            @if($pendingGelanggangs->isEmpty())
                <div class="empty-state">
                    <span class="material-icons">task_alt</span>
                    <h3 style="margin: 0 0 5px 0; color: #333;">All caught up!</h3>
                    <p style="margin: 0;">There are no pending Gelanggang applications at this time.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Cawangan Code</th>
                                <th>Instructor Code</th>
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
                                <td><span class="badge-pending">Pending</span></td>
                                <td style="display: flex; gap: 8px; justify-content: center;">
                                    
                                    <form action="{{ route('gelanggangs.approve', $gelanggang->gel_ID) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-approve" onclick="return confirm('Approve this Gelanggang? It will become active immediately.');">
                                            <span class="material-icons" style="font-size: 16px;">check</span> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('gelanggangs.reject', $gelanggang->gel_ID) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-reject" onclick="return confirm('Are you sure you want to REJECT this application?');">
                                            <span class="material-icons" style="font-size: 16px;">close</span> Reject
                                        </button>
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="footer-nav">
                <a href="{{ route('staff.dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Admin Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>