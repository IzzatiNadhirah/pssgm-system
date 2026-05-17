<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Class Schedule - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Navbar dah ada style sendiri dari include, kita just tambah untuk body */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }

        .form-container { max-width: 650px; width: 100%; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); border-top: 5px solid #ffcc00; }
        .header-title { color: #111; margin-top: 0; border-bottom: 2px solid #cc0000; padding-bottom: 10px; display: inline-block; margin-bottom: 25px; text-transform: uppercase; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        
        /* Input ditukar kembali kepada yang asal dan kemas */
        input[type="datetime-local"], input[type="time"], input[type="number"], select { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; transition: 0.3s; font-family: inherit; cursor: pointer; font-size: 1em;
        }
        input:focus, select:focus { border-color: #ffcc00; outline: none; box-shadow: 0 0 5px rgba(255, 204, 0, 0.3); }
        
        .btn-submit { padding: 12px 25px; background-color: #111; color: #ffcc00; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.2s; text-transform: uppercase;}
        .btn-submit:hover { background-color: #333; transform: translateY(-2px); }
        
        .btn-cancel { margin-left: 15px; color: #cc0000; text-decoration: none; font-weight: bold; }
        .btn-cancel:hover { color: #111; }
        
        .info-box { background-color: #e9ecef; padding: 15px; border-radius: 4px; margin-bottom: 20px; color: #444; border-left: 4px solid #cc0000; font-size: 1.1em;}
        
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 5px solid #dc3545; margin-bottom: 20px; border-radius: 4px; }
        .alert-error ul { margin: 10px 0 0 0; padding-left: 20px; }
    </style>
</head>
<body>
    
    @include('layouts.navbar')

    <div class="content-area">
        <div class="form-container">
            <h2 class="header-title">
                <span class="material-icons" style="vertical-align: bottom; font-size: 28px;">event_available</span> Set Class Schedule
            </h2>

            @if ($errors->any())
                <div class="alert-error">
                    <b><span class="material-icons" style="font-size: 18px; vertical-align: bottom;">error</span> Validation Errors:</b>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="info-box">
                <span class="material-icons" style="vertical-align: bottom;">menu_book</span>
                <b>Course:</b> {{ $course->course_type }}
            </div>

            <form action="{{ route('courses.update_schedule', $course->course_ID ?? $course->id) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group">
                    <label for="gel_ID">Select Location (Gelanggang):</label>
                    <select id="gel_ID" name="gel_ID" required>
                        <option value="" disabled {{ empty($course->gel_ID) && empty($course->gelanggang_ID) ? 'selected' : '' }}>-- Pilih Gelanggang --</option>
                        
                        @if(isset($gelanggangs))
                            @foreach($gelanggangs as $gel)
                                <option value="{{ $gel->gel_ID ?? $gel->id }}" {{ ($course->gel_ID ?? $course->gelanggang_ID) == ($gel->gel_ID ?? $gel->id) ? 'selected' : '' }}>
                                    {{ $gel->gel_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label for="start_time">Date & Start Time:</label>
                        <input type="datetime-local" id="start_time" name="start_time" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="end_time">End Time:</label>
                        <input type="time" id="end_time" name="end_time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="capacity">Class Capacity (Pax):</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $course->capacity) }}" min="1" required placeholder="e.g., 30">
                </div>

                <div style="margin-top: 30px; display: flex; align-items: center;">
                    <button type="submit" class="btn-submit">Save Schedule</button>
                    <a href="{{ route('courses.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>