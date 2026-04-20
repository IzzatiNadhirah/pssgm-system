<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <style>
        .stat-box {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 10px;
            flex: 1;
            text-align: center;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div style="max-width: 900px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        
        <h2>HQ Overview: Super Admin Dashboard</h2>
        
        <p>Welcome to the master control panel, <b>{{ Auth::guard('staff')->user()->name }}</b>!</p>

        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <div class="stat-box" style="border-left-color: #007bff;">
                <h3>Total Members</h3>
                <div class="stat-number">{{ $totalMembers }}</div>
            </div>
            
            <div class="stat-box" style="border-left-color: #17a2b8;">
                <h3>Active Gelanggang</h3>
                <div class="stat-number">{{ $totalGelanggang }}</div>
            </div>
            
            <div class="stat-box" style="border-left-color: #28a745;">
                <h3>Total Fees Collected</h3>
                <div class="stat-number">RM {{ number_format($totalFees, 2) }}</div>
            </div>
        </div>

        <div style="margin: 30px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #6c757d;">
            <h3>System Management</h3>
            <ul>
                <li><a href="{{ route('cawangans.index') }}">Manage Cawangan (Branches)</a></li>
                
                <li><a href="{{ route('gelanggangs.pending') }}" style="font-weight: bold; color: #d32f2f;">Review Pending Gelanggang Approvals</a></li>
                
                <li><a href="{{ route('gelanggangs.index') }}">View Active Gelanggang Directory</a></li>
                
                <li><a href="{{ route('users.index') }}">Manage All System Users</a></li>
                
                <li><a href="#">View Detailed Payment Reports</a></li>
            </ul>
        </div>

        <hr>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Logout
            </button>
        </form>

    </div>
</body>
</html>