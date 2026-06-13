<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Timetable - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
    <style>
        body, button, input, select, textarea, table { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        
        body { background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 1000px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header-area { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-title { display: flex; align-items: center; gap: 15px; }
        .header-title .material-icons { font-size: 32px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        /* --- Style untuk Tabs --- */
        .view-tabs { display: flex; gap: 10px; }
        .view-tab-btn { background: #eee; border: none; padding: 8px 15px; border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; color: #555; transition: 0.2s; }
        .view-tab-btn:hover { background: #ddd; }
        .view-tab-btn.active { background: #ffcc00; color: #111; }

        /* --- Sembunyikan content ikut tab aktif --- */
        .view-content { display: none; }
        .view-content.active { display: block; }

        /* --- Style Asal Table --- */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; font-size: 0.95em; color: #222; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; letter-spacing: 0.5px; }
        tr:hover { background-color: #fffdf5; }
        
        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; }
        .empty-state .material-icons { font-size: 48px; margin-bottom: 10px; color: #ccc; }
        .empty-state h3 { margin-bottom: 5px; color: #333; }
        .empty-state p { font-size: 0.95em; }
        
        .btn-join-now { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px; transition: 0.2s; font-size: 0.95em; }
        .btn-join-now:hover { background: #218838; transform: translateY(-2px); }
        .btn-unenroll { background-color: #cc0000; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s; }
        .btn-unenroll:hover { background-color: #aa0000; transform: translateY(-2px); }
        .btn-ended { background-color: #666; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: not-allowed; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; opacity: 0.8; }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 8px; font-size: 0.95em; }

        .back-nav { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
        .back-nav a { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 0.95em; }
        .back-nav a:hover { transform: translateX(-5px); color: #111; }

        /* --- Style untuk Kalendar & Modal Pop-up --- */
        #calendar { margin-top: 20px; }
        .fc-event { cursor: pointer; border-radius: 4px; padding: 2px 4px; border: none; }
        .fc-event-past { opacity: 0.6; filter: grayscale(100%); }

        /* Modal Overlay */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; width: 90%; max-width: 450px; border-radius: 10px; overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.3); animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-header { background: #111; color: #ffcc00; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #cc0000; }
        .modal-header h3 { margin: 0; font-size: 1.1em; display: flex; align-items: center; gap: 8px; }
        .close-modal { background: none; border: none; color: white; cursor: pointer; font-size: 20px; }
        .close-modal:hover { color: #ffcc00; }
        
        .modal-body { padding: 20px; }
        .detail-row { margin-bottom: 15px; }
        .detail-label { font-size: 0.8em; color: #666; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; display: block; }
        .detail-val { font-size: 1.05em; color: #111; font-weight: bold; display: flex; align-items: center; gap: 8px; }
        .modal-footer { padding: 15px 20px; background: #f9f9f9; border-top: 1px solid #eee; text-align: right; }
        
        /* Details Summary untuk laci Drop Group */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <div class="header-title">
                    <span class="material-icons">event_note</span>
                    <h2>My Training Timetable</h2>
                </div>
                <div class="view-tabs">
                    <button class="view-tab-btn active" onclick="switchView('list')"><span class="material-icons">format_list_bulleted</span> List</button>
                    <button class="view-tab-btn" onclick="switchView('calendar')"><span class="material-icons">calendar_month</span> Calendar</button>
                </div>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 20px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            @if($enrollments->isEmpty())
                <div class="empty-state">
                    <span class="material-icons">sports_martial_arts</span>
                    <h3>You have not enrolled in any classes yet.</h3>
                    <p>Please browse the course directory and enroll in a training class now.</p>
                    <a href="{{ route('courses.index') }}" class="btn-join-now">Browse Courses</a>
                </div>
            @else

                {{-- ========================================================= --}}
                {{-- KITA EJAS SINI: PHP Sort & Group Logic --}}
                {{-- ========================================================= --}}
                @php
                    $groupedRows = [];
                    $flatRows = [];

                    foreach($enrollments as $enrollment) {
                        // Tarik Sesi (Fallback kalau relation tak wujud)
                        $sesi = $enrollment->session ?? \App\Models\SessionTimetable::with('gelanggang')->where('id', $enrollment->session_ID)->orWhere('session_ID', $enrollment->session_ID)->first();
                        
                        $isPast = false;
                        $gabunganTamat = null;
                        $timestamp = 0;

                        if($sesi && $sesi->start_time && $sesi->end_time) {
                            $tarikhMula = \Carbon\Carbon::parse($sesi->start_time)->format('Y-m-d');
                            $masaTamat = $sesi->end_time;
                            
                            if (strlen($masaTamat) <= 8) {
                                $gabunganTamat = \Carbon\Carbon::parse($tarikhMula . ' ' . $masaTamat);
                            } else {
                                $gabunganTamat = \Carbon\Carbon::parse($masaTamat);
                            }
                            
                            $isPast = $gabunganTamat->isPast();
                            $timestamp = \Carbon\Carbon::parse($sesi->start_time)->timestamp;
                        }

                        $item = [
                            'enrollment' => $enrollment,
                            'sesi' => $sesi,
                            'isPast' => $isPast,
                            'gabunganTamat' => $gabunganTamat,
                            'timestamp' => $timestamp
                        ];

                        // Simpan untuk Kalendar (Perlukan semua sesi)
                        $flatRows[] = $item;

                        // Logik Grouping untuk Table List
                        if($sesi && $sesi->start_time) {
                            $courseId = $enrollment->course_ID;
                            $gelId = $sesi->gel_ID;
                            $createdAtStamp = $sesi->created_at ? \Carbon\Carbon::parse($sesi->created_at)->format('Ymd_His') : 'manual';
                            $groupKey = "{$courseId}_{$gelId}_{$createdAtStamp}";
                        } else {
                            $groupKey = "unscheduled_" . ($enrollment->enroll_ID ?? $enrollment->id);
                        }

                        if(!isset($groupedRows[$groupKey])) {
                            $groupedRows[$groupKey] = [];
                        }
                        $groupedRows[$groupKey][] = $item;
                    }

                    // Susun Kumpulan Berdasarkan Tarikh
                    uasort($groupedRows, function($a, $b) {
                        return $a[0]['timestamp'] <=> $b[0]['timestamp'];
                    });
                @endphp
                
                <div id="view-list" class="view-content active">
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date Enrolled</th>
                                    <th>Course Type</th>
                                    <th>Instructor</th>
                                    <th>Location</th>
                                    <th>Schedule</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedRows as $groupKey => $group)
                                    @php
                                        // Susun kelas dalam group mengikut kronologi
                                        usort($group, function($a, $b) { return $a['timestamp'] <=> $b['timestamp']; });
                                        
                                        $firstItem = $group[0];
                                        $lastItem = $group[count($group) - 1];
                                        
                                        $enrollment = $firstItem['enrollment'];
                                        $firstSesi = $firstItem['sesi'];
                                        $lastSesi = $lastItem['sesi'];
                                        
                                        $sessionCount = count($group);
                                        $isWeekly = $sessionCount > 1;
                                        
                                        // Check kalau kesemua jadual dalam group ni dah tamat
                                        $allPast = collect($group)->every(function($i) { return $i['isPast']; });

                                        $hari = $firstSesi ? \Carbon\Carbon::parse($firstSesi->start_time)->format('l') : 'TBA';
                                        $tarikhMula = $firstSesi ? \Carbon\Carbon::parse($firstSesi->start_time)->format('d M Y') : 'TBA';
                                        $tarikhAkhir = $lastSesi ? \Carbon\Carbon::parse($lastSesi->start_time)->format('d M Y') : 'TBA';
                                        $masaMula = $firstSesi ? \Carbon\Carbon::parse($firstSesi->start_time)->format('h:i A') : 'TBA';
                                        $masaAkhir = $firstSesi ? \Carbon\Carbon::parse($firstSesi->end_time)->format('h:i A') : 'TBA';
                                    @endphp

                                    <tr style="{{ $allPast ? 'background-color: #fcfcfc;' : '' }}">
                                        <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($enrollment->enroll_date)->format('d M Y') }}</td>
                                        
                                        <td><b style="color: {{ $allPast ? '#888' : '#111' }};">{{ $enrollment->course->course_type ?? 'N/A' }}</b></td>
                                        
                                        <td style="color: {{ $allPast ? '#888' : '#222' }};">{{ $enrollment->course->instructor->name ?? 'TBA' }}</td>
                                        
                                        <td>
                                            @if($firstSesi && $firstSesi->gelanggang)
                                                <span style="color: {{ $allPast ? '#888' : '#222' }}; font-weight: bold;">
                                                    <span class="material-icons" style="font-size: 14px; vertical-align: text-bottom;">location_on</span>
                                                    {{ $firstSesi->gelanggang->gel_name }}
                                                </span>
                                            @else
                                                <span style="color: #cc0000; font-style: italic;">TBA</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($firstSesi && $firstSesi->start_time)
                                                @if($isWeekly)
                                                    <div style="font-weight: bold; color: {{ $allPast ? '#888' : '#17a2b8' }}; font-size: 1.05em; display: flex; align-items: center; gap: 5px;">
                                                        <span class="material-icons" style="font-size: 16px;">autorenew</span> 
                                                        Every {{ $hari }}
                                                    </div>
                                                    <div style="font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px; color: {{ $allPast ? '#888' : '#555' }};">
                                                        <span class="material-icons" style="font-size: 14px;">date_range</span>
                                                        {{ $tarikhMula }} - {{ $tarikhAkhir }} <b style="color: #cc0000;">({{ $sessionCount }} Classes)</b>
                                                    </div>
                                                    <div style="font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px; color: {{ $allPast ? '#888' : '#555' }};">
                                                        <span class="material-icons" style="font-size: 14px;">schedule</span>
                                                        {{ $masaMula }} - {{ $masaAkhir }}
                                                    </div>
                                                @else
                                                    <div style="font-weight: bold; color: {{ $allPast ? '#888' : '#111' }}; font-size: 1.05em; {{ $allPast ? 'text-decoration: line-through;' : '' }}">
                                                        {{ $tarikhMula }}
                                                    </div>
                                                    <div style="font-size: 0.95em; margin-top: 4px; display: flex; align-items: center; gap: 4px; color: {{ $allPast ? '#888' : '#222' }};">
                                                        <span class="material-icons" style="font-size: 16px; color: {{ $allPast ? '#888' : '#cc0000' }};">schedule</span>
                                                        {{ $masaMula }} - {{ $masaAkhir }}
                                                    </div>
                                                @endif
                                            @else
                                                <span style="color: #cc0000; font-style: italic;">TBA</span>
                                            @endif
                                        </td>

                                        <td style="text-align: center; vertical-align: top;">
                                            @if($isWeekly)
                                                <details style="text-align: left; background: #fffdf5; border: 1px solid #ffcc00; border-radius: 6px; padding: 5px; width: 100%; box-sizing: border-box;">
                                                    <summary style="cursor: pointer; font-weight: bold; color: #111; outline: none; padding: 2px; font-size: 0.75em; display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                        <span class="material-icons" style="font-size: 14px;">visibility</span> Show Dates & Drop
                                                    </summary>
                                                    <div style="margin-top: 5px; display: flex; flex-direction: column; gap: 4px;">
                                                        @foreach($group as $item)
                                                            @php 
                                                                $isPastSesi = $item['isPast']; 
                                                                $enr = $item['enrollment'];
                                                                $s = $item['sesi'];
                                                            @endphp
                                                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px; background: white; border: 1px solid #eee; border-radius: 4px;">
                                                                <span style="font-size: 0.85em; {{ $isPastSesi ? 'text-decoration: line-through; color: #999;' : 'font-weight: bold; color: #333;' }}">
                                                                    {{ \Carbon\Carbon::parse($s->start_time)->format('d M') }}
                                                                </span>
                                                                <div>
                                                                    @if($isPastSesi)
                                                                        <span style="font-size: 0.75em; color: #888; background: #eee; padding: 2px 6px; border-radius: 4px;">Ended</span>
                                                                    @else
                                                                        <form action="{{ route('enroll.destroy', $enr->enroll_ID ?? $enr->id) }}" method="POST" onsubmit="return confirm('Drop this class on {{ \Carbon\Carbon::parse($s->start_time)->format('d M') }}?');" style="margin:0;">
                                                                            @csrf @method('DELETE')
                                                                            <button type="submit" class="btn-unenroll" style="padding: 2px 6px; font-size: 0.75em;" title="Drop Class"><span class="material-icons" style="font-size: 14px;">logout</span></button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </details>
                                            @else
                                                <div style="display: flex; justify-content: center; margin-top: 5px;">
                                                    @if($allPast)
                                                        <button type="button" class="btn-ended" disabled title="This class has already ended">
                                                            <span class="material-icons" style="font-size: 16px;">history</span> Ended
                                                        </button>
                                                    @else
                                                        <form action="{{ route('enroll.destroy', $enrollment->enroll_ID ?? $enrollment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unenroll from this class? This action cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-unenroll">
                                                                <span class="material-icons" style="font-size: 16px;">logout</span> Drop
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="view-calendar" class="view-content">
                    <div id='calendar'></div>
                </div>

            @endif

            <div class="back-nav">
                <a href="{{ route('dashboard') }}">
                    <span class="material-icons">arrow_back</span> Back to Dashboard
                </a>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="eventModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><span class="material-icons">event</span> Class Details</h3>
                <button class="close-modal" onclick="closeModal()"><span class="material-icons">close</span></button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <span class="detail-label">Course Type</span>
                    <span class="detail-val" id="m-course"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Instructor</span>
                    <span class="detail-val" id="m-instructor"><span class="material-icons" style="color: #666; font-size:18px;">person</span> <span id="m-instructor-text"></span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location</span>
                    <span class="detail-val" id="m-location"><span class="material-icons" style="color: #cc0000; font-size:18px;">location_on</span> <span id="m-location-text"></span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Schedule</span>
                    <span class="detail-val" id="m-time"><span class="material-icons" style="color: #ff9900; font-size:18px;">schedule</span> <span id="m-time-text"></span></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="view-tab-btn" style="display:inline-block;" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        let calendar; 

        function switchView(viewName) {
            document.querySelectorAll('.view-tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            document.querySelectorAll('.view-content').forEach(content => content.classList.remove('active'));
            document.getElementById('view-' + viewName).classList.add('active');

            if(viewName === 'calendar' && !calendar) {
                initCalendar();
            }
        }

        function initCalendar() {
            var calendarEl = document.getElementById('calendar');
            
            var eventsData = [
                // Kalendar akan guna array $flatRows supaya dia lukis SATU-SATU jadual dalam kotak
                @foreach($flatRows ?? [] as $row)
                    @php
                        $enrollment = $row['enrollment'];
                        $sesi = $row['sesi'];
                        $isPast = $row['isPast'];
                        $gabunganTamat = $row['gabunganTamat'];

                        if($sesi && $sesi->start_time && $gabunganTamat) {
                            $warna = $isPast ? '#888888' : '#cc0000'; 
                            $title = $enrollment->course->course_type ?? 'Training Class';
                            $startIso = \Carbon\Carbon::parse($sesi->start_time)->toIso8601String();
                            $endIso = $gabunganTamat->toIso8601String();
                            
                            $instructor = addslashes($enrollment->course->instructor->name ?? 'TBA');
                            $lokasi = addslashes($sesi->gelanggang->gel_name ?? 'TBA');
                            $masaPaparan = \Carbon\Carbon::parse($sesi->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($sesi->end_time)->format('h:i A');
                        }
                    @endphp
                    @if($sesi && $sesi->start_time)
                    {
                        title: '{{ $title }}',
                        start: '{{ $startIso }}',
                        end: '{{ $endIso }}',
                        color: '{{ $warna }}',
                        className: '{{ $isPast ? "fc-event-past" : "" }}',
                        extendedProps: {
                            courseName: '{{ $title }}',
                            instructor: '{{ $instructor }}',
                            location: '{{ $lokasi }}',
                            timeDisplay: '{{ $masaPaparan }}'
                        }
                    },
                    @endif
                @endforeach
            ];

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                displayEventTime: false, 
                events: eventsData,
                eventClick: function(info) {
                    document.getElementById('m-course').innerText = info.event.extendedProps.courseName;
                    document.getElementById('m-instructor-text').innerText = info.event.extendedProps.instructor;
                    document.getElementById('m-location-text').innerText = info.event.extendedProps.location;
                    
                    var eventDate = info.event.start.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                    document.getElementById('m-time-text').innerText = eventDate + ' (' + info.event.extendedProps.timeDisplay + ')';
                    
                    document.getElementById('eventModal').classList.add('active');
                }
            });
            calendar.render();
        }

        function closeModal() {
            document.getElementById('eventModal').classList.remove('active');
        }
    </script>
</body>
</html>