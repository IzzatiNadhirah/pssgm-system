<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS Khusus untuk Page Content */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }

        .container { 
            max-width: 1200px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        /* --- HEADER --- */
        .header-area { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 30px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 2px; font-size: 2.2em; }
        .subtitle { color: #555; font-size: 1.1em; margin-top: 10px; }
        .status-badge { 
            display: inline-flex; align-items: center; gap: 5px; background: #cce5ff; 
            color: #004085; padding: 8px 16px; border-radius: 20px; font-weight: bold; margin-top: 15px; 
            border: 1px solid #b8daff;
        }

        /* --- STATS BOXES --- */
        .stats-container { display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap; }
        
        .stat-card { 
            flex: 1; min-width: 180px; background: #f9f9f9; padding: 25px 15px; 
            border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
            border-left: 5px solid #111;
        }
        .stat-card.gold { border-left-color: #ffcc00; }
        .stat-card.red { border-left-color: #cc0000; }
        
        .stat-card h4 { margin: 0; color: #666; text-transform: uppercase; font-size: 0.8em; letter-spacing: 1px; }
        .stat-card .value { font-size: 2.2em; font-weight: bold; color: #111; margin-top: 10px; display: block; }

        /* --- GRID MENU CARDS --- */
        .section-title { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; text-transform: uppercase; color: #111; font-size: 1.2em; }
        
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        
        .card-link { text-decoration: none; color: inherit; display: block; }
        
        .card { 
            background: #111; color: white; padding: 25px 20px; border-radius: 12px; 
            text-align: center; transition: 0.3s; border-bottom: 5px solid #cc0000; 
            height: 100%; box-sizing: border-box; display: flex; flex-direction: column; align-items: center;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); border-bottom-color: #ffcc00; }

        .card .material-icons { font-size: 42px; color: #ffcc00; margin-bottom: 15px; }
        .card h3 { margin: 0 0 10px 0; font-size: 1.1em; text-transform: uppercase; letter-spacing: 1px; }
        .card p { margin: 0; font-size: 0.85em; color: #ccc; line-height: 1.4; }

        /* --- NOTIFICATION BADGE STYLE --- */
        .icon-wrapper { position: relative; display: inline-block; }
        .notif-dot {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #cc0000; /* Merah PSSGM */
            color: white;
            font-size: 0.7em;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 20px;
            border: 3px solid #111; /* Sempadan hitam supaya blend dengan background kad */
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            animation: pulse 2s infinite; /* Tambah efek denyut sikit bagi gempak */
        }

        /* Animasi denyutan merah */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(204, 0, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(204, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(204, 0, 0, 0); }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <h2>HQ Overview</h2>
                <p class="subtitle">Welcome to the master control panel</p>
                <div class="status-badge">
                    <span class="material-icons" style="font-size: 18px;">admin_panel_settings</span> Admin
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <h4>Members</h4>
                    <span class="value">{{ $countMembers ?? 0 }}</span>
                </div>
                <div class="stat-card">
                    <h4>Instructors</h4>
                    <span class="value">{{ $countInstructors ?? 0 }}</span>
                </div>
                <div class="stat-card">
                    <h4>Staff</h4>
                    <span class="value">{{ $countStaffs ?? 0 }}</span>
                </div>
                <div class="stat-card gold">
                    <h4>Gelanggang</h4>
                    <span class="value">{{ $totalGelanggang ?? 0 }}</span>
                </div>
                <div class="stat-card red">
                    <h4>Fees Collected (RM)</h4>
                    <span class="value">{{ number_format($totalFees ?? 0, 2) }}</span>
                </div>
            </div>

            <h3 class="section-title">System Management</h3>

            <div class="grid-container">

                @php
                    // Kira Gelanggang Pending
                    $pendingGelanggangCount = \App\Models\Gelanggang::where('status', 'pending')->count();
                    
                    // KITA EJAS SINI: Kira Payment Pending
                    $pendingPaymentCount = \App\Models\Payment::where('payment_status', 'Pending Verification')->count();
                @endphp

                <a href="{{ route('gelanggangs.pending') }}" class="card-link">
                    <div class="card">
                        <div class="icon-wrapper">
                            <span class="material-icons">notification_important</span>
                            @if($pendingGelanggangCount > 0)
                                <span class="notif-dot">{{ $pendingGelanggangCount }}</span>
                            @endif
                        </div>
                        <h3>Pending Approvals</h3>
                        <p>Review and approve new Gelanggang registrations.</p>
                    </div>
                </a>

                <a href="{{ route('cawangans.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">domain</span>
                        <h3>Manage Branches</h3>
                        <p>Register and configure PSSGM branches.</p>
                    </div>
                </a>

                <a href="{{ route('gelanggangs.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">stadium</span>
                        <h3>Active Gelanggang</h3>
                        <p>View directory of all approved training locations.</p>
                    </div>
                </a>

                <a href="{{ route('users.index') }}" class="card-link">
                    <div class="card">
                        <span class="material-icons">manage_accounts</span>
                        <h3>System Users</h3>
                        <p>Manage members, instructors, and staff accounts.</p>
                    </div>
                </a>

                {{-- KITA EJAS SINI: Kad Payment Approvals dengan Notifikasi --}}
                <a href="{{ route('staff.payments.index') }}" class="card-link">
                    <div class="card">
                        <div class="icon-wrapper">
                            <span class="material-icons">receipt_long</span>
                            @if($pendingPaymentCount > 0)
                                <span class="notif-dot">{{ $pendingPaymentCount }}</span>
                            @endif
                        </div>
                        <h3>Payment List</h3>
                        <p>Review receipts.</p>
                    </div>
                </a>

            </div>

        </div>
    </div>

</body>
</html>