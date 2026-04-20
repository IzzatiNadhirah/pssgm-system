<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
</head>
<body>
    <div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        
        <h2>Staff Dashboard</h2>
        
        @if (session('success'))
            <p style="color: green;"><b>{{ session('success') }}</b></p>
        @endif

        <p>Welcome to the Admin Staff panel, <b>{{ Auth::guard('staff')->user()->name }}</b>!</p>

        <div style="margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff;">
            <h3>System Management</h3>
            <ul>
                <li><a href="#" style="color: gray; text-decoration: none;">View My Cawangan (Page Not Built Yet)</a></li>
                
                <li><a href="{{ route('gelanggangs.create') }}" style="font-weight: bold;">Register New Gelanggang</a></li>
                
                <li><a href="#" style="color: gray; text-decoration: none;">Manage System Users (Page Not Built Yet)</a></li>
                <li><a href="#" style="color: gray; text-decoration: none;">View Payments & Memberships (Page Not Built Yet)</a></li>
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