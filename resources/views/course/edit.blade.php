<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
</head>
<body>
    <div style="max-width: 600px; margin: 20px auto; padding: 30px; border: 1px solid #ccc; border-radius: 8px; font-family: sans-serif; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <h2 style="margin-top: 0;">Edit Course: {{ $course->course_code }}</h2>

        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; background-color: #f8d7da; border-radius: 4px;">
                <b>Validation Errors:</b>
                <ul style="margin: 5px 0 0 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('courses.update', $course->course_ID ?? $course->id) }}" method="POST">
            @csrf 
            @method('PUT')

            <label for="course_type" style="font-weight: bold;">Course Type:</label><br>
            <select id="course_type" name="course_type" required style="width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                <option value="Silat Olahraga" {{ $course->course_type == 'Silat Olahraga' ? 'selected' : '' }}>Silat Olahraga</option>
                <option value="Silat Seni" {{ $course->course_type == 'Silat Seni' ? 'selected' : '' }}>Silat Seni</option>
                <option value="Pelajaran Silibus" {{ $course->course_type == 'Pelajaran Silibus' ? 'selected' : '' }}>Pelajaran Silibus</option>
            </select>

            <label style="font-weight: bold;">Assigned Instructor:</label><br>
            <input type="text" value="{{ $course->instructor->name ?? 'Unknown Instructor' }}" disabled style="width: 100%; padding: 10px; margin-top: 5px; background-color: #e9ecef; border: 1px solid #ccc; border-radius: 4px; color: #495057; box-sizing: border-box;">
            
            <input type="hidden" name="instructor_ID" value="{{ $course->instructor_ID }}">
            <br><br>

            <button type="submit" style="padding: 10px 20px; background-color: #ffc107; color: #212529; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Update Course
            </button>
            <a href="{{ route('courses.index') }}" style="margin-left: 15px; color: #007bff; text-decoration: none; font-weight: bold;">Cancel</a>
        </form>
    </div>
</body>
</html>