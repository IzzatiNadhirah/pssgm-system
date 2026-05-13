<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Timetable - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 1000px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 32px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }

        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; background: #eee; }
        
        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; }
        .empty-state .material-icons { font-size: 48px; margin-bottom: 10px; color: #ccc; }
        
        .btn-join-now { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px; transition: 0.2s; }
        .btn-join-now:hover { background: #218838; transform: translateY(-2px); }

        .back-nav { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
        .back-nav a { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-nav a:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">event_note</span>
                <h2>My Training Timetable</h2>
            </div>

            @if($enrollments->isEmpty())
                <div class="empty-state">
                    <span class="material-icons">sports_martial_arts</span>
                    <h3>Awak belum mendaftar mana-mana kelas lagi.</h3>
                    <p>Sila semak senarai gelanggang dan daftar kelas latihan anda sekarang.</p>
                    <a href="{{ route('courses.index') }}" class="btn-join-now">Browse Courses</a>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Date Enrolled</th>
                                <th>Course Type</th>
                                <th>Instructor</th>
                                <th>Location (Gelanggang)</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td style="font-size: 0.9em; color: #666;">
                                        {{ \Carbon\Carbon::parse($enrollment->enroll_date)->format('d M Y') }}
                                    </td>
                                    <td><b>{{ $enrollment->course->course_type ?? 'N/A' }}</b></td>
                                    <td>{{ $enrollment->course->instructor->name ?? 'TBA' }}</td>
                                    <td>
                                        <span class="status-badge">{{ $enrollment->course->gelanggang->gel_name ?? 'TBA' }}</span>
                                    </td>
                                    <td style="color: #111;">
                                        <b>{{ $enrollment->course->session_time ?? 'TBA' }}</b>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="back-nav">
                <a href="{{ route('dashboard') }}">
                    <span class="material-icons">arrow_back</span> Back to Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>