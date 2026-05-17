<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage System Users - PSSGM Melaka</title>
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
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;
        }

        .header-text h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        .header-text p { margin: 5px 0 0 0; color: #666; font-size: 0.9em; }

        .section-title { 
            margin: 30px 0 15px 0; color: #cc0000; text-transform: uppercase; 
            font-size: 1.25em; border-left: 5px solid #ffcc00; padding-left: 10px; font-weight: bold;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
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

        .badge-role { padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: bold; color: white; }
        .badge-member { background-color: #17a2b8; }
        .badge-staff { background-color: #6f42c1; }
        .badge-admin { background-color: #dc3545; }
        
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px; }

        .empty-state { text-align: center; padding: 40px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; margin-top: 10px; }
        
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
                <div class="header-text">
                    <h2>System Users Directory</h2>
                    <p>Manage all registered members and administrative staff in the system.</p>
                </div>
                
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'super_admin')
                    <a href="{{ route('users.create') }}" class="btn btn-add">
                        <span class="material-icons">person_add</span> Register New User
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 18px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            <div class="section-title">Registered Members</div>
            @if($members->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">group_off</span>
                    <h3>No Members Found</h3>
                    <p>No training members are currently registered.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Bengkung Level</th>
                                <th>Membership Type</th>
                                <th>Registration Date</th>
                                <th>Role</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td><b>{{ $member->user_ID }}</b></td> 
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->bengkung_level ?? 'N/A' }}</td> 
                                <td><b>{{ $member->membership->member_type ?? 'None' }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d M Y') }}</td>
                                <td><span class="badge-role badge-member">Member</span></td>
                                <td style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="{{ route('users.edit', $member->user_ID) }}" class="btn btn-edit" title="Edit User">
                                        <span class="material-icons" style="font-size: 18px;">edit</span>
                                    </a>

                                    <form action="{{ route('users.destroy', $member->user_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" title="Delete User">
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

            <div class="section-title">Management Staff</div>
            @if($staffs->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">admin_panel_settings</span>
                    <h3>No Staff Found</h3>
                    <p>No management staff accounts exist in the system.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Registration Date</th>
                                <th>Role</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr>
                                <td><b>{{ $staff->staff_ID }}</b></td>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($staff->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($staff->role === 'super_admin')
                                        <span class="badge-role badge-admin">Super Admin</span>
                                    @else
                                        <span class="badge-role badge-staff">System Staff</span>
                                    @endif
                                </td>
                                <td style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="{{ route('staffs.edit', $staff->staff_ID) }}" class="btn btn-edit" title="Edit Staff">
                                        <span class="material-icons" style="font-size: 18px;">edit</span>
                                    </a>

                                    @if($staff->role !== 'super_admin')
                                        <form action="{{ route('staffs.destroy', $staff->staff_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff member?');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete" title="Delete Staff">
                                                <span class="material-icons" style="font-size: 18px;">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="footer-nav">
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'super_admin')
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Super Admin Dashboard
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