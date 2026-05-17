<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1100px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        /* --- HEADER STYLES --- */
        .header-area { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 30px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 2px; font-size: 2.2em; }
        .subtitle { color: #555; font-size: 1.1em; margin-top: 10px; }
        
        .status-badge { 
            display: inline-flex; align-items: center; gap: 5px; background: #d4edda; 
            color: #155724; padding: 8px 16px; border-radius: 20px; font-weight: bold; margin-top: 15px; 
            border: 1px solid #c3e6cb;
        }

        /* --- GRID CARDS STYLES --- */
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        
        .card-link { text-decoration: none; color: inherit; display: block; }
        
        .card { 
            background: #111; color: white; padding: 30px 20px; border-radius: 12px; 
            text-align: center; transition: 0.3s; border-bottom: 5px solid #cc0000; 
            height: 100%; box-sizing: border-box; display: flex; flex-direction: column; align-items: center;
        }
        
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); border-bottom-color: #ffcc00; }
        
        .card .material-icons { font-size: 48px; color: #ffcc00; margin-bottom: 15px; }
        .card h3 { margin: 0 0 10px 0; font-size: 1.2em; text-transform: uppercase; letter-spacing: 1px; }
        .card p { margin: 0; font-size: 0.9em; color: #aaa; line-height: 1.5; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <h2>Staff Dashboard</h2>
                <p class="subtitle">Welcome back to the Admin Staff panel, <b>{{ Auth::guard('staff')->user()->name }}</b>!</p>
                <div class="status-badge">
                    <span class="material-icons" style="font-size: 18px;">admin_panel_settings</span> System Administrator
                </div>
            </div>

            <div class="grid-container">
                
                <a href="{{ route('courses.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">menu_book</span>
                        <h3>Course Directory</h3>
                        <p>Manage silat courses, assign instructors, and update schedules.</p>
                    </div>
                </a>

                <a href="{{ route('gelanggangs.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">stadium</span>
                        <h3>Manage Gelanggang</h3>
                        <p>Register new training locations and view current gelanggangs.</p>
                    </div>
                </a>

                <a href="#" class="card-link">
                    <div class="card">
                        <span class="material-icons">account_balance</span>
                        <h3>My Cawangan</h3>
                        <p>View and manage your district branch information.</p>
                    </div>
                </a>

                <a href="{{ route('users.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">people</span>
                        <h3>System Users</h3>
                        <p>Manage registered members, instructors, and staff accounts.</p>
                    </div>
                </a>

                <a href="#" class="card-link">
                    <div class="card">
                        <span class="material-icons">payments</span>
                        <h3>Fee & Memberships</h3>
                        <p>Track membership registrations and verify payment receipts.</p>
                    </div>
                </a>

            </div>

        </div>
    </div>

</body>
</html>