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

            <label for="course_name">Course Name:</label><br>
            <input type="text" id="course_name" name="course_name" placeholder="e.g., Asas Silat (Level 1)" required style="width: 100%;"><br><br>

            <label for="description">Course Description:</label><br>
            <textarea id="description" name="description" rows="4" required style="width: 100%;"></textarea><br><br>

            <label for="instructor_ID">Assign Instructor:</label><br>
            <select id="instructor_ID" name="instructor_ID" required style="width: 100%; padding: 5px;">
                <option value="" disabled selected>-- Select Instructor --</option>
                @foreach($instructors as $instructor)
                    <option value="{{ $instructor->instructor_ID ?? $instructor->id }}">
                        {{ $instructor->name }}
                    </option>
                @endforeach
            </select><br><br>

            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Register Course
            </button>
        </form>
    </div>
</body>
</html>