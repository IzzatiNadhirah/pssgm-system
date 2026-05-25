<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cawangan - PSSGM Melaka</title>
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
            max-width: 1000px; 
            width: 100%;
            background: white; 
            padding: 35px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000;
            border-bottom: 8px solid #ffcc00;
        }
        
        .header-area {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;
        }

        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }
        
        .btn { 
            padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px; 
            font-weight: bold; text-decoration: none; display: inline-flex; 
            align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s;
        }
        
        .btn-add { background-color: #cc0000; color: white; }
        .btn-edit { background-color: #ffcc00; color: #111; padding: 6px 12px; }
        .btn-delete { background-color: #333; color: white; padding: 6px 12px; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px; }

        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; }
        
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
                <h2>Manage Cawangan (Branches)</h2>
                <a href="{{ route('cawangans.create') }}" class="btn btn-add">
                    <span class="material-icons">add</span> Register New Cawangan
                </a>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 18px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            @if($cawangans->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">domain_disabled</span>
                    <h3>No Cawangan Registered Yet</h3>
                    <p>Click the button above to register a new branch.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cawangans as $caw)
                            <tr>
                                <td><b>{{ $caw->caw_name }}</b></td>
                                <td style="display: flex; gap: 8px; justify-content: center;">
                                    
                                    <a href="{{ route('cawangans.edit', $caw->caw_ID) }}" class="btn btn-edit" title="Edit Cawangan">
                                        <span class="material-icons" style="font-size: 18px;">edit</span>
                                    </a>

                                    <form action="{{ route('cawangans.destroy', $caw->caw_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Cawangan?');" style="margin: 0;">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" title="Delete Cawangan">
                                            <span class="material-icons" style="font-size: 18px;">delete</span>
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
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'admin')
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Staff Dashboard
                    </a>
                @endif
            </div>

        </div>
    </div>

</body>
</html>