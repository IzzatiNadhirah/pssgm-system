<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Course</title>
</head>
<body>
    <div style="max-width: 600px; margin: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <h2>Register New Course</h2>

        @if (session('success'))
            <script>alert("{{ session('success') }}");</script>
            <p style="color: green;"><b>{{ session('success') }}</b></p>
        @endif

        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <b>Validation Errors:</b>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('courses.store') }}" method="POST">
            @csrf 

            <label for="course_type">Course Type:</label><br>
            <select id="course_type" name="course_type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                <option value="" disabled selected>-- Select Course Type --</option>
                <option value="Silat Olahraga">Silat Olahraga</option>
                <option value="Silat Seni">Silat Seni</option>
                <option value="Pelajaran Silibus">Pelajaran Silibus</option>
            </select><br><br>

            <label>Assigned Instructor:</label><br>
            <input type="text" value="{{ Auth::guard('instructor')->user()->name ?? Auth::user()->name }}" disabled style="width: 100%; padding: 8px; background-color: #e9ecef; border: 1px solid #ccc; border-radius: 4px; color: #495057; box-sizing: border-box;">
            
            <input type="hidden" name="instructor_ID" value="{{ Auth::guard('instructor')->user()->instructor_ID ?? Auth::user()->instructor_ID ?? Auth::id() }}">
            <br><br>

            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Register Course
            </button>
            <a href="{{ route('courses.index') }}" style="margin-left: 15px; color: #007bff; text-decoration: none;">Cancel</a>
        </form>
    </div>
</body>
</html>