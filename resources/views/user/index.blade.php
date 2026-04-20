<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage System Users</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; text-decoration: none; display: inline-block; font-size: 0.9em; }
        .btn-add { background-color: #28a745; color: white; margin-bottom: 15px; }
        .btn-edit { background-color: #ffc107; color: #212529; }
        .btn-delete { background-color: #dc3545; color: white; }
        
        .badge-member { background-color: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; }
        .empty-state { background-color: #e9ecef; padding: 40px; text-align: center; border-radius: 8px; color: #6c757d; margin-top: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-left: 5px solid #28a745; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 style="margin-top: 0;">System Users Directory</h2>
        <p style="color: #666;">This list contains all standard members registered in the system.</p>

        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        <a href="{{ route('users.create') }}" class="btn btn-add">+ Register New Member</a>

        @if($users->isEmpty())
            <div class="empty-state">
                <p style="margin-top: 0;">No members are currently registered in the system.</p>
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
                        @foreach($users as $user)
                        <tr>
                            <td><b>{{ $user->member_code ?? $user->user_code ?? $user->id }}</b></td> 
                            
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->bengkung_level ?? 'N/A' }}</td> 
                            
                            <td><b>{{ $user->membership->member_type ?? 'None' }}</b></td>
                            
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td><span class="badge-member">Member</span></td>
                            
                            <td style="display: flex; gap: 10px; justify-content: center;">
                                
                                <a href="{{ route('users.edit', $user->user_ID) }}" class="btn btn-edit">Edit</a>

                                <form action="{{ route('users.destroy', $user->user_ID) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Delete</button>
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