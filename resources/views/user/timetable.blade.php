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
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }
        
        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; }
        .empty-state .material-icons { font-size: 48px; margin-bottom: 10px; color: #ccc; }
        
        .btn-join-now { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px; transition: 0.2s; }
        .btn-join-now:hover { background: #218838; transform: translateY(-2px); }

        .btn-unenroll { background-color: #cc0000; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s; }
        .btn-unenroll:hover { background-color: #aa0000; transform: translateY(-2px); }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }

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

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="vertical-align: bottom; font-size: 18px;">check_circle</span> 
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
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Date Enrolled</th>
                                <th>Course Type</th>
                                <th>Instructor</th>
                                <th>Location (Gelanggang)</th>
                                <th>Schedule</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                @php
                                    // Fetch SessionTimetable data for this course
                                    $sesi = \App\Models\SessionTimetable::with('gelanggang')->where('course_ID', $enrollment->course_ID)->first();
                                @endphp
                                <tr>
                                    <td style="font-size: 0.9em; color: #666;">
                                        {{ \Carbon\Carbon::parse($enrollment->enroll_date)->format('d M Y') }}
                                    </td>
                                    <td><b style="color: #111;">{{ $enrollment->course->course_type ?? 'N/A' }}</b></td>
                                    <td>{{ $enrollment->course->instructor->name ?? 'TBA' }}</td>
                                    
                                    <td>
                                        @if($sesi && $sesi->gelanggang)
                                            {{ $sesi->gelanggang->gel_name }}
                                        @else
                                            <span style="color: #888; font-style: italic;">TBA</span>
                                        @endif
                                    </td>

                                    <td style="color: #111;">
                                        @if($sesi && $sesi->start_time && $sesi->end_time)
                                            <div style="font-weight: bold; color: #cc0000; font-size: 1.05em;">
                                                {{ \Carbon\Carbon::parse($sesi->start_time)->format('d M Y') }}
                                            </div>
                                            <div style="color: #555; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <span class="material-icons" style="font-size: 14px;">schedule</span>
                                                {{ \Carbon\Carbon::parse($sesi->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sesi->end_time)->format('h:i A') }}
                                            </div>
                                        @else
                                            <span style="color: #888; font-style: italic;">TBA</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('enroll.destroy', $enrollment->enroll_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to unenroll from this class? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-unenroll">
                                                <span class="material-icons" style="font-size: 16px;">logout</span> Unenroll
                                            </button>
                                        </form>
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