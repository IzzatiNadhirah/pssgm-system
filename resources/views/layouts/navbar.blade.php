<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
    /* --- NAVIGATION BAR STYLE --- */
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

    .nav-left { display: flex; align-items: center; gap: 12px; color: white; font-weight: bold; letter-spacing: 1px; }
    .nav-logo-small { width: 40px; height: auto; }

    .nav-center { display: flex; gap: 20px; }

    .nav-link {
        color: white !important; 
        text-decoration: none !important;
        font-size: 0.85em;
        font-weight: 600;
        display: flex;
        align-items: center; gap: 6px;
        transition: 0.3s;
    }
    .nav-link:hover { color: #ffcc00 !important; }
    .nav-link.active { color: #ffcc00 !important; }

    .nav-right { display: flex; align-items: center; gap: 20px; }

    .user-meta { text-align: right; color: white; line-height: 1.2; }
    .user-meta .user-name { display: block; font-size: 0.9em; font-weight: bold; }
    /* KITA EJAS SINI: Padam sikit warna emas pada role supaya kita boleh tukar warna ikut status */
    .user-meta .user-role { display: block; font-size: 0.75em; font-weight: bold; text-transform: uppercase; }

    .profile-link {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 8px;
        transition: 0.3s;
        border: 1px solid transparent;
    }
    .profile-link:hover {
        background-color: rgba(255, 204, 0, 0.1);
        border-color: rgba(255, 204, 0, 0.3);
    }
    .profile-link .material-icons {
        font-size: 36px;
        color: #ffcc00;
    }

    .btn-logout-nav {
        background-color: #cc0000; color: white; border: none; padding: 8px 15px;
        border-radius: 6px; font-weight: bold; font-size: 0.85em; cursor: pointer;
        display: flex; align-items: center; gap: 5px; transition: 0.3s;
    }
    .btn-logout-nav:hover { background-color: #ff0000; }

    @media (max-width: 992px) { .nav-center { display: none; } }
</style>

<nav class="navbar">
    <div class="nav-left">
        <img src="{{ asset('images/logo_gayong.png') }}" class="nav-logo-small" alt="PSSGM">
        <span>PSSGM MELAKA</span>
    </div>

    @php
        // Kenal pasti siapa yang sedang log masuk
        $user = Auth::guard('staff')->user() ?? Auth::guard('instructor')->user() ?? Auth::user();
        $isMember = false;
        $role = '';
        $roleColor = '#ffcc00'; // Warna asal (Emas)
        
        if ($user) {
            if (Auth::guard('staff')->check()) {
                $role = strtolower($user->role ?? '') === 'admin' ? 'ADMIN' : 'SYSTEM STAFF';
            } elseif (Auth::guard('instructor')->check()) {
                $role = 'INSTRUCTOR';
            } else {
                $isMember = true; 
                
                // KITA EJAS SINI: Semak status keahlian sebenar pelajar (User biasa)
                $activeMembership = \App\Models\Membership::where('user_ID', $user->user_ID ?? $user->id)
                                    ->where(function($query) {
                                        $query->whereNull('expired_at')
                                              ->orWhere('expired_at', '>', now());
                                    })
                                    ->first();
                
                if ($activeMembership) {
                    $role = 'ACTIVE';
                    $roleColor = '#28a745'; // Hijau untuk aktif
                } else {
                    $role = 'INACTIVE / PENDING';
                    $roleColor = '#dc3545'; // Merah untuk belum bayar atau expired
                }
            }
        }
    @endphp

    @if($user)
        <div class="nav-center">
            
            {{-- ========================================== --}}
            {{-- MENU UNTUK STAFF & SUPER ADMIN --}}
            {{-- ========================================== --}}
            @if(Auth::guard('staff')->check())
                
                {{-- SUSUNAN TEPAT UNTUK SUPER ADMIN --}}
                @if(strtolower(Auth::guard('staff')->user()->role) === 'admin')
                    <a href="{{ route('staff.dashboard') }}" class="nav-link">
                        <span class="material-icons">dashboard</span> Dashboard
                    </a>
                    
                    <a href="{{ route('gelanggangs.pending') }}" class="nav-link">
                        <span class="material-icons">rule</span> Approvals
                    </a>
                    <a href="{{ route('cawangans.index') }}" class="nav-link">
                        <span class="material-icons">domain</span> Branches
                    </a>
                    <a href="{{ route('gelanggangs.index') }}" class="nav-link">
                        <span class="material-icons">stadium</span> Gelanggang
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <span class="material-icons">manage_accounts</span> Users
                    </a>
                    <a href="{{ route('staff.payments.index') }}" class="nav-link">
                        <span class="material-icons">receipt_long</span> Payments
                    </a>
                
                {{-- SUSUNAN UNTUK SYSTEM STAFF BIASA --}}
                @else
                    <a href="{{ route('staff.dashboard') }}" class="nav-link">
                        <span class="material-icons">admin_panel_settings</span> Admin Panel
                    </a>
                    <a href="{{ route('gelanggangs.index') }}" class="nav-link">
                        <span class="material-icons">storefront</span> Gelanggang
                    </a>
                    <a href="{{ route('courses.index') }}" class="nav-link">
                        <span class="material-icons">list_alt</span> Manage Courses
                    </a>
                    <a href="{{ route('staff.promotions.index') }}" class="nav-link">
                        <span class="material-icons">military_tech</span> Bengkung
                    </a>
                @endif

            {{-- ========================================== --}}
            {{-- MENU UNTUK INSTRUCTOR --}}
            {{-- ========================================== --}}
            @elseif(Auth::guard('instructor')->check())
                <a href="{{ route('instructor.dashboard') }}" class="nav-link">
                    <span class="material-icons">dashboard</span> Dashboard
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link">
                    <span class="material-icons">menu_book</span> My Courses
                </a>
                <a href="{{ route('instructor.enrolled') }}" class="nav-link">
                    <span class="material-icons">groups</span> Students
                </a>
                <a href="{{ route('attendance.index') }}" class="nav-link">
                    <span class="material-icons">fact_check</span> Attendance
                </a>
                <a href="{{ route('promotions.index') }}" class="nav-link">
                    <span class="material-icons">military_tech</span> Promotions
                </a>

            {{-- ========================================== --}}
            {{-- MENU UNTUK PELAJAR (USER BIASA) --}}
            {{-- ========================================== --}}
            @else
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <span class="material-icons">dashboard</span> Dashboard
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link">
                    <span class="material-icons">fitness_center</span> Courses
                </a>
                <a href="{{ route('timetable.index') }}" class="nav-link">
                    <span class="material-icons">event_note</span> My Timetable
                </a>
                <a href="{{ route('membership.history') }}" class="nav-link">
                    <span class="material-icons">receipt_long</span> History
                </a>
            @endif
        </div>

        <div class="nav-right">
            @if($isMember)
                <a href="{{ route('profile.edit') }}" class="profile-link" title="Manage My Profile">
                    <div class="user-meta">
                        <span class="user-name">{{ $user->name }}</span>
                        {{-- KITA EJAS SINI: Tunjuk status beserta warna yang sesuai --}}
                        <span class="user-role" style="color: {{ $roleColor }};">{{ $role }}</span>
                    </div>
                    <span class="material-icons">account_circle</span>
                </a>
            @else
                <div class="user-meta">
                    <span class="user-name">{{ $user->name }}</span>
                    <span class="user-role" style="color: {{ $roleColor }};">{{ $role }}</span>
                </div>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout-nav">
                    <span class="material-icons" style="font-size: 18px;">logout</span> Logout
                </button>
            </form>
        </div>
    @endif
</nav>