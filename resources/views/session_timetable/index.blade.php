<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; }
        
        /* PSSGM THEME COLORS */
        .container { max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #cc0000; }
        .header-title { color: #111; margin-top: 0; border-bottom: 2px solid #ffcc00; padding-bottom: 10px; display: inline-block; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        
        /* Black Background, Gold Text for Header */
        th { background-color: #111; color: #ffcc00; font-weight: bold; letter-spacing: 0.5px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        
        .btn-delete { background-color: #333; color: white; border: 1px solid #111; padding: 8px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; transition: 0.2s; }
        .btn-delete:hover { background-color: #000; }
        
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-left: 5px solid #28a745; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 class="header-title">Enrolled Students List</h2>
        <p style="color: #666;">View the list of students who have enrolled in your classes.</p>

        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        @if($timetables->isEmpty())
            <div style="background-color: #fff9e6; padding: 40px; text-align: center; border-radius: 8px; border: 1px dashed #ffcc00; margin-top: 20px;">
                <p style="margin-top: 0; color: #555; font-weight: bold;">No students have enrolled in your classes yet.</p>
                <p style="font-size: 0.9em; color: #888;">Students will appear here once they register for your scheduled courses.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Student Details</th>
                            <th>Course Enrolled</th>
                            <th>Class Schedule</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetables as $timetable)
                        <tr>
                            <td><b>{{ $timetable->student->name }}</b><br><small style="color: #666;">ID: {{ $timetable->student->user_code }}</small></td>
                            <td><b>{{ $timetable->course->course_code }}</b><br><small>{{ $timetable->course->course_type }}</small></td> 
                            
                            <td>{{ $timetable->course->session_time ?? 'Not Set' }}</td>
                            
                            <td style="text-align: center;">
                                <form action="{{ route('sessions.destroy_custom', ['course_id' => $timetable->course_ID, 'user_id' => $timetable->user_ID]) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to remove this student from the class?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('instructor.dashboard') }}" style="color: #cc0000; text-decoration: none; font-weight: bold;">&larr; Back to Dashboard</a>
        </div>

    </div>
</body>
</html>