<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1000px; width: 100%; background: white; padding: 35px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }
        
        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        .course-section { margin-bottom: 40px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .course-header { background: #111; color: #ffcc00; padding: 15px 20px; font-size: 1.1em; font-weight: bold; display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid #cc0000; }
        
        .course-info { padding: 15px 20px; font-size: 0.9em; color: #444; border-bottom: 1px solid #ddd; background: white;}
        .course-info span { font-weight: bold; color: #111; margin-right: 15px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 20px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        th { background-color: #f1f1f1; color: #333; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }

        .empty-state { text-align: center; padding: 30px; color: #888; font-style: italic; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: left; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">groups</span>
                <h2>Enrolled Students Directory</h2>
            </div>

            @if($courses->isEmpty())
                <div class="empty-state" style="border: 2px dashed #ddd; border-radius: 8px; padding: 50px;">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">assignment_late</span>
                    <p>You have not been assigned to any courses yet.</p>
                </div>
            @else
                @foreach($courses as $course)
                    <div class="course-section">
                        <div class="course-header">
                            <div>
                                <span class="material-icons" style="vertical-align: bottom; font-size: 20px; margin-right: 5px;">menu_book</span>
                                {{ $course->course_type }}
                            </div>
                            <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">
                                {{ $course->enrollments ? $course->enrollments->count() : 0 }} / {{ $course->capacity ?? '?' }} Pax
                            </span>
                        </div>

                        <div class="course-info">
                            <span><i class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">place</i> Location:</span> {{ $course->gelanggang->gel_name ?? 'Not Set' }} <br>
                            <span style="margin-top: 5px; display: inline-block;"><i class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">schedule</i> Time:</span> {{ $course->session_time ?? 'Not Scheduled' }}
                        </div>

                        @if(!$course->enrollments || $course->enrollments->isEmpty())
                            <div class="empty-state">No students have enrolled in this class yet.</div>
                        @else
                            <div style="overflow-x: auto;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th>Student Name</th>
                                            <th>Registration Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->enrollments as $index => $enrollment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><b>{{ $enrollment->user->name ?? 'Unknown Student' }}</b></td>
                                            
                                            <td>{{ \Carbon\Carbon::parse($enrollment->enroll_date)->format('d M Y') }}</td>
                                            
                                            <td><b style="color: #28a745;">Active</b></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            <div class="footer-nav">
                <a href="{{ route('instructor.dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Instructor Dashboard
                </a>
            </div>

        </div>
    </div>
</body>
</html>