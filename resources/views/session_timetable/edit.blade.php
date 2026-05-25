<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Session - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 800px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px; }
        .header-area .material-icons { font-size: 32px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 5px solid #dc3545; margin-bottom: 20px; border-radius: 4px; }
        .alert-error ul { margin: 10px 0 0 0; padding-left: 20px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        
        .form-control { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; 
            box-sizing: border-box; font-family: inherit; font-size: 1em; transition: 0.3s;
        }
        .form-control:focus { border-color: #cc0000; outline: none; box-shadow: 0 0 5px rgba(204,0,0,0.2); }
        select.form-control { cursor: pointer; }
        input[type="datetime-local"], input[type="time"] { cursor: pointer; }

        /* GRID UNTUK MASA */
        .time-grid { display: flex; gap: 15px; }
        .time-grid .form-group { flex: 1; margin-bottom: 20px; }

        .btn-submit { 
            background-color: #ff9900; color: #111; border: none; padding: 14px 24px; 
            font-size: 1em; font-weight: bold; border-radius: 6px; cursor: pointer; 
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; 
            width: 100%; justify-content: center; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-submit:hover { background-color: #e68a00; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(204,0,0,0.3); }

        .back-nav { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: center; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">edit_calendar</span>
                <h2>Edit Class Session</h2>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <b><span class="material-icons" style="font-size: 18px; vertical-align: bottom;">error</span> Whoops! Please fix these errors:</b>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sessions.update', $timetable->id) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group">
                    <label for="course_ID">Select Course:</label>
                    <select id="course_ID" name="course_ID" class="form-control" required>
                        <option value="" disabled>-- Select Your Assigned Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_ID ?? $course->id }}" {{ $timetable->course_ID == ($course->course_ID ?? $course->id) ? 'selected' : '' }}>
                                {{ $course->course_type }} ({{ $course->course_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="gel_ID">Select Training Location (Gelanggang):</label>
                    <select id="gel_ID" name="gel_ID" class="form-control" required>
                        <option value="" disabled>-- Select Active Gelanggang --</option>
                        @foreach($gelanggangs as $gelanggang)
                            <option value="{{ $gelanggang->gel_ID ?? $gelanggang->id }}" {{ $timetable->gel_ID == ($gelanggang->gel_ID ?? $gelanggang->id) ? 'selected' : '' }}>
                                {{ $gelanggang->gel_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- MASA DIPECAHKAN KEPADA DUA KOTAK DENGAN DATA LAMA --}}
                <div class="time-grid">
                    <div class="form-group">
                        <label for="start_time">Start Date & Time:</label>
                        <input type="datetime-local" id="start_time" name="start_time" class="form-control" value="{{ \Carbon\Carbon::parse($timetable->start_time)->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time:</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" value="{{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="capacity">Maximum Capacity (Pax):</label>
                    <input type="number" id="capacity" name="capacity" class="form-control" min="1" value="{{ $timetable->capacity }}" required>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-icons">update</span> Update Schedule
                </button>
            </form>

            <div class="back-nav">
                <a href="{{ route('sessions.index') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Manage Sessions
                </a>
            </div>

        </div>
    </div>

</body>
</html>