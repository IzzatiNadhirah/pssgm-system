<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Course - PSSGM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; }
        
        /* PSSGM THEME COLORS */
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #cc0000; }
        .header-title { color: #111; margin-top: 0; border-bottom: 2px solid #ffcc00; padding-bottom: 10px; display: inline-block; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        
        select, input[type="text"] { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; transition: border-color 0.3s;
        }
        select:focus, input:focus { border-color: #ffcc00; outline: none; box-shadow: 0 0 5px rgba(255, 204, 0, 0.3); }
        
        .btn-submit { padding: 12px 25px; background-color: #cc0000; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.2s; }
        .btn-submit:hover { background-color: #ff0000; }
        .btn-cancel { margin-left: 15px; color: #555; text-decoration: none; font-weight: bold; }
        .btn-cancel:hover { color: #111; }
        
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px; border-left: 5px solid #28a745; margin-bottom: 25px; border-radius: 4px; }
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="header-title">Register New Course</h2>

        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                <b>Validation Errors:</b>
                <ul style="margin-top: 5px; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('courses.store') }}" method="POST">
            @csrf 

            <div class="form-group">
                <label for="course_type">Course Type:</label>
                <select id="course_type" name="course_type" required>
                    <option value="" disabled selected>-- Select Course Type --</option>
                    <option value="Silat Olahraga">Silat Olahraga</option>
                    <option value="Silat Seni">Silat Seni</option>
                    <option value="Pelajaran Silibus">Pelajaran Silibus</option>
                </select>
            </div>

            <div class="form-group">
                <label for="instructor_ID">Assign Instructor:</label>
                <select id="instructor_ID" name="instructor_ID" required>
                    <option value="" disabled selected>-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->instructor_ID ?? $instructor->id }}">
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Register Course</button>
                <a href="{{ route('courses.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>