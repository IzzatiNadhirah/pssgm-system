<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - PSSGM Melaka</title>
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
        .btn-schedule { background-color: #17a2b8; color: white; }
        .btn-join { background-color: #28a745; color: white; }
        .btn-disabled { background-color: #6c757d; color: white; cursor: not-allowed; opacity: 0.8; }
        .btn:hover:not(.btn-disabled) { opacity: 0.9; transform: translateY(-2px); }

        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }

        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); }

        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; background: #eee; }
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
                    <h2>Course Directory & Schedules</h2>
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
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Course Type</th>
                                
                                @if(!Auth::guard('instructor')->check())
                                    <th>Assigned Instructor</th>
                                @endif
                                
                                {{-- Tukar Tajuk Kolum --}}
                                <th>Location</th>
                                <th>Schedule</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            
                            {{-- Tarik data dari SessionTimetable --}}
                            @php
                                $sesi = \App\Models\SessionTimetable::with('gelanggang')->where('course_ID', $course->course_ID ?? $course->id)->first();
                                $hasSessions = $sesi ? true : false;
                            @endphp

                            <tr>
                                <td><b style="color: #111; font-size: 1.1em;">{{ $course->course_type }}</b></td>
                                
                                @if(!Auth::guard('instructor')->check())
                                    <td>{{ $course->instructor->name ?? 'Unassigned' }}</td>
                                @endif
                                
                                {{-- Paparan Lokasi --}}
                                <td>
                                    @if($hasSessions && $sesi->gelanggang)
                                        <span class="status-badge">{{ $sesi->gelanggang->gel_name }}</span>
                                    @else
                                        <span style="color: #888; font-style: italic;">TBA</span>
                                    @endif
                                </td>

                                {{-- Paparan Masa --}}
                                <td style="color: #111;">
                                    @if($hasSessions && $sesi->start_time && $sesi->end_time)
                                        <div style="font-weight: bold; color: #cc0000; font-size: 1.05em;">
                                            {{ \Carbon\Carbon::parse($sesi->start_time)->format('d M Y') }}
                                        </div>
                                        <div style="color: #555; font-size: 0.9em; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                            <span class="material-icons" style="font-size: 14px;">schedule</span>
                                            {{ \Carbon\Carbon::parse($sesi->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sesi->end_time)->format('h:i A') }}
                                        </div>
                                    @else
                                        <span style="color: #cc0000; font-weight: bold;">
                                            <span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">event_busy</span> No Schedule Yet
                                        </span>
                                    @endif
                                </td>
                                
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        
                                        {{-- STAFF ACTIONS --}}
                                        @if(Auth::guard('staff')->check())
                                            <a href="{{ route('courses.edit', $course->course_ID ?? $course->id) }}" class="btn btn-edit" title="Edit">
                                                <span class="material-icons">edit</span>
                                            </a>
                                            <form action="{{ route('courses.destroy', $course->course_ID ?? $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-delete" title="Delete Course"><span class="material-icons">delete</span></button>
                                            </form>
                                        @endif

                                        {{-- INSTRUCTOR ACTIONS --}}
                                        @if(Auth::guard('instructor')->check() && Auth::guard('instructor')->user()->instructor_ID == $course->instructor_ID)
                                            <a href="{{ route('sessions.index') }}" class="btn btn-schedule">
                                                <span class="material-icons">event_note</span> Manage Sessions
                                            </a>
                                        @endif

                                        {{-- MEMBER ACTIONS (JOIN TRAINING) --}}
                                        @if(!Auth::guard('staff')->check() && !Auth::guard('instructor')->check())
                                            @if($course->instructor_ID && $hasSessions)
                                                <form action="{{ route('enroll.store', $course->course_ID ?? $course->id) }}" method="POST" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-join">
                                                        <span class="material-icons">add_circle</span> Join Training
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-disabled" title="Class not ready for enrollment">
                                                    <span class="material-icons">lock</span> Not Ready
                                                </button>
                                            @endif
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
                @if(Auth::guard('staff')->check())
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Admin Dashboard
                    </a>
                @elseif(Auth::guard('instructor')->check())
                    <a href="{{ route('instructor.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Instructor Dashboard
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Member Dashboard
                    </a>
                @endif
            </div>

        </div>
    </div>
</body>
</html>