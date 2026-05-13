<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student to Session - PSSGM</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
        }
        
        /* PSSGM THEME COLORS */
        .form-container { 
            max-width: 650px; 
            margin: 40px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            border-top: 5px solid #cc0000; /* Gayong Red */
        }
        
        .header-title { 
            color: #111; 
            margin-top: 0; 
            border-bottom: 2px solid #ffcc00; /* Gayong Gold */
            padding-bottom: 10px; 
            display: inline-block; 
            margin-bottom: 25px;
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            font-weight: bold; 
            margin-bottom: 8px; 
            color: #333; 
        }
        
        select, input[type="text"], input[type="number"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
            font-family: inherit; 
            transition: border-color 0.3s;
        }

        select:focus, input:focus { 
            border-color: #ffcc00; 
            outline: none; 
            box-shadow: 0 0 5px rgba(255, 204, 0, 0.3); 
        }
        
        .btn-submit { 
            padding: 12px 25px; 
            background-color: #cc0000; /* Gayong Red */
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 16px; 
            transition: background-color 0.2s; 
        }
        
        .btn-submit:hover { 
            background-color: #ff0000; 
        }

        .btn-cancel { 
            margin-left: 15px; 
            color: #555; 
            text-decoration: none; 
            font-weight: bold; 
        }

        .btn-cancel:hover { 
            color: #111; 
        }
        
        .error-box { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 4px; 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="header-title">Enroll Student to Session</h2>

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

        <form action="{{ route('sessions.store') }}" method="POST">
            @csrf 

            <div class="form-group">
                <label for="course_ID">Select Course:</label>
                <select id="course_ID" name="course_ID" required>
                    <option value="" disabled selected>-- Select Your Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->course_ID }}">
                            {{ $course->course_code }} - {{ $course->course_type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="user_ID">Select Student:</label>
                <select id="user_ID" name="user_ID" required>
                    <option value="" disabled selected>-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->user_ID }}">
                            {{ $student->name }} (ID: {{ $student->user_code ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="session_time">Session Time:</label>
                <input type="text" id="session_time" name="session_time" required placeholder="e.g., Every Saturday, 8:30 PM - 10:30 PM">
            </div>

            <div class="form-group">
                <label for="capacity">Class Capacity (Pax):</label>
                <input type="number" id="capacity" name="capacity" min="1" required placeholder="e.g., 30">
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Enroll Student</button>
                <a href="{{ route('sessions.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>