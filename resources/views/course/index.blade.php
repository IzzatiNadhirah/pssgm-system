<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; text-decoration: none; display: inline-block; font-size: 0.9em; }
        .btn-add { background-color: #28a745; color: white; margin-bottom: 15px; }
        .btn-edit { background-color: #ffc107; color: #212529; }
        .btn-delete { background-color: #dc3545; color: white; }
        
        .empty-state { background-color: #e9ecef; padding: 40px; text-align: center; border-radius: 8px; color: #6c757d; margin-top: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-left: 5px solid #28a745; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 style="margin-top: 0;">Course Directory</h2>
        <p style="color: #666;">Manage all martial arts training courses available in the system.</p>

        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        <a href="{{ route('courses.create') }}" class="btn btn-add">+ Register New Course</a>

        @if($courses->isEmpty())
            <div class="empty-state">
                <p style="margin-top: 0;">No courses have been registered yet.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Description</th>
                            <th>Instructor Assigned</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td><b>{{ $course->course_code }}</b></td> 
                            <td>{{ $course->course_name }}</td>
                            <td>{{ Str::limit($course->description, 50) }}</td>
                            
                            <td>{{ $course->instructor->name ?? $course->instructor_ID }}</td>
                            
                            <td style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('courses.edit', $course->course_ID ?? $course->id) }}" class="btn btn-edit">Edit</a>

                                <form action="{{ route('courses.destroy', $course->course_ID ?? $course->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('instructor.dashboard') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">&larr; Back to Dashboard</a>
        </div>

    </div>
</body>
</html>