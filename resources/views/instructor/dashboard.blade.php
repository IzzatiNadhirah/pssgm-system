<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS Khusus untuk Page Content sahaja (Navbar CSS dah ada dalam include) */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #111; 
            margin: 0; 
            min-height: 100vh;
        }

        .content-area {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .container { 
            max-width: 900px; 
            width: 100%;
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000;
            border-bottom: 8px solid #ffcc00;
        }
        
        .header-title { 
            color: #111; 
            margin-top: 0; 
            border-bottom: 2px solid #eee; 
            padding-bottom: 15px; 
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-title .material-icons { color: #cc0000; font-size: 32px; }
        
        .welcome-text { 
            font-size: 1.1em; 
            color: #444; 
            margin-bottom: 35px; 
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #ffcc00;
            border-radius: 4px;
        }
        
        /* Grid layout untuk jadikan menu bentuk kotak */
        .dashboard-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 25px; 
            margin-bottom: 30px; 
        }
        
        /* Design untuk Menu Card */
        .card { 
            background-color: #111; 
            color: white; 
            padding: 30px 20px; 
            border-radius: 10px; 
            text-decoration: none; 
            text-align: center; 
            font-weight: bold; 
            font-size: 1.2em; 
            transition: transform 0.2s, box-shadow 0.2s; 
            border-bottom: 5px solid #ffcc00; 
            display: flex; 
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .card .material-icons { font-size: 48px; color: #ffcc00; margin-bottom: 5px; }

        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.3); 
            background-color: #222; 
        }
        
        /* Card untuk Student warna merah */
        .card-red { border-bottom-color: #cc0000; }
        .card-red .material-icons { color: #cc0000; }
        
        .sub-text {
            font-size: 0.75em; 
            color: #ccc; 
            margin-top: 5px; 
            font-weight: normal;
        }
        
        /* Bahagian butang Logout kita hilangkan sebab dah ada kat Navbar */
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            <h2 class="header-title">
                <span class="material-icons">sports_martial_arts</span> Instructor Dashboard
            </h2>
            
            <div class="welcome-text">
                Welcome back, <b>{{ Auth::guard('instructor')->user()->name ?? 'Kasim bin Selamat' }}</b>! Here is your quick access panel.
            </div>

            <div class="dashboard-grid">
                
                <a href="{{ route('courses.index') }}" class="card">
                    <span class="material-icons">menu_book</span>
                    Assigned Courses
                    <div class="sub-text">View courses & manage schedules</div>
                </a>
                
                <a href="{{ route('instructor.enrolled') }}" class="card card-red">
                    <span class="material-icons">groups</span>
                    Enrolled Students
                    <div class="sub-text">View students joining your classes</div>
                </a>

            </div>

        </div>
    </div>
</body>
</html>