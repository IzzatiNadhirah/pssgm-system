<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }

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
    </style>
</head>
<body>

    @include('layouts.navbar')

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

            @php
                // --- KITA EJAS SINI: Semak Status Pembayaran Terkini ---
                $latestPayment = null;
                $isPendingPayment = false;
                
                if (!is_null(Auth::user()->membership)) {
                    $latestPayment = \App\Models\Payment::where('member_ID', Auth::user()->membership->member_ID)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                        
                    if ($latestPayment && $latestPayment->payment_status == 'Pending Verification') {
                        $isPendingPayment = true;
                    }
                }
            @endphp

            @if(is_null(Auth::user()->membership))
                <div class="status-bar status-pending">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="material-icons">warning</span>
                        <span>Membership: Inactive (Payment Required)</span>
                    </div>
                    <a href="{{ route('memberships.create') }}" style="color: #856404; text-decoration: underline;">Pay Now</a>
                </div>
                
            {{-- Tambah logik jika resit masih dlm proses semakan Admin --}}
            @elseif($isPendingPayment)
                <div class="status-bar status-pending">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="material-icons">hourglass_empty</span>
                        <span>Status: Pending Verification</span>
                    </div>
                    <div style="text-align: right; font-size: 0.85em; color: #856404;">
                        Your payment receipt is being reviewed by the admin.
                    </div>
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
                {{-- Disable fungsi pendaftaran kelas jika payment masih pending atau expired --}}
                @if(is_null(Auth::user()->membership) || $isPendingPayment || (isset($isExpired) && $isExpired))
                    <div class="card-box" style="opacity: 0.7; cursor: not-allowed;" title="You must have an active membership to join training.">
                        <span class="material-icons">sports_martial_arts</span>
                        <span class="card-title">Join Training</span>
                        <span class="card-desc">Browse gelanggang and enroll in available silat sessions.</span>
                    </div>
                @else
                    <a href="{{ route('courses.index') }}" class="card-link-main">
                        <div class="card-box">
                            <span class="material-icons">sports_martial_arts</span>
                            <span class="card-title">Join Training</span>
                            <span class="card-desc">Browse gelanggang and enroll in available silat sessions.</span>
                        </div>
                    </a>
                @endif

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