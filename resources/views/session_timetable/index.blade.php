<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sessions - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { max-width: 1200px; width: 100%; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; }
        
        .header-area { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }
        
        .btn { padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s; }
        .btn-add { background-color: #cc0000; color: white; }
        .btn-edit { background-color: #ffcc00; color: #111; }
        .btn-delete { background-color: #333; color: white; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .btn-ended { background-color: #666; color: white; cursor: not-allowed; }
        .btn-ended:hover { transform: none; opacity: 1; }

        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }

        .empty-state { text-align: center; padding: 50px; color: #888; }
        .empty-state p { margin-top: 10px; }

        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); }

        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; background: white; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #cc0000; }
        .dataTables_wrapper .dataTables_length select { border: 2px solid #eee; border-radius: 6px; padding: 5px; background: white; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ffcc00 !important; color: #111 !important; border: none; font-weight: bold; border-radius: 6px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: none; border-radius: 6px; }
        .dataTables_wrapper .dataTables_info { font-size: 0.9em; color: #666; margin-top: 15px; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 15px; }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 20px; }
        .dataTables_wrapper .dataTables_length { margin-bottom: 20px; }
        
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <h2>Manage Class Sessions</h2>
                <a href="{{ route('sessions.create', ['course_id' => request('course_id')]) }}" class="btn btn-add">
                    <span class="material-icons">add</span> Create New Session
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($timetables->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px;">event_busy</span>
                    <h3>No Sessions Created</h3>
                    <p>You have not scheduled any sessions for your courses yet.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table id="sessionsTable" class="display">
                        <thead>
                            <tr>
                                <th>Course Type</th>
                                <th>Location (Gelanggang)</th>
                                <th>Session Date & Time</th>
                                <th style="text-align: center;">Capacity</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedTimetables = [];
                                foreach($timetables as $session) {
                                    if($session->start_time) {
                                        // KITA EJAS SINI: Gunakan 'created_at' sebagai cap jari supaya 
                                        // hanya jadual yang di-generate pada klik "Save" yang sama akan digroupkan.
                                        $courseId = $session->course_ID ?? $session->course->id;
                                        $gelId = $session->gel_ID ?? $session->gelanggang->id;
                                        $createdAtStamp = $session->created_at ? \Carbon\Carbon::parse($session->created_at)->format('Ymd_His') : 'manual';
                                        
                                        $groupKey = "{$courseId}_{$gelId}_{$createdAtStamp}";
                                    } else {
                                        $groupKey = "unscheduled_" . $session->id;
                                    }

                                    if(!isset($groupedTimetables[$groupKey])) {
                                        $groupedTimetables[$groupKey] = [];
                                    }
                                    $groupedTimetables[$groupKey][] = $session;
                                }

                                // Susun setiap kumpulan berdasarkan tarikh kelas terawal
                                uasort($groupedTimetables, function($a, $b) {
                                    $timeA = $a[0]->start_time ? strtotime($a[0]->start_time) : 0;
                                    $timeB = $b[0]->start_time ? strtotime($b[0]->start_time) : 0;
                                    return $timeA <=> $timeB;
                                });
                            @endphp

                            @foreach($groupedTimetables as $groupKey => $group)
                            @php
                                // Susun sesi dalam kumpulan dari tarikh awal ke akhir
                                usort($group, function($a, $b) {
                                    return strtotime($a->start_time) <=> strtotime($b->start_time);
                                });

                                $firstSesi = $group[0];
                                $lastSesi = $group[count($group) - 1];
                                $sessionCount = count($group);
                                $isWeekly = $sessionCount > 1; 

                                // Periksa jika SEMUA kelas dalam group ni dah lepas
                                $allPast = true;
                                foreach($group as $s) {
                                    if(!\Carbon\Carbon::parse($s->start_time)->isPast()) {
                                        $allPast = false;
                                        break;
                                    }
                                }
                            @endphp
                            
                            <tr style="{{ $allPast ? 'background-color: #fcfcfc;' : '' }}">
                                <td><b style="color: {{ $allPast ? '#888' : '#111' }}; font-size: 1.1em;">{{ $firstSesi->course->course_type ?? 'Unknown Course' }}</b></td>
                                
                                <td style="{{ $allPast ? 'color: #888;' : '' }}">{{ $firstSesi->gelanggang->gel_name ?? 'Location Not Set' }}</td>
                                
                                <td style="color: #111;">
                                    @if($firstSesi->start_time && $firstSesi->end_time)
                                        @if($isWeekly)
                                            {{-- PAPARAN UNTUK KELAS BERULANG (GROUP) --}}
                                            <div style="font-weight: bold; color: {{ $allPast ? '#999' : '#17a2b8' }}; font-size: 1.05em; display: flex; align-items: center; gap: 5px;">
                                                <span class="material-icons" style="font-size: 18px;">autorenew</span> 
                                                Every {{ \Carbon\Carbon::parse($firstSesi->start_time)->format('l') }}
                                            </div>
                                            <div style="color: {{ $allPast ? '#aaa' : '#555' }}; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <span class="material-icons" style="font-size: 14px;">date_range</span>
                                                {{ \Carbon\Carbon::parse($firstSesi->start_time)->format('d M Y') }} - {{ \Carbon\Carbon::parse($lastSesi->start_time)->format('d M Y') }}
                                                <b style="color: #cc0000;">({{ $sessionCount }} Classes)</b>
                                            </div>
                                            <div style="color: {{ $allPast ? '#aaa' : '#555' }}; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <span class="material-icons" style="font-size: 14px;">schedule</span>
                                                {{ \Carbon\Carbon::parse($firstSesi->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($firstSesi->end_time)->format('h:i A') }}
                                            </div>
                                        @else
                                            {{-- PAPARAN UNTUK KELAS SATU HARI --}}
                                            <div style="font-weight: bold; color: {{ $allPast ? '#999' : '#cc0000' }}; font-size: 1.05em; {{ $allPast ? 'text-decoration: line-through;' : '' }}">
                                                {{ \Carbon\Carbon::parse($firstSesi->start_time)->format('d M Y') }}
                                            </div>
                                            <div style="color: {{ $allPast ? '#aaa' : '#555' }}; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <span class="material-icons" style="font-size: 14px;">schedule</span>
                                                {{ \Carbon\Carbon::parse($firstSesi->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($firstSesi->end_time)->format('h:i A') }}
                                            </div>
                                        @endif
                                    @else
                                        <span style="color: #cc0000; font-style: italic;">Masa belum ditetapkan</span>
                                    @endif
                                </td>
                                
                                <td style="text-align: center; {{ $allPast ? 'color: #888;' : '' }}">{{ $firstSesi->capacity }} Pax</td>
                                
                                <td style="text-align: center; vertical-align: top;">
                                    @if($isWeekly)
                                        <details style="text-align: left; background: #fffdf5; border: 1px solid #ffcc00; border-radius: 6px; padding: 5px; min-width: 200px;">
                                            <summary style="cursor: pointer; font-weight: bold; color: #111; outline: none; padding: 5px; font-size: 0.85em; display: flex; align-items: center; justify-content: center; gap: 5px; background: #ffcc00; border-radius: 4px;">
                                                <span class="material-icons" style="font-size: 18px;">list</span> Manage Classes
                                            </summary>
                                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 8px;">
                                                @foreach($group as $sesi)
                                                    @php $isPastSesi = \Carbon\Carbon::parse($sesi->start_time)->isPast(); @endphp
                                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: white; border: 1px solid #eee; border-radius: 4px;">
                                                        <span style="font-size: 0.85em; {{ $isPastSesi ? 'text-decoration: line-through; color: #999;' : 'font-weight: bold; color: #333;' }}">
                                                            {{ \Carbon\Carbon::parse($sesi->start_time)->format('d M') }}
                                                        </span>
                                                        <div style="display: flex; gap: 4px;">
                                                            @if($isPastSesi)
                                                                <span style="font-size: 0.75em; color: #888; background: #eee; padding: 2px 6px; border-radius: 4px;">Ended</span>
                                                            @else
                                                                <a href="{{ route('sessions.edit', $sesi->id) }}" class="btn btn-edit" style="padding: 2px 6px; font-size: 0.75em;" title="Edit"><span class="material-icons" style="font-size: 14px;">edit</span></a>
                                                                <form action="{{ route('sessions.destroy', $sesi->id) }}" method="POST" onsubmit="return confirm('Delete this specific class on {{ \Carbon\Carbon::parse($sesi->start_time)->format('d M') }}?');" style="margin:0;">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-delete" style="padding: 2px 6px; font-size: 0.75em;" title="Delete"><span class="material-icons" style="font-size: 14px;">delete</span></button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @else
                                        <div style="display: flex; gap: 8px; justify-content: center; margin-top: 5px;">
                                            @if($allPast)
                                                <button type="button" class="btn btn-ended" title="Class has ended. Records cannot be altered.">
                                                    <span class="material-icons" style="font-size: 18px;">history</span> Ended
                                                </button>
                                            @else
                                                <a href="{{ route('sessions.edit', $firstSesi->id) }}" class="btn btn-edit" title="Edit Session">
                                                    <span class="material-icons" style="font-size: 18px;">edit</span>
                                                </a>
                                                <form action="{{ route('sessions.destroy', $firstSesi->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');" style="margin:0;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-delete" title="Delete Session">
                                                        <span class="material-icons" style="font-size: 18px;">delete</span>
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
            @endif

            <div class="footer-nav">
                <a href="{{ route('courses.index') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to My Courses
                </a>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sessionsTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "search": "Quick Search:",
                    "lengthMenu": "Show _MENU_ entries"
                },
                "order": [] 
            });
        });
    </script>
</body>
</html>