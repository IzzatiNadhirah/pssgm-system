<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - PSSGM</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
            margin: 0; 
        }
        .container { 
            max-width: 800px; 
            margin: 40px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            border-top: 5px solid #cc0000; 
        }
        .header-title { 
            color: #111; 
            margin-top: 0; 
            border-bottom: 2px solid #ffcc00; 
            padding-bottom: 10px; 
            display: inline-block; 
        }
        .welcome-text { 
            font-size: 1.1em; 
            color: #444; 
            margin-bottom: 30px; 
        }
        
        /* Grid layout untuk jadikan menu bentuk kotak */
        .dashboard-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        
        /* Design untuk Menu Card */
        .card { 
            background-color: #111; 
            color: white; 
            padding: 25px 20px; 
            border-radius: 8px; 
            text-decoration: none; 
            text-align: center; 
            font-weight: bold; 
            font-size: 1.1em; 
            transition: transform 0.2s, box-shadow 0.2s; 
            border-bottom: 4px solid #ffcc00; 
            display: block; 
        }
        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 6px 15px rgba(0,0,0,0.2); 
            background-color: #222; 
        }
        
        .card-red { border-bottom-color: #cc0000; }
        
        /* Bahagian butang Logout */
        .logout-form { 
            border-top: 1px solid #eee; 
            padding-top: 20px; 
            margin-top: 20px; 
            text-align: right; 
        }
        .btn-logout { 
            background-color: #cc0000; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 4px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: 0.2s; 
        }
        .btn-logout:hover { background-color: #990000; }
        
        .sub-text {
            font-size: 0.8em; 
            color: #ccc; 
            margin-top: 10px; 
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header-title">Instructor Dashboard</h2>
        
        <p class="welcome-text">
            Welcome to the Instructor panel, <b>{{ Auth::guard('instructor')->user()->name ?? 'Kasim bin Selamat' }}</b>!
        </p>

        <div class="dashboard-grid">
            <a href="{{ route('courses.index') }}" class="card">
                📋 Assigned Courses
                <div class="sub-text">View your courses and set class schedules</div>
            </a>
            
            <a href="{{ route('sessions.index') }}" class="card card-red">
                👥 Enrolled Students
                <div class="sub-text">View students who joined your classes</div>
            </a>
        </div>

        <div class="logout-form">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>