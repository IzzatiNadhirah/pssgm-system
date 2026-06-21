<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        body, button, input, select, textarea, table, th, td, div, a, p, h1, h2, h3, h4, h5, h6 { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; 
        }
        .material-icons { font-family: 'Material Icons' !important; }

        body { background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1100px; width: 100%; background: white; padding: 35px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }
        
        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        .course-section { margin-bottom: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        
        .course-header { 
            background: #111; color: #ffcc00; padding: 15px 20px; font-size: 1.1em; font-weight: bold; 
            display: flex; justify-content: space-between; align-items: center; 
            border-left: 4px solid #cc0000; cursor: pointer; transition: background 0.3s;
        }
        .course-header:hover { background: #222; }
        
        .toggle-icon { transition: transform 0.3s; color: white; }
        .toggle-icon.open { transform: rotate(180deg); }

        .course-body { display: none; padding: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 20px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; cursor: pointer; }
        tr:hover { background-color: #fffdf5; }

        .empty-state { text-align: center; padding: 30px; color: #888; font-style: italic; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: left; }
        
        /* KITA EJAS SINI: Tukar warna back link supaya bukan merah */
        .back-link { color: #111; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #ffcc00; }

        .attendance-badge { background: #111; color: #ffcc00; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold; }

        /* Datatables Overrides */
        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; background: white; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #111; }
        .dataTables_wrapper .dataTables_length select { border: 2px solid #eee; border-radius: 6px; padding: 5px; background: white; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ffcc00 !important; color: #111 !important; border: none; font-weight: bold; border-radius: 6px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: none; border-radius: 6px; }
        .dataTables_wrapper .dataTables_info { font-size: 0.9em; color: #666; }

        .dt-buttons { margin-bottom: 15px; }
        
        /* KITA EJAS SINI: Tukar butang Print jadi warna Hitam/Emas */
        .dt-button.btn-print { background: #111 !important; color: #ffcc00 !important; border: none !important; border-radius: 6px !important; padding: 8px 16px !important; font-weight: bold !important; transition: 0.2s !important; }
        .dt-button.btn-print:hover { background: #333 !important; transform: translateY(-2px); color: white !important; }

        /* MODAL BENGKUNG */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; width: 90%; max-width: 450px; border-radius: 10px; overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.3); }
        .modal-header { background: #111; color: #ffcc00; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #ffcc00; }
        .modal-header h3 { margin: 0; font-size: 1.1em; display: flex; align-items: center; gap: 8px; }
        .close-modal { background: none; border: none; color: white; cursor: pointer; font-size: 20px; }
        .close-modal:hover { color: #ffcc00; }
        .modal-body { padding: 25px 20px; max-height: 400px; overflow-y: auto; }
        .student-name-link { color: #111; text-decoration: none; border-bottom: 1px dashed #111; cursor: pointer; transition: 0.2s; }
        .student-name-link:hover { color: #ffcc00; border-bottom-color: #ffcc00; }
        .history-list { list-style: none; padding: 0; margin: 0; }
        .history-list li { padding: 12px 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start; }
        .history-list li:last-child { border-bottom: none; }

        @media print {
            body { background-color: white !important; color: black !important; }
            .content-area { padding: 0 !important; }
            .container { box-shadow: none !important; border: none !important; max-width: 100% !important; padding: 0 !important; }
            .footer-nav, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate, .dt-buttons { display: none !important; }
            .course-header { border-left: none !important; background-color: #f1f1f1 !important; color: #111 !important; }
            .course-header span { background-color: transparent !important; color: #111 !important; }
            .course-body { display: block !important; padding: 0 !important;} 
            th { background-color: #f1f1f1 !important; color: black !important; border-bottom: 2px solid black !important; }
            td { color: black !important; border-bottom: 1px solid #ccc !important; }
            .student-name-link { text-decoration: none !important; border: none !important; color: black !important; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons" style="color: #111;">groups</span>
                <h2>Enrolled Students List</h2>
            </div>

            @if($courses->isEmpty())
                <div class="empty-state" style="border: 2px dashed #ddd; border-radius: 8px; padding: 50px;">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">assignment_late</span>
                    <p>You have not been assigned to any courses yet.</p>
                </div>
            @else
                @foreach($courses as $course)
                    
                    @php
                        $sessions = \App\Models\SessionTimetable::with('gelanggang')
                                        ->where('course_ID', $course->course_ID ?? $course->id)
                                        ->orderBy('start_time', 'desc')
                                        ->get();
                                        
                        $groupedSessions = [];
                        foreach($sessions as $sesi) {
                            $gelId = $sesi->gel_ID ?? ($sesi->gelanggang ? $sesi->gelanggang->id : 'none');
                            $createdAtStamp = $sesi->created_at ? \Carbon\Carbon::parse($sesi->created_at)->format('Ymd_His') : 'manual';
                            $groupKey = "{$gelId}_{$createdAtStamp}";
                            
                            if(!isset($groupedSessions[$groupKey])) {
                                $groupedSessions[$groupKey] = [];
                            }
                            $groupedSessions[$groupKey][] = $sesi;
                        }
                    @endphp

                    @if(empty($groupedSessions))
                        <div class="course-section">
                            <div class="course-header" title="Click to expand">
                                <div>
                                    <span class="material-icons" style="vertical-align: bottom; font-size: 20px; margin-right: 5px;">menu_book</span>
                                    {{ $course->course_type }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">Total Enrolled: 0</span>
                                    <span class="material-icons toggle-icon">expand_more</span>
                                </div>
                            </div>
                            <div class="course-body">
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
                                $enrollments = \App\Models\Enrollment::with('user')->where('session_ID', $first_session_id)->get();
                            @endphp

                            <div class="course-section">
                                <div class="course-header" title="Click to view students list">
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
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">
                                            Enrolled: {{ $enrollments->count() }} 
                                        </span>
                                        <span class="material-icons toggle-icon">expand_more</span>
                                    </div>
                                </div>

                                <div class="course-body">
                                    @if($enrollments->isEmpty())
                                        <div class="empty-state">No students have enrolled in this specific class yet.</div>
                                    @else
                                        
                                        <div style="overflow-x: auto; background: white; border-radius: 8px;">
                                            <table class="dataTable" data-course="{{ $course->course_type }} ({{ $firstSesi->gelanggang->gel_name ?? 'Class' }})">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50px;">No.</th>
                                                        <th>Student Name</th>
                                                        <th>Bengkung Level</th>
                                                        <th style="text-align: center;">Overall Attendance</th>
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
                                                                                            
                                                        $bengkungHistory = \App\Models\PromotionRequest::with('instructor')
                                                                                                       ->where('user_ID', $user_id)
                                                                                                       ->where('status', 'Approved')
                                                                                                       ->orderBy('created_at', 'desc')
                                                                                                       ->get();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <a href="javascript:void(0)" 
                                                               class="student-name-link" 
                                                               onclick="openStudentModal(this)"
                                                               data-name="{{ $enrollment->user->name ?? 'Unknown Student' }}"
                                                               data-ic="{{ $enrollment->user->icNo ?? $enrollment->user->ic_no ?? 'N/A' }}"
                                                               data-history="{{ json_encode($bengkungHistory) }}">
                                                               <b>{{ $enrollment->user->name ?? 'Unknown Student' }}</b>
                                                            </a>
                                                        </td>
                                                        <td>{{ $enrollment->user->bengkung_level ?? 'N/A' }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="attendance-badge">{{ $total_attended }} / {{ $sessionCount }}</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif

            <div class="footer-nav">
                <a href="{{ route('instructor.dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Instructor Dashboard
                </a>
            </div>

        </div>
    </div>

    {{-- KOTAK POP-UP MODAL --}}
    <div class="modal-overlay" id="studentModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><span class="material-icons">badge</span> Student Details</h3>
                <button class="close-modal" onclick="closeStudentModal()"><span class="material-icons">close</span></button>
            </div>
            <div class="modal-body">
                <h2 id="modal-name" style="margin-top: 0; margin-bottom: 5px; font-size: 1.3em;"></h2>
                <p style="color: #666; font-size: 0.9em; margin-top: 0; margin-bottom: 20px;">
                    <span class="material-icons" style="font-size: 14px; vertical-align: middle;">pin</span> IC: <span id="modal-ic"></span>
                </p>

                <h4 style="border-bottom: 2px solid #eee; padding-bottom: 5px; margin-bottom: 0; color: #111;">
                    <span class="material-icons" style="font-size: 16px; vertical-align: bottom; color: #ffcc00;">military_tech</span> Bengkung History
                </h4>
                <ul class="history-list" id="modal-history-list">
                    <!-- Data disuntik oleh JavaScript kat sini -->
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        // JavaScript untuk kawal modal
        function openStudentModal(element) {
            const name = element.getAttribute('data-name');
            const ic = element.getAttribute('data-ic');
            const historyJson = element.getAttribute('data-history');
            
            document.getElementById('modal-name').innerText = name;
            document.getElementById('modal-ic').innerText = ic;

            const historyListEl = document.getElementById('modal-history-list');
            historyListEl.innerHTML = ''; 

            try {
                const historyData = JSON.parse(historyJson);
                
                if(!historyData || historyData.length === 0) {
                    historyListEl.innerHTML = '<li><span style="color:#888; font-style:italic;">No approved belt promotion history yet.</span></li>';
                } else {
                    historyData.forEach(record => {
                        const li = document.createElement('li');
                        
                        const dateObj = new Date(record.updated_at || record.created_at);
                        const dateStr = dateObj.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                        
                        const instructorName = record.instructor ? record.instructor.name : 'Unknown Instructor';
                        const previousBengkung = record.current_bengkung || 'N/A';

                        li.innerHTML = `
                            <div style="flex: 1;">
                                <b style="font-size: 1.1em; color: #111;">${record.requested_bengkung}</b>
                                <div style="font-size: 0.85em; color: #666; margin-top: 4px; display: flex; flex-direction: column; gap: 2px;">
                                    <span><span class="material-icons" style="font-size:14px; vertical-align:text-bottom; color:#888;">history</span> Prev: <b>${previousBengkung}</b></span>
                                    <span><span class="material-icons" style="font-size:14px; vertical-align:text-bottom; color:#888;">person</span> By: ${instructorName}</span>
                                </div>
                            </div>
                            <div style="text-align: right; color: #666; font-size: 0.85em; font-family: monospace;">
                                <span class="material-icons" style="font-size:14px; vertical-align:text-bottom;">event_available</span><br>
                                ${dateStr}
                            </div>
                        `;
                        historyListEl.appendChild(li);
                    });
                }
            } catch (e) {
                historyListEl.innerHTML = '<li><span style="color:red;">Error loading history.</span></li>';
            }

            document.getElementById('studentModal').classList.add('active');
        }

        function closeStudentModal() {
            document.getElementById('studentModal').classList.remove('active');
        }

        $(document).ready(function() {
            // ACCORDION SCRIPT
            $('.course-header').on('click', function() {
                var body = $(this).next('.course-body');
                var icon = $(this).find('.toggle-icon');
                
                body.slideToggle(300, function() {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });
                
                icon.toggleClass('open');
            });

            // DATATABLES SCRIPT 
            $('.dataTable').each(function() {
                var courseName = $(this).data('course'); 
                
                $(this).DataTable({
                    "paging": true, 
                    "pageLength": 25,
                    "info": true,
                    "language": {
                        "search": "Search Student:"
                    },
                    "order": [], 
                    "dom": '<"dt-buttons"B>frtip', 
                    "buttons": [
                        {
                            extend: 'print',
                            text: '<span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">print</span> Print List',
                            className: 'btn-print',
                            title: 'Enrolled Students List - ' + courseName, 
                            exportOptions: {
                                columns: [0, 1, 2, 3] 
                            }
                        }
                    ]
                });
            });
        });
    </script>
</body>
</html>