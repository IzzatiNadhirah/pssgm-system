<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Attendance - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body, button, input, select, textarea, table, th, td, div, a, p, h1, h2, h3, h4, h5, h6 { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; 
        }
        .material-icons {
            font-family: 'Material Icons' !important; 
        }

        body { background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1100px; width: 100%; background: white; padding: 35px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #17a2b8; border-bottom: 8px solid #ffcc00; 
        }
        
        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 36px; color: #17a2b8; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        .course-section { margin-bottom: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        
        .course-header { 
            background: #111; color: #ffcc00; padding: 15px 20px; font-size: 1.1em; font-weight: bold; 
            display: flex; justify-content: space-between; align-items: center; 
            border-left: 4px solid #17a2b8; cursor: pointer; transition: background 0.3s;
        }
        .course-header:hover { background: #222; }
        
        .toggle-icon { transition: transform 0.3s; color: white; }
        .toggle-icon.open { transform: rotate(180deg); }

        .course-body { display: none; padding: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 20px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; vertical-align: middle; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; cursor: pointer; }
        tr:hover { background-color: #fffdf5; }

        .empty-state { text-align: center; padding: 30px; color: #888; font-style: italic; }
        
        .attendance-controls { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-top: 15px; padding: 15px; background: #e0f7fa; 
            border: 1px solid #17a2b8; border-radius: 8px; flex-wrap: wrap; gap: 15px;
        }
        
        .session-dropdown {
            padding: 10px; border: 2px solid #b8daff; border-radius: 6px; 
            font-family: inherit; font-size: 0.95em; outline: none; transition: 0.2s; background: white;
            cursor: pointer; font-weight: bold; color: #111;
        }
        .session-dropdown:focus { border-color: #17a2b8; }
        
        .btn-save-attendance { 
            background-color: #28a745; color: white; border: none; padding: 10px 20px; 
            border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; 
            align-items: center; gap: 8px; font-size: 1em; transition: 0.2s; text-transform: uppercase; margin: 0;
        }
        .btn-save-attendance:hover { background-color: #218838; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(40,167,69,0.3); }

        .btn-save-attendance.disabled {
            background-color: #6c757d; cursor: not-allowed; box-shadow: none; transform: none; opacity: 0.8;
        }

        .custom-checkbox { transform: scale(1.6); cursor: pointer; accent-color: #28a745; }
        
        .attendance-badge { background: #17a2b8; color: white; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold; }

        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: left; }
        .back-link { color: #17a2b8; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 0.95em; }
        .back-link:hover { transform: translateX(-5px); color: #111; }

        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; background: white; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #17a2b8; }

        .alert-box { width: 100%; box-sizing: border-box; margin-bottom: 20px;}
        .alert { padding: 15px; border-radius: 8px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }

        .custom-pagination { display: flex; justify-content: center; gap: 8px; margin-top: 25px; align-items: center; flex-wrap: wrap; }
        .page-btn { background: #f1f1f1; border: 1px solid #ddd; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s; color: #333; font-size: 0.9em; }
        .page-btn:hover:not(:disabled) { background: #e2e2e2; }
        .page-btn.active { background: #17a2b8; color: white; border-color: #17a2b8; pointer-events: none; }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .page-length-control { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px dashed #eee; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">fact_check</span>
                <h2>Class Attendance Management</h2>
            </div>

            @if(session('success'))
                <div class="alert-box">
                    <div class="alert alert-success">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-box">
                    <div class="alert alert-error">{{ session('error') }}</div>
                </div>
            @endif

            @if($courses->isEmpty())
                <div class="empty-state" style="border: 2px dashed #ddd; border-radius: 8px; padding: 50px;">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">assignment_late</span>
                    <p>You have not been assigned to any courses yet.</p>
                </div>
            @else
                
                {{-- Wrapper utama untuk senarai laci supaya Pagination berfungsi --}}
                <div id="accordion-container">
                    
                    <div class="page-length-control">
                        <span style="font-weight: bold; color: #111; text-transform: uppercase;">Class Directory</span>
                        <div>
                            <label for="perPage" style="font-size: 0.9em; font-weight: bold; color: #555;">Show </label>
                            <select id="perPage" class="session-dropdown" style="padding: 5px 10px; width: auto; display: inline-block; font-size: 0.9em; border-color: #ddd;">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <span style="font-size: 0.9em; font-weight: bold; color: #555;"> Classes</span>
                        </div>
                    </div>

                    @foreach($courses as $course)
                        
                        @php
                            $sessions = \App\Models\SessionTimetable::with('gelanggang')
                                            ->where('course_ID', $course->course_ID ?? $course->id)
                                            ->orderBy('start_time', 'desc')
                                            ->get();
                                            
                            $groupedSessions = [];
                            foreach($sessions as $sesi) {
                                $gelId = $sesi->gel_ID ?? ($sesi->gelanggang ? $sesi->gelanggang->id : 'none');
                                $dayOfWeek = \Carbon\Carbon::parse($sesi->start_time)->format('l');
                                $timeStart = \Carbon\Carbon::parse($sesi->start_time)->format('H:i');
                                
                                $groupKey = "{$gelId}_{$dayOfWeek}_{$timeStart}";
                                
                                if(!isset($groupedSessions[$groupKey])) {
                                    $groupedSessions[$groupKey] = [];
                                }
                                $groupedSessions[$groupKey][] = $sesi;
                            }
                        @endphp

                        @if(empty($groupedSessions))
                            <div class="course-section">
                                <div class="course-header">
                                    <div>
                                        <span class="material-icons" style="vertical-align: bottom; font-size: 20px; margin-right: 5px;">menu_book</span>
                                        {{ $course->course_type }}
                                    </div>
                                    <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">No Sessions</span>
                                </div>
                                <div class="course-body" style="display: block;">
                                    <div class="empty-state">No class schedules have been set for this course yet.</div>
                                </div>
                            </div>
                        @else
                            
                            @foreach($groupedSessions as $groupKey => $group)
                                @php
                                    usort($group, function($a, $b) { return strtotime($a->start_time) <=> strtotime($b->start_time); });
                                    
                                    $firstSesi = $group[0];
                                    $lastSesi = $group[count($group) - 1];
                                    $sessionCount = count($group);
                                    $isWeekly = $sessionCount > 1;
                                    
                                    $hari = \Carbon\Carbon::parse($firstSesi->start_time)->format('l');
                                    $tarikhMula = \Carbon\Carbon::parse($firstSesi->start_time)->format('d M Y');
                                    $tarikhAkhir = \Carbon\Carbon::parse($lastSesi->start_time)->format('d M Y');
                                    $masaMula = \Carbon\Carbon::parse($firstSesi->start_time)->format('h:i A');
                                    
                                    $all_session_ids = array_map(function($s) { return $s->session_ID ?? $s->id; }, $group);
                                    
                                    $first_session_id = $firstSesi->session_ID ?? $firstSesi->id;
                                    $enrollments = \App\Models\Enrollment::with('user')->whereIn('session_ID', $all_session_ids)->get()->unique('user_ID');
                                    
                                    $todayStr = date('Y-m-d');
                                    $defaultSesi = $firstSesi;
                                    foreach($group as $s) {
                                        if (\Carbon\Carbon::parse($s->start_time)->format('Y-m-d') == $todayStr) {
                                            $defaultSesi = $s;
                                            break;
                                        }
                                    }
                                    
                                    $defaultSesiId = $defaultSesi->id ?? $defaultSesi->session_ID;
                                    $isAttendanceSubmitted = \App\Models\Attendance::where('session_id', $defaultSesiId)
                                                                ->where('date', \Carbon\Carbon::parse($defaultSesi->start_time)->format('Y-m-d'))
                                                                ->exists();

                                    // --- KITA EJAS SINI: Logik untuk tentukan sama ada kelas dah tamat ---
                                    $isEnded = false;
                                    if ($lastSesi && $lastSesi->start_time) {
                                        $tarikhMulaTerakhir = \Carbon\Carbon::parse($lastSesi->start_time)->format('Y-m-d');
                                        $masaTamatTerakhir = $lastSesi->end_time;
                                        
                                        if ($masaTamatTerakhir) {
                                            // Check kalau masaTamat takde date gabung
                                            if (strlen($masaTamatTerakhir) <= 8) {
                                                $gabunganTamat = \Carbon\Carbon::parse($tarikhMulaTerakhir . ' ' . $masaTamatTerakhir);
                                            } else {
                                                $gabunganTamat = \Carbon\Carbon::parse($masaTamatTerakhir);
                                            }
                                        } else {
                                            // Kalau takde end_time langsung dalam database, kita anggap kelas habis 3 jam lepas start_time
                                            $gabunganTamat = \Carbon\Carbon::parse($lastSesi->start_time)->addHours(3);
                                        }
                                        
                                        $isEnded = $gabunganTamat->isPast();
                                    }
                                @endphp

                                <div class="course-section">
                                    <div class="course-header" title="Click to expand & mark attendance">
                                        <div style="flex: 1;">
                                            <span class="material-icons" style="vertical-align: text-bottom; font-size: 20px; margin-right: 5px;">menu_book</span>
                                            {{ $course->course_type }}
                                            
                                            <div style="margin-top: 5px; font-size: 0.85em; color: #ddd; font-weight: normal; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                                <span><span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">location_on</span> {{ $firstSesi->gelanggang->gel_name ?? 'TBA' }}</span>
                                                <span style="color:#666;">|</span>
                                                @if($isWeekly)
                                                    <span><span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">autorenew</span> Every {{ $hari }}</span>
                                                    <span style="color:#666;">|</span>
                                                    <span><span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">date_range</span> {{ $tarikhMula }} - {{ $tarikhAkhir }}</span>
                                                    <span style="color:#ffcc00; font-weight: bold;">({{ $sessionCount }} Classes)</span>
                                                @else
                                                    <span><span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">calendar_today</span> {{ $tarikhMula }}</span>
                                                @endif
                                                <span style="color:#666;">|</span>
                                                <span><span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">schedule</span> {{ $masaMula }}</span>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            
                                            {{-- KITA EJAS SINI: Letak Badge Status Active/Ended --}}
                                            @if($isEnded)
                                                <span style="font-size: 0.8em; background: #6c757d; padding: 4px 10px; border-radius: 12px; color: white; display: flex; align-items: center; gap: 4px;">
                                                    <span class="material-icons" style="font-size: 14px;">history</span> Ended
                                                </span>
                                            @else
                                                <span style="font-size: 0.8em; background: #28a745; padding: 4px 10px; border-radius: 12px; color: white; display: flex; align-items: center; gap: 4px;">
                                                    <span class="material-icons" style="font-size: 14px;">play_circle</span> Active
                                                </span>
                                            @endif

                                            <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">
                                                Enrolled: {{ $enrollments->count() }} 
                                            </span>
                                            <span class="material-icons toggle-icon">expand_more</span>
                                        </div>
                                    </div>

                                    <div class="course-body">
                                        @if($enrollments->isEmpty())
                                            <div class="empty-state">No students have enrolled in this class yet.</div>
                                        @else
                                            
                                            <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm_{{ $groupKey }}">
                                                @csrf
                                                
                                                <div style="overflow-x: auto; background: white; border-radius: 8px;">
                                                    <table class="dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 50px;">No.</th>
                                                                <th>Student Name</th>
                                                                <th style="text-align: center;">Total Attended</th>
                                                                <th style="text-align: center; color: #28a745; width: 100px;">Present?</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($enrollments as $index => $enrollment)
                                                            @php
                                                                $user_id = $enrollment->user->user_ID ?? $enrollment->user->id;
                                                                
                                                                $total_attended = \App\Models\Attendance::whereIn('session_id', $all_session_ids)
                                                                                                ->where('user_id', $user_id)
                                                                                                ->where('status', 'Hadir')
                                                                                                ->count();
                                                                                                
                                                                $has_attended_today = \App\Models\Attendance::where('session_id', $defaultSesiId)
                                                                                                ->where('user_id', $user_id)
                                                                                                ->where('date', \Carbon\Carbon::parse($defaultSesi->start_time)->format('Y-m-d'))
                                                                                                ->where('status', 'Hadir')
                                                                                                ->exists();
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <b>{{ $enrollment->user->name ?? 'Unknown Student' }}</b><br>
                                                                    <span style="font-size: 0.8em; color: #666;">Bengkung: {{ $enrollment->user->bengkung_level ?? 'N/A' }}</span>
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    <span class="attendance-badge">{{ $total_attended }} / {{ $sessionCount }} Classes</span>
                                                                </td>
                                                                
                                                                <td style="text-align: center;">
                                                                    <input type="checkbox" name="attendance[]" value="{{ $user_id }}" class="custom-checkbox cb-attendance" data-user="{{ $user_id }}" title="Tick if present" {{ $has_attended_today ? 'checked' : '' }} {{ $isAttendanceSubmitted ? 'disabled' : '' }}>
                                                                    @if($isAttendanceSubmitted && $has_attended_today)
                                                                        <input type="hidden" name="attendance[]" value="{{ $user_id }}">
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="attendance-controls">
                                                    <div style="flex-grow: 1;">
                                                        <p style="margin: 0; font-size: 0.85em; color: #0c5460; font-style: italic;">
                                                            * Tick the box for students who are present. Leave blank if absent.
                                                        </p>
                                                    </div>
                                                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                                                        
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <label style="margin:0; font-size:0.9em; font-weight:bold; color:#0c5460;">Class Date:</label>
                                                            
                                                            <select name="session_id" class="session-dropdown" required id="dropdown_{{ str_replace(':', '', $groupKey) }}" onchange="updateAttendanceStatus('{{ str_replace(':', '', $groupKey) }}')">
                                                                @foreach($group as $s)
                                                                    @php 
                                                                        $s_id = $s->id ?? $s->session_ID; 
                                                                        $s_date = \Carbon\Carbon::parse($s->start_time)->format('Y-m-d');
                                                                        $s_display = \Carbon\Carbon::parse($s->start_time)->format('d M Y');
                                                                        
                                                                        $statusSubmit = \App\Models\Attendance::where('session_id', $s_id)
                                                                                            ->where('date', $s_date)
                                                                                            ->exists() ? 'true' : 'false';
                                                                    @endphp
                                                                    <option value="{{ $s_id }}" data-date="{{ $s_date }}" data-submitted="{{ $statusSubmit }}" {{ $s == $defaultSesi ? 'selected' : '' }}>
                                                                        {{ $s_display }} {!! $statusSubmit == 'true' ? '&#10003; (Saved)' : '' !!}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            
                                                            <input type="hidden" name="attendance_date" id="date_{{ str_replace(':', '', $groupKey) }}" value="{{ \Carbon\Carbon::parse($defaultSesi->start_time)->format('Y-m-d') }}">
                                                        </div>

                                                        <button type="submit" id="btnSave_{{ str_replace(':', '', $groupKey) }}" class="btn-save-attendance {{ $isAttendanceSubmitted ? 'disabled' : '' }}" {{ $isAttendanceSubmitted ? 'disabled' : '' }}>
                                                            <span class="material-icons">{{ $isAttendanceSubmitted ? 'check_circle' : 'how_to_reg' }}</span> 
                                                            {{ $isAttendanceSubmitted ? 'Attendance Saved' : 'Save Attendance' }}
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                            </form>

                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
                
                {{-- Kontena untuk nombor page --}}
                <div class="custom-pagination" id="custom-pagination"></div>
                
            @endif

            <div class="footer-nav">
                <a href="{{ route('instructor.dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Instructor Dashboard
                </a>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // ACCORDION
            $('.course-header').on('click', function() {
                var body = $(this).next('.course-body');
                var icon = $(this).find('.toggle-icon');
                
                body.slideToggle(300, function() {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });
                icon.toggleClass('open');
            });

            // DATATABLES
            $('.dataTable').each(function() {
                $(this).DataTable({
                    "paging": false, 
                    "info": false,
                    "language": {
                        "search": "Search Student:"
                    },
                    "order": [] 
                });
            });

            // KITA EJAS SINI: Skrip untuk Custom Pagination
            var $sections = $('.course-section');
            var totalItems = $sections.length;
            var itemsPerPage = parseInt($('#perPage').val());
            var totalPages = 0;
            var currentPage = 1;

            function updatePaginationVars() {
                itemsPerPage = parseInt($('#perPage').val());
                totalPages = Math.ceil(totalItems / itemsPerPage);
                currentPage = 1; 
            }

            function renderPagination() {
                // Walaupun kelas tak cukup untuk page 2, kita paksa butang Prev & Next sentiasa ada
                if (totalItems === 0) {
                    $('#custom-pagination').html('');
                    return;
                }
                
                var html = '<button class="page-btn prev-btn" id="prevBtn">&laquo; Prev</button>';
                
                for (var i = 1; i <= Math.max(1, totalPages); i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    html += '<button class="page-btn page-num ' + activeClass + '" data-page="' + i + '">' + i + '</button>';
                }
                
                html += '<button class="page-btn next-btn" id="nextBtn">Next &raquo;</button>';
                
                $('#custom-pagination').html(html);
                
                // Kunci butang ikut logik page semasa
                $('#prevBtn').prop('disabled', currentPage === 1 || totalPages <= 1);
                $('#nextBtn').prop('disabled', currentPage === totalPages || totalPages <= 1);
            }

            function showPage(page) {
                currentPage = page;
                $sections.hide(); // Sembunyikan semua laci
                var start = (page - 1) * itemsPerPage;
                var end = start + itemsPerPage;
                $sections.slice(start, end).fadeIn(300); // Tunjuk ikut had
                renderPagination();
            }

            // Mula kira bila sistem loading
            if(totalItems > 0) {
                updatePaginationVars();
                showPage(1);
            }

            // Bila cikgu tukar Dropdown Show Entries (5, 10, 20)
            $('#perPage').on('change', function() {
                updatePaginationVars();
                showPage(1);
            });

            $('#custom-pagination').on('click', '.page-num', function() {
                showPage($(this).data('page'));
            });

            $('#custom-pagination').on('click', '#prevBtn', function() {
                if (currentPage > 1) showPage(currentPage - 1);
            });

            $('#custom-pagination').on('click', '#nextBtn', function() {
                if (currentPage < totalPages) showPage(currentPage + 1);
            });
        });

        // FUNGSI LOCK ATTENDANCE SAVED
        function updateAttendanceStatus(groupKey) {
            var dropdown = document.getElementById('dropdown_' + groupKey);
            var dateInput = document.getElementById('date_' + groupKey);
            var btnSave = document.getElementById('btnSave_' + groupKey);
            var form = document.getElementById('attendanceForm_' + groupKey);
            
            var selectedOption = dropdown.options[dropdown.selectedIndex];
            var dateValue = selectedOption.getAttribute('data-date');
            var isSubmitted = selectedOption.getAttribute('data-submitted') === 'true';
            
            dateInput.value = dateValue;

            var checkboxes = form.querySelectorAll('.cb-attendance');

            if(isSubmitted) {
                btnSave.classList.add('disabled');
                btnSave.disabled = true;
                btnSave.innerHTML = '<span class="material-icons">check_circle</span> Attendance Saved';
                alert("Attendance for this date (" + dateValue + ") has already been saved. You cannot modify it here.");
            } else {
                btnSave.classList.remove('disabled');
                btnSave.disabled = false;
                btnSave.innerHTML = '<span class="material-icons">how_to_reg</span> Save Attendance';
                
                checkboxes.forEach(function(cb) {
                    cb.checked = false;
                    cb.disabled = false;
                });
            }
        }
    </script>
</body>
</html>