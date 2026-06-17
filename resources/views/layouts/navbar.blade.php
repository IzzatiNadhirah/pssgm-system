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

    .nav-center { display: flex; gap: 25px; }

    /* FIX WARNA UNGU & LINK STYLE */
    .nav-link {
        color: white !important; 
        text-decoration: none !important;
        font-size: 0.9em;
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
    .user-meta .user-role { display: block; font-size: 0.75em; color: #ffcc00; text-transform: uppercase; }

    /* --- KITA EJAS SINI: Style untuk Link Profile --- */
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

    @media (max-width: 768px) { .nav-center { display: none; } }
</style>

<nav class="navbar">
    <div class="nav-left">
        <img src="{{ asset('images/logo_gayong.png') }}" class="nav-logo-small" alt="PSSGM">
        <span>PSSGM MELAKA</span>
    </div>

    <div class="nav-center">
        @if(Auth::guard('staff')->check())
            <a href="{{ route('staff.dashboard') }}" class="nav-link">
                <span class="material-icons">admin_panel_settings</span> Admin Panel
            </a>
            <a href="{{ route('courses.index') }}" class="nav-link">
                <span class="material-icons">list_alt</span> Manage Courses
            </a>
            
            {{-- PENGHADANG KESELAMATAN: Hanya staff berpangkat 'admin' nampak menu ni --}}
            @if(strtolower(Auth::guard('staff')->user()->role) === 'admin')
                <a href="{{ route('users.index') }}" class="nav-link">
                    <span class="material-icons">people</span> Members
                </a>
            @endif

        @elseif(Auth::guard('instructor')->check())
            <a href="{{ route('instructor.dashboard') }}" class="nav-link">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="{{ route('courses.index') }}" class="nav-link">
                <span class="material-icons">menu_book</span> My Courses
            </a>
            <a href="{{ route('attendance.index') }}" class="nav-link">
                <span class="material-icons">fact_check</span> Attendance
            </a>

        @else
            <a href="{{ route('dashboard') }}" class="nav-link">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="{{ route('courses.index') }}" class="nav-link">
                <span class="material-icons">fitness_center</span> Courses
            </a>
            
            {{-- KITA EJAS SINI: Tambah link My Timetable untuk User Biasa --}}
            <a href="{{ route('timetable.index') }}" class="nav-link">
                <span class="material-icons">event_note</span> My Timetable
            </a>
            
            <a href="{{ route('membership.history') }}" class="nav-link">
                <span class="material-icons">receipt_long</span> History
            </a>
        @endif
    </div>

    <div class="nav-right">
        @php
            $user = Auth::guard('staff')->user() ?? Auth::guard('instructor')->user() ?? Auth::user();
            $isMember = false;
            
            // LOGIK BAHARU UNTUK TENTUKAN PANGKAT (ROLE) DENGAN TEPAT
            if (Auth::guard('staff')->check()) {
                $role = strtolower(Auth::guard('staff')->user()->role) === 'admin' ? 'Admin' : 'System Staff';
            } elseif (Auth::guard('instructor')->check()) {
                $role = 'Instructor';
            } else {
                $role = 'Active';
                $isMember = true; // Set flag ni true kalau dia Ahli
            }
        @endphp

        {{-- Paparan berbeza untuk Ahli (Boleh klik) vs Staf/Cikgu (Statik) --}}
        @if($isMember)
            <a href="{{ route('profile.edit') }}" class="profile-link" title="Manage My Profile">
                <div class="user-meta">
                    <span class="user-name">{{ $user->name }}</span>
                    <span class="user-role">{{ $role }}</span>
                </div>
                <span class="material-icons">account_circle</span>
            </a>
        @else
            <div class="user-meta">
                <span class="user-name">{{ $user->name }}</span>
                <span class="user-role">{{ $role }}</span>
            </div>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-logout-nav">
                <span class="material-icons" style="font-size: 18px;">logout</span> Logout
            </button>
        </form>
    </div>
</nav>