<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        
        /* --- NAVIGATION BAR --- */
        .navbar {
            background-color: #000;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #ffcc00; /* Gold Line */
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-logo-small { width: 40px; height: auto; }

        .nav-center {
            display: flex;
            gap: 20px;
        }

        /* FIX WARNA UNGU: Paksa warna putih dan buang visited style */
        .nav-link {
            color: white !important; 
            text-decoration: none !important;
            font-size: 0.9em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }

        .nav-link:hover { color: #ffcc00 !important; }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-meta {
            text-align: right;
            color: white;
            line-height: 1.2;
        }

        .user-meta .user-name { display: block; font-size: 0.9em; font-weight: bold; }
        .user-meta .user-role { display: block; font-size: 0.75em; color: #ffcc00; }

        .btn-logout-nav {
            background-color: #cc0000;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 0.85em;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-logout-nav:hover { background-color: #ff0000; }

        /* --- CONTENT AREA --- */
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 900px; width: 100%; background: white; padding: 40px; border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header { margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        h2 { margin: 0; color: #111; text-transform: uppercase; font-size: 1.5em; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #111; color: #ffcc00; text-transform: uppercase; font-size: 0.85em; }
        
        .status-paid { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; }
        
        .empty-state { text-align: center; padding: 40px; color: #888; }
        .back-nav { margin-top: 30px; }
        .back-nav a { color: #cc0000; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px; transition: 0.2s; }
        .back-nav a:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <img src="{{ asset('images/logo_gayong.png') }}" class="nav-logo-small" alt="PSSGM">
            <span>PSSGM MELAKA</span>
        </div>

        <div class="nav-center">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="{{ route('courses.index') }}" class="nav-link">
                <span class="material-icons">fitness_center</span> Courses
            </a>
            <a href="{{ route('membership.history') }}" class="nav-link" style="color: #ffcc00 !important;">
                <span class="material-icons">receipt_long</span> History
            </a>
        </div>

        <div class="nav-right">
            <div class="user-meta">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">Active</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout-nav">
                    <span class="material-icons" style="font-size: 18px;">logout</span> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="content-area">
        <div class="container">
            <div class="header">
                <h2><span class="material-icons" style="vertical-align: bottom; color: #cc0000;">history</span> Payment History</h2>
            </div>

            @if($payments->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ddd;">payments</span>
                    <p>No payment records found.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td><code style="background: #f4f4f4; padding: 4px 8px; border-radius: 4px; color: #444;">{{ $payment->payment_code }}</code></td>
                            <td><b>RM {{ number_format($payment->amount, 2) }}</b></td>
                            <td><span class="status-paid">Paid</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="back-nav">
                <a href="{{ route('dashboard') }}">
                    <span class="material-icons">arrow_back</span> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

</body>
</html>