<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .navbar { background-color: #000; padding: 10px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #ffcc00; position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .nav-left { display: flex; align-items: center; gap: 12px; color: white; font-weight: bold; }
        .nav-logo-small { width: 40px; height: auto; }
        .nav-center { display: flex; gap: 20px; }
        .nav-link { color: white; text-decoration: none; font-size: 0.9em; font-weight: 600; display: flex; align-items: center; gap: 5px; transition: 0.3s; }
        .nav-link:hover { color: #ffcc00; }
        .nav-right { display: flex; align-items: center; gap: 10px; }
        .user-meta { text-align: right; color: white; line-height: 1.2; }
        .user-meta .user-name { display: block; font-size: 0.9em; font-weight: bold; }
        .user-meta .user-role { display: block; font-size: 0.75em; color: #ffcc00; }
        .btn-logout-nav { background-color: #cc0000; color: white; border: none; padding: 8px 15px; border-radius: 6px; font-weight: bold; font-size: 0.85em; cursor: pointer; display: flex; align-items: center; gap: 5px; }

        .content-area { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; }
        
        .renewal-alert {
            max-width: 900px; width: 100%; background: #fff3cd; color: #856404; 
            padding: 15px; border-radius: 8px; margin-bottom: 20px; 
            border-left: 6px solid #ffc107; display: flex; align-items: center; gap: 10px;
            box-sizing: border-box; font-weight: bold; font-size: 0.9em;
        }

        .container { 
            max-width: 900px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }
        .header { text-align: center; margin-bottom: 35px; }
        .logo-large { width: 110px; margin-bottom: 15px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        .status-bar { padding: 15px 20px; border-radius: 10px; margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; font-weight: bold; font-size: 0.95em; }
        .status-active { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-expired { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 25px; }
        .card-box { background-color: #111; color: white; padding: 35px 25px; border-radius: 12px; text-align: center; transition: 0.3s; border-bottom: 5px solid #ffcc00; display: flex; flex-direction: column; align-items: center; height: 100%; box-sizing: border-box; }
        .card-box:hover { transform: translateY(-8px); background-color: #222; }
        .card-link-main { text-decoration: none; color: inherit; width: 100%; }
        .card-box .material-icons { font-size: 2.5em; color: #ffcc00; margin-bottom: 15px; display: block; }
        .card-title { font-size: 1.2em; display: block; margin-bottom: 8px; font-weight: bold; }
        .card-desc { font-size: 0.85em; color: #bbb; font-weight: normal; line-height: 1.4; margin-bottom: 20px; }

        .card-action-btn { background: #cc0000; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 0.8em; font-weight: bold; transition: 0.2s; display: flex; align-items: center; gap: 5px; }
        .card-action-btn.gold { background: #ffcc00; color: #111; }
        .card-action-btn.disabled { background: #444; color: #888; cursor: not-allowed; }
        .card-action-btn:hover:not(.disabled) { transform: scale(1.05); }

        @media (max-width: 768px) { .nav-center { display: none; } }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <img src="{{ asset('images/logo_gayong.png') }}" class="nav-logo-small" alt="PSSGM">
            <span>PSSGM MELAKA</span>
        </div>
        <div class="nav-center">
            <a href="{{ route('dashboard') }}" class="nav-link"><span class="material-icons">dashboard</span> Dashboard</a>
            <a href="{{ route('courses.index') }}" class="nav-link"><span class="material-icons">fitness_center</span> Courses</a>
            <a href="{{ route('membership.history') }}" class="nav-link"><span class="material-icons">receipt_long</span> History</a>
        </div>
        <div class="nav-right">
            {{-- KITA EJAS SINI: Jadikan nama & ikon boleh di-klik ke Profile --}}
            <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; text-decoration: none; padding: 5px 10px; border-radius: 8px; transition: 0.3s;" onmouseover="this.style.backgroundColor='rgba(255, 204, 0, 0.1)'" onmouseout="this.style.backgroundColor='transparent'" title="Manage My Profile">
                <div class="user-meta" style="margin-right: 10px;">
                    <span class="user-name" style="color: white;">{{ Auth::user()->name }}</span>
                    {{-- Kekalkan status 'Active' atau 'Member' macam yang bos nak --}}
                    <span class="user-role" style="font-weight: bold; color: #ffcc00;">{{ is_null(Auth::user()->membership) ? 'Member' : 'Active' }}</span>
                </div>
                <span class="material-icons" style="font-size: 38px; color: #ffcc00;">account_circle</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0; margin-left: 10px;">
                @csrf
                <button type="submit" class="btn-logout-nav">
                    <span class="material-icons" style="font-size: 18px;">logout</span> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="content-area">
        
        @if(!is_null(Auth::user()->membership) && Auth::user()->membership->expired_at)
            @php
                $daysLeft = \Carbon\Carbon::now()->diffInDays(Auth::user()->membership->expired_at, false);
            @endphp

            @if(Auth::user()->membership->member_type == 'Tahunan')
                @if($daysLeft <= 30 && $daysLeft > 0)
                    <div class="renewal-alert">
                        <span class="material-icons">info</span>
                        <span>Reminder: Your annual membership expires in <b>{{ $daysLeft }} days</b>. Please renew soon!</span>
                    </div>
                @elseif($daysLeft <= 0)
                    <div class="renewal-alert" style="background: #f8d7da; color: #721c24; border-left-color: #dc3545;">
                        <span class="material-icons">error</span>
                        <span>ALERT: Your membership has <b>EXPIRED</b>. Please renew to continue training!</span>
                    </div>
                @endif
            @endif
        @endif

        <div class="container">
            <div class="header">
                <img src="{{ asset('images/logo_gayong.png') }}" alt="Logo" class="logo-large">
                <h2>Member Dashboard</h2>
                <p style="color: #666;">Pertubuhan Silat Seni Gayong Malaysia - Cawangan Melaka</p>
            </div>

            @if(is_null(Auth::user()->membership))
                <div class="status-bar status-pending">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="material-icons">warning</span>
                        <span>Membership: Inactive (Payment Required)</span>
                    </div>
                    <a href="{{ route('memberships.create') }}" style="color: #856404; text-decoration: underline;">Pay Now</a>
                </div>
            @else
                @php
                    $isExpired = Auth::user()->membership->member_type == 'Tahunan' && 
                                 Auth::user()->membership->expired_at && 
                                 \Carbon\Carbon::now()->greaterThan(Auth::user()->membership->expired_at);
                @endphp
                <div class="status-bar {{ $isExpired ? 'status-expired' : 'status-active' }}">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="material-icons">{{ $isExpired ? 'block' : 'verified' }}</span>
                        <span>Status: {{ $isExpired ? 'Expired' : 'Active' }} ({{ Auth::user()->membership->member_type }})</span>
                    </div>
                    <div style="text-align: right;">
                        @if(Auth::user()->membership->member_type == 'Sepanjang Hayat')
                            <span style="font-size: 0.8em; display: block;">Membership: <b>Lifetime Access</b></span>
                        @elseif(Auth::user()->membership->expired_at)
                            <span style="font-size: 0.8em; display: block;">Expiry Date: <b>{{ \Carbon\Carbon::parse(Auth::user()->membership->expired_at)->format('d M Y') }}</b></span>
                        @else
                            <span style="font-size: 0.8em; display: block; color: #cc0000;">Expiry: <b>Data Pending</b></span>
                        @endif
                        <a href="{{ route('memberships.create') }}" style="font-size: 0.7em; color: inherit; text-decoration: underline;">Renew/Upgrade</a>
                    </div>
                </div>
            @endif

            <div class="dashboard-grid">
                <a href="{{ route('courses.index') }}" class="card-link-main">
                    <div class="card-box">
                        <span class="material-icons">sports_martial_arts</span>
                        <span class="card-title">Join Training</span>
                        <span class="card-desc">Browse gelanggang and enroll in available silat sessions.</span>
                    </div>
                </a>

                <a href="{{ route('timetable.index') }}" class="card-link-main">
                    <div class="card-box">
                        <span class="material-icons">event_note</span>
                        <span class="card-title">My Timetable</span>
                        <span class="card-desc">Check your upcoming classes, dates, and locations.</span>
                    </div>
                </a>

                <div class="card-box">
                    <span class="material-icons">payments</span>
                    <span class="card-title">Membership & Fees</span>
                    <span class="card-desc">Manage your subscription, renew fees, and view records.</span>
                    
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('membership.history') }}" class="card-action-btn gold">
                            <span class="material-icons" style="font-size: 16px;">history</span> History
                        </a>

                        @if(is_null(Auth::user()->membership) || Auth::user()->membership->member_type == 'Tahunan')
                            <a href="{{ route('memberships.create') }}" class="card-action-btn">
                                <span class="material-icons" style="font-size: 16px;">autorenew</span> {{ is_null(Auth::user()->membership) ? 'Pay Now' : 'Renew/Upgrade' }}
                            </a>
                        @else
                            <div class="card-action-btn disabled">
                                <span class="material-icons" style="font-size: 16px;">done_all</span> Lifetime
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>