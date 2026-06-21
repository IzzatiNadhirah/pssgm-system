<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Directory - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { max-width: 1300px; width: 100%; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; }
        
        .header-area { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; cursor: pointer; }
        tr:hover { background-color: #fffdf5; }
        
        .btn { padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s; }
        .btn-add { background-color: #070361; color: white; }
        .btn-edit { background-color: #ffcc00; color: #111; }
        .btn-delete { background-color: #333; color: white; }
        .btn-schedule { background-color: #17a2b8; color: white; }
        .btn-join { background-color: #28a745; color: white; }
        .btn-disabled { background-color: #888; color: white; cursor: not-allowed; }
        .btn-ended { background-color: #555; color: white; cursor: not-allowed; }
        .btn-enrolled { background-color: #6c757d; color: white; cursor: not-allowed; opacity: 0.9; }

        .btn:hover:not(.btn-disabled):not(.btn-ended):not(.btn-enrolled) { opacity: 0.9; transform: translateY(-2px); }

        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }

        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); }

        .filter-bar { display: flex; gap: 15px; margin-bottom: 35px; background: #fdfdfd; padding: 15px; border-radius: 8px; border: 2px dashed #eee; flex-wrap: wrap; }
        .filter-box { flex: 1; min-width: 200px; }
        .filter-box label { display: block; font-size: 0.85em; color: #111; margin-bottom: 8px; font-weight: bold; text-transform: uppercase; }
        .filter-box select { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; outline: none; font-family: inherit; font-size: 0.95em; cursor: pointer; transition: 0.2s; }
        .filter-box select:focus { border-color: #cc0000; }

        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #cc0000; }
        .dataTables_wrapper .dataTables_length select { border: 2px solid #eee; border-radius: 6px; padding: 5px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ffcc00 !important; color: #111 !important; border: none; font-weight: bold; border-radius: 6px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: none; border-radius: 6px; }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 20px; } 
        .dataTables_wrapper .dataTables_length { margin-bottom: 20px; }
        .dataTables_wrapper .dataTables_info { margin-top: 15px; padding-top: 10px; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 15px; padding-top: 10px; }
        
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                @if(Auth::guard('instructor')->check())
                    <h2>My Assigned Courses</h2>
                @else
                    <h2>Available Courses</h2>
                @endif
                
                @if(Auth::guard('staff')->check())
                    <a href="{{ route('courses.create') }}" class="btn btn-add">
                        <span class="material-icons">add</span> Register New Course
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($courses->isEmpty())
                <div style="text-align: center; padding: 50px; color: #888;">
                    <span class="material-icons" style="font-size: 48px;">inventory_2</span>
                    <p>No courses found in the system.</p>
                </div>
            @else
                
                @if(Auth::guard('instructor')->check())
                    <div style="overflow-x: auto;">
                        <table class="dataTable" id="instructorTable">
                            <thead>
                                <tr>
                                    <th>Course Type</th>
                                    <th style="text-align: center;">Active Sessions</th>
                                    <th style="text-align: center;">Total Enrolled</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                @php
                                    $session_count = \App\Models\SessionTimetable::where('course_ID', $course->course_ID ?? $course->id)->count();
                                    
                                    $sessions_for_course = \App\Models\SessionTimetable::where('course_ID', $course->course_ID ?? $course->id)->get();
                                    $session_ids = [];
                                    foreach($sessions_for_course as $s) {
                                        $session_ids[] = $s->id ?? $s->session_ID;
                                    }
                                    
                                    try {
                                        $total_enrolled = \DB::table('enrollments')->whereIn('session_ID', $session_ids)->count();
                                    } catch(\Exception $e) {
                                        $total_enrolled = 0;
                                    }
                                @endphp
                                <tr>
                                    <td><b style="color: #111; font-size: 1.1em;">{{ $course->course_type }}</b></td>
                                    
                                    <td style="text-align: center;">
                                        @if($session_count > 0)
                                            <span style="font-weight: bold; color: #28a745; font-size: 1.2em;">{{ $session_count }}</span>
                                            <div style="font-size: 0.7em; color: #666; text-transform: uppercase;">Sessions Scheduled</div>
                                        @else
                                            <span style="font-weight: bold; color: #cc0000; font-size: 1.2em;">0</span>
                                            <div style="font-size: 0.7em; color: #cc0000; text-transform: uppercase;">No Sessions</div>
                                        @endif
                                    </td>

                                    <td style="text-align: center;">
                                        <span style="font-weight: bold; color: #111; font-size: 1.2em;">{{ $total_enrolled }}</span>
                                        <div style="font-size: 0.7em; color: #666; text-transform: uppercase;">Students</div>
                                    </td>

                                    <td style="text-align: center;">
                                        <a href="{{ route('sessions.index', ['course_id' => $course->course_ID ?? $course->id]) }}" class="btn btn-schedule">
                                            <span class="material-icons">event_note</span> Manage Sessions
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    
                    <div class="filter-bar">
                        <div class="filter-box">
                            <label><span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">category</span> Filter Course Type</label>
                            <select id="filter-course">
                                <option value="">-- All Courses --</option>
                            </select>
                        </div>
                        <div class="filter-box">
                            <label><span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">domain</span> Filter Branch</label>
                            <select id="filter-branch">
                                <option value="">-- All Branches --</option>
                            </select>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="dataTable" id="memberTable">
                            <thead>
                                <tr>
                                    <th>Course Type</th>
                                    <th>Instructor</th>
                                    <th>Branch</th>
                                    <th>Location & Schedule</th>
                                    <th style="text-align: center;">Capacity</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                
                                @php
                                    $allSessions = \App\Models\SessionTimetable::with(['gelanggang.cawangan'])
                                                ->where('course_ID', $course->course_ID ?? $course->id)
                                                ->orderBy('start_time', 'asc')
                                                ->get();
                                                
                                    $groupedSessions = [];
                                    
                                    foreach($allSessions as $sesi) {
                                        if(!Auth::guard('staff')->check() && (!$sesi->gelanggang || !$sesi->gelanggang->cawangan)) {
                                            continue; 
                                        }
                                        
                                        $gelanggang_id = $sesi->gel_ID;
                                        $createdAtStamp = $sesi->created_at ? \Carbon\Carbon::parse($sesi->created_at)->format('Ymd_His') : 'manual';
                                        
                                        $groupKey = "{$gelanggang_id}_{$createdAtStamp}";
                                        
                                        if(!isset($groupedSessions[$groupKey])) {
                                            $groupedSessions[$groupKey] = [
                                                'first_session' => $sesi,
                                                'last_session' => $sesi,
                                                'all_ids' => [],
                                                'all_sessions' => []
                                            ];
                                        }
                                        
                                        $groupedSessions[$groupKey]['last_session'] = $sesi;
                                        $groupedSessions[$groupKey]['all_ids'][] = $sesi->id ?? $sesi->session_ID;
                                        $groupedSessions[$groupKey]['all_sessions'][] = $sesi;
                                    }

                                    uasort($groupedSessions, function($a, $b) {
                                        $timeA = $a['first_session']->start_time ? strtotime($a['first_session']->start_time) : 0;
                                        $timeB = $b['first_session']->start_time ? strtotime($b['first_session']->start_time) : 0;
                                        return $timeA <=> $timeB;
                                    });
                                @endphp

                                @if($allSessions->isEmpty())
                                    @if(Auth::guard('staff')->check())
                                        <tr>
                                            <td><b style="color: #111; font-size: 1.1em;">{{ $course->course_type }}</b></td>
                                            <td>{{ $course->instructor->name ?? 'TBA' }}</td>
                                            <td><span style="color: #888; font-style: italic;">TBA</span></td>
                                            <td data-sort="2_999999999999">
                                                <span style="color: #cc0000; font-weight: bold;">
                                                    <span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">event_busy</span> No Schedule Yet
                                                </span>
                                            </td>
                                            <td style="text-align: center;"><span style="color: #888;">-</span></td>
                                            <td style="text-align: center;">
                                                <div style="display: flex; gap: 8px; justify-content: center;">
                                                    <a href="{{ route('courses.edit', $course->course_ID ?? $course->id) }}" class="btn btn-edit" title="Edit"><span class="material-icons">edit</span></a>
                                                    <form action="{{ route('courses.destroy', $course->course_ID ?? $course->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="margin:0;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-delete" title="Delete"><span class="material-icons">delete</span></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                @else
                                    @foreach($groupedSessions as $groupKey => $group)
                                        @php
                                            $firstSesi = $group['first_session'];
                                            $lastSesi = $group['last_session'];
                                            $sessionCount = count($group['all_sessions']);
                                            $isWeekly = $sessionCount > 1;
                                            
                                            $session_id_to_check = $firstSesi->id ?? $firstSesi->session_ID;
                                            $limit_capacity = $firstSesi->capacity ?? 10; 
                                            
                                            try {
                                                $current_enrolled = \DB::table('enrollments')->where('session_ID', $session_id_to_check)->count();
                                            } catch(\Exception $e) {
                                                $current_enrolled = 0; 
                                            }
                                            $isFull = $current_enrolled >= $limit_capacity;
                                            
                                            $allPast = true;
                                            foreach($group['all_sessions'] as $s) {
                                                if(!\Carbon\Carbon::parse($s->start_time)->isPast()) {
                                                    $allPast = false;
                                                    break;
                                                }
                                            }

                                            $hasEnrolled = false;
                                            if(Auth::guard('web')->check()) {
                                                $user_id = Auth::guard('web')->user()->user_ID ?? Auth::guard('web')->id();
                                                try {
                                                    $hasEnrolled = \DB::table('enrollments')
                                                        ->where('user_ID', $user_id)
                                                        ->whereIn('session_ID', $group['all_ids']) 
                                                        ->exists();
                                                } catch(\Exception $e) {}
                                            }
                                            
                                            $hari = \Carbon\Carbon::parse($firstSesi->start_time)->format('l');
                                            $tarikhMula = \Carbon\Carbon::parse($firstSesi->start_time)->format('d M Y');
                                            $tarikhAkhir = \Carbon\Carbon::parse($lastSesi->start_time)->format('d M Y');
                                            $masaMula = \Carbon\Carbon::parse($firstSesi->start_time)->format('h:i A');
                                            $masaAkhir = \Carbon\Carbon::parse($firstSesi->end_time)->format('h:i A');
                                        @endphp

                                        <tr style="{{ $allPast ? 'background-color: #fcfcfc;' : '' }}">
                                            <td><b style="color: {{ $allPast ? '#888' : '#111' }}; font-size: 1.1em;">{{ $course->course_type }}</b></td>
                                            
                                            <td style="{{ $allPast ? 'color: #888;' : '' }}">{{ $course->instructor->name ?? 'TBA' }}</td>
                                            
                                            <td style="{{ $allPast ? 'color: #888;' : '' }}">
                                                @if($firstSesi->gelanggang && $firstSesi->gelanggang->cawangan)
                                                    <b>{{ $firstSesi->gelanggang->cawangan->caw_name }}</b>
                                                @else
                                                    <span style="color: #888; font-style: italic;">TBA</span>
                                                @endif
                                            </td>

                                            <td data-sort="{{ $allPast ? '1' : '0' }}_{{ \Carbon\Carbon::parse($firstSesi->start_time)->format('YmdHi') }}">
                                                <div style="color: {{ $allPast ? '#888' : '#111' }}; font-weight: bold; margin-bottom: 5px; display: flex; align-items: center; gap: 5px;">
                                                    <span class="material-icons" style="font-size: 18px; color: {{ $allPast ? '#888' : '#cc0000' }};">location_on</span>
                                                    {{ $firstSesi->gelanggang->gel_name ?? 'TBA' }}
                                                </div>
                                                
                                                <div style="font-size: 0.9em; color: #444; padding-left: 2px;">
                                                    @if($isWeekly)
                                                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px; font-weight: bold; color: {{ $allPast ? '#999' : '#17a2b8' }};">
                                                            <span class="material-icons" style="font-size: 14px;">autorenew</span> 
                                                            Every {{ $hari }}
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px; color: {{ $allPast ? '#aaa' : '#555' }};">
                                                            <span class="material-icons" style="font-size: 14px; visibility: hidden;">date_range</span> 
                                                            <span class="material-icons" style="font-size: 14px; margin-left: -20px;">date_range</span> 
                                                            {{ $tarikhMula }} to {{ $tarikhAkhir }} <b style="color: #cc0000;">({{ $sessionCount }} Classes)</b>
                                                        </div>
                                                    @else
                                                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px;">
                                                            <span class="material-icons" style="font-size: 14px; color: #888;">calendar_today</span> 
                                                            <b style="{{ $allPast ? 'color: #999; text-decoration: line-through;' : 'color: #222;' }}">
                                                                {{ $tarikhMula }}
                                                            </b> 
                                                        </div>
                                                    @endif
                                                    
                                                    <div style="display: flex; align-items: center; gap: 6px;">
                                                        <span class="material-icons" style="font-size: 14px; color: #888; visibility: hidden;">schedule</span> 
                                                        <span class="material-icons" style="font-size: 14px; color: #888; margin-left: -20px;">schedule</span> 
                                                        <span style="{{ $allPast ? 'color: #999;' : '' }}">
                                                            {{ $masaMula }} - {{ $masaAkhir }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td style="text-align: center;">
                                                @if($allPast)
                                                    <span style="font-weight: bold; color: #888; font-size: 1.1em;">{{ $current_enrolled }}/{{ $limit_capacity }}</span>
                                                    <div style="font-size: 0.7em; color: #888; text-transform: uppercase;">Closed</div>
                                                @elseif($isFull)
                                                    <span style="font-weight: bold; color: #cc0000; font-size: 1.1em;">{{ $current_enrolled }}/{{ $limit_capacity }}</span>
                                                    <div style="font-size: 0.7em; color: #cc0000; text-transform: uppercase;">Full</div>
                                                @else
                                                    <span style="font-weight: bold; color: #28a745; font-size: 1.1em;">{{ $current_enrolled }}/{{ $limit_capacity }}</span>
                                                    <div style="font-size: 0.7em; color: #666; text-transform: uppercase;">Available</div>
                                                @endif
                                            </td>
                                            
                                            <td style="text-align: center; vertical-align: top;">
                                                <div style="display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: center;">
                                                    @if(Auth::guard('staff')->check())
                                                        <div style="display: flex; gap: 5px;">
                                                            <a href="{{ route('courses.edit', $course->course_ID ?? $course->id) }}" class="btn btn-edit" title="Edit"><span class="material-icons">edit</span></a>
                                                            <form action="{{ route('courses.destroy', $course->course_ID ?? $course->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="margin:0;">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-delete" title="Delete"><span class="material-icons">delete</span></button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        @if($hasEnrolled)
                                                            <button type="button" class="btn btn-enrolled" title="You have enrolled in this class"><span class="material-icons">check_circle</span> Enrolled</button>
                                                        @elseif($allPast)
                                                            <button type="button" class="btn btn-ended" title="Class has ended"><span class="material-icons">history</span> Ended</button>
                                                        @elseif($isFull)
                                                            <button type="button" class="btn btn-disabled"><span class="material-icons">do_not_disturb_alt</span> Full</button>
                                                        @else
                                                            <form action="{{ route('enroll.store', $course->course_ID ?? $course->id) }}" method="POST" style="margin:0;">
                                                                @csrf
                                                                @foreach($group['all_ids'] as $sid)
                                                                    <input type="hidden" name="session_ids[]" value="{{ $sid }}">
                                                                @endforeach
                                                                <button type="submit" class="btn btn-join"><span class="material-icons">how_to_reg</span> Enroll {{ $isWeekly ? 'All' : '' }}</button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                    
                                                    @if($isWeekly && Auth::guard('web')->check())
                                                        <details style="text-align: left; background: #fffdf5; border: 1px solid #ffcc00; border-radius: 6px; padding: 5px; width: 100%; box-sizing: border-box;">
                                                            <summary style="cursor: pointer; font-weight: bold; color: #111; outline: none; padding: 2px; font-size: 0.75em; display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                                <span class="material-icons" style="font-size: 14px;">visibility</span> Show Dates
                                                            </summary>
                                                            <div style="margin-top: 5px; display: flex; flex-direction: column; gap: 4px;">
                                                                @foreach($group['all_sessions'] as $sesi)
                                                                    @php $isPastSesi = \Carbon\Carbon::parse($sesi->start_time)->isPast(); @endphp
                                                                    <div style="font-size: 0.75em; text-align: center; border-bottom: 1px dashed #eee; padding-bottom: 2px; {{ $isPastSesi ? 'text-decoration: line-through; color: #999;' : 'color: #333;' }}">
                                                                        {{ \Carbon\Carbon::parse($sesi->start_time)->format('d M') }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </details>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

            <div class="footer-nav">
                @if(Auth::guard('staff')->check())
                    <a href="{{ route('staff.dashboard') }}" class="back-link"><span class="material-icons">arrow_back</span> Back to Admin Dashboard</a>
                @elseif(Auth::guard('instructor')->check())
                    <a href="{{ route('instructor.dashboard') }}" class="back-link"><span class="material-icons">arrow_back</span> Back to Instructor Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="back-link"><span class="material-icons">arrow_back</span> Back to Member Dashboard</a>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            if ($('#memberTable').length > 0) {
                var table = $('#memberTable').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [5, 10, 25, 50],
                    "language": {
                        "search": "Quick Search:",
                        "lengthMenu": "Show _MENU_ entries"
                    },
                    "order": [[3, "asc"]]
                });

                var courseColIndex = 0; 
                var branchColIndex = 2; 

                // --- FUNGSI FILTER BAHARU ---
                var courseList = [];
                table.column(courseColIndex).nodes().to$().each(function () {
                    // Tangkap teks dan buang segala space tersembunyi
                    var val = $(this).text().replace(/\s+/g, ' ').trim(); 
                    if (val && !courseList.includes(val)) {
                        courseList.push(val);
                    }
                });
                
                $('#filter-course').empty().append('<option value="">-- All Courses --</option>');
                courseList.sort().forEach(function(val) {
                    $('#filter-course').append('<option value="' + val + '">' + val + '</option>');
                });

                var branchList = [];
                table.column(branchColIndex).nodes().to$().each(function () {
                    var val = $(this).text().replace(/\s+/g, ' ').trim();
                    if (val && val !== 'TBA' && !branchList.includes(val)) {
                        branchList.push(val);
                    }
                });
                
                $('#filter-branch').empty().append('<option value="">-- All Branches --</option>');
                branchList.sort().forEach(function(val) {
                    $('#filter-branch').append('<option value="' + val + '">' + val + '</option>');
                });

                $('#filter-course').on('change', function () {
                    var val = $(this).val();
                    var searchVal = val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '';
                    table.column(courseColIndex).search(searchVal, true, false).draw();
                });

                $('#filter-branch').on('change', function () {
                    var val = $(this).val();
                    var searchVal = val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '';
                    table.column(branchColIndex).search(searchVal, true, false).draw();
                });
            } else {
                $('#instructorTable').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [5, 10, 25, 50],
                    "language": {
                        "search": "Quick Search:",
                        "lengthMenu": "Show _MENU_ entries"
                    },
                    "order": [] 
                });
            }
        });
    </script>
</body>
</html>