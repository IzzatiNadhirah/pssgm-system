<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
</head>
<body>
    <div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        
        <h2>Instructor Dashboard</h2>
        
        @if (session('success'))
            <p style="color: green;"><b>{{ session('success') }}</b></p>
        @endif

        <p>Welcome to the Instructor panel.</p>

        <div style="margin: 20px 0;">
            <h3>Quick Actions</h3>
            <ul>
                <li><a href="{{ route('sessions.create') }}">Manage Training Sessions</a></li>
                <li><a href="{{ route('courses.create') }}">Manage Courses</a></li>
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