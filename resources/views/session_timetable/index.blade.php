<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sessions - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    <table>
                        <thead>
                            <tr>
                                <th>Course Type</th>
                                <th>Location (Gelanggang)</th>
                                <th>Session Date & Time</th>
                                <th>Capacity</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // KITA EJAS SINI: Logik untuk susun (sort) senarai kelas
                                $sortedTimetables = $timetables->sort(function ($a, $b) {
                                    $aPast = \Carbon\Carbon::parse($a->start_time)->isPast();
                                    $bPast = \Carbon\Carbon::parse($b->start_time)->isPast();

                                    // Kalau dua-dua dah lepas ATAU dua-dua belum lepas
                                    if ($aPast === $bPast) {
                                        if ($aPast) {
                                            // Kalau dah lepas: Susun dari yang paling baru lepas ke paling lama
                                            return $b->start_time <=> $a->start_time; 
                                        } else {
                                            // Kalau belum lepas: Susun dari yang paling dekat nak mula
                                            return $a->start_time <=> $b->start_time;
                                        }
                                    }
                                    
                                    // Tolak kelas yang dah lepas ke bawah (return 1)
                                    return $aPast ? 1 : -1;
                                });
                            @endphp

                            {{-- Guna variable $sortedTimetables yang kita dah susun --}}
                            @foreach($sortedTimetables as $session)
                            @php
                                $isPast = false;
                                if($session->start_time) {
                                    $isPast = \Carbon\Carbon::parse($session->start_time)->isPast();
                                }
                            @endphp
                            
                            <tr style="{{ $isPast ? 'background-color: #fcfcfc;' : '' }}">
                                <td><b style="color: {{ $isPast ? '#888' : '#111' }}; font-size: 1.1em;">{{ $session->course->course_type ?? 'Unknown Course' }}</b></td>
                                
                                <td style="{{ $isPast ? 'color: #888;' : '' }}">{{ $session->gelanggang->gel_name ?? 'Location Not Set' }}</td>
                                
                                <td style="color: #111;">
                                    @if($session->start_time && $session->end_time)
                                        <div style="font-weight: bold; color: {{ $isPast ? '#999' : '#cc0000' }}; font-size: 1.05em; {{ $isPast ? 'text-decoration: line-through;' : '' }}">
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y') }}
                                        </div>
                                        <div style="color: {{ $isPast ? '#aaa' : '#555' }}; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                            <span class="material-icons" style="font-size: 14px;">schedule</span>
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                        </div>
                                    @else
                                        <span style="color: #cc0000; font-style: italic;">Masa belum ditetapkan</span>
                                    @endif
                                </td>
                                
                                <td style="{{ $isPast ? 'color: #888;' : '' }}">{{ $session->capacity }} Pax</td>
                                
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        
                                        @if($isPast)
                                            <button type="button" class="btn btn-ended" title="Class has ended. Records cannot be altered.">
                                                <span class="material-icons" style="font-size: 18px;">history</span> Ended
                                            </button>
                                        @else
                                            <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-edit" title="Edit Session">
                                                <span class="material-icons" style="font-size: 18px;">edit</span>
                                            </a>

                                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-delete" title="Delete Session">
                                                    <span class="material-icons" style="font-size: 18px;">delete</span>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
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
</body>
</html>