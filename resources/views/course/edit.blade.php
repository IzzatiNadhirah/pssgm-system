<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 800px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #ffcc00; /* Warna kuning untuk mod Edit */
            border-bottom: 8px solid #cc0000; 
        }

        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px; }
        .header-area .material-icons { font-size: 32px; color: #ffcc00; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        /* --- ALERTS --- */
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 5px solid #dc3545; margin-bottom: 20px; border-radius: 4px; }
        .alert-error ul { margin: 10px 0 0 0; padding-left: 20px; }
        .alert-error li { margin-bottom: 5px; }

        /* --- FORM STYLES --- */
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        
        .form-control { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; 
            box-sizing: border-box; font-family: inherit; font-size: 1em; transition: 0.3s;
        }
        .form-control:focus { border-color: #ffcc00; outline: none; box-shadow: 0 0 5px rgba(255,204,0,0.4); }
        
        /* Style khas untuk input disabled */
        .form-control:disabled { background-color: #f1f1f1; color: #666; cursor: not-allowed; border-color: #ddd; }

        /* Butang Update (Kuning) */
        .btn-submit { 
            background-color: #ffcc00; color: #111; border: none; padding: 14px 24px; 
            font-size: 1em; font-weight: bold; border-radius: 6px; cursor: pointer; 
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; 
            width: 100%; justify-content: center; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-submit:hover { background-color: #e6b800; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,204,0,0.3); }

        /* --- KITA EJAS SINI: Tambah CSS untuk Info Card --- */
        .info-card { background: #fff8e6; border-left: 5px solid #ffcc00; padding: 20px; border-radius: 8px; margin-top: 35px; color: #444; }
        .info-card h4 { margin: 0 0 10px 0; display: flex; align-items: center; gap: 8px; color: #111; font-size: 1.1em; }
        .info-card p { margin: 0; font-size: 0.9em; line-height: 1.6; }
        .info-card ul { margin: 10px 0 0; padding-left: 20px; font-size: 0.9em; line-height: 1.6; }

        .back-nav { margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; text-align: center; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">edit_note</span>
                <h2>Edit Course: {{ $course->course_code }}</h2>
            </div>

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

            <form action="{{ route('courses.update', $course->course_ID ?? $course->id) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group">
                    <label for="course_type">Course Type:</label>
                    <select id="course_type" name="course_type" class="form-control" required>
                        <option value="Silat Olahraga" {{ $course->course_type == 'Silat Olahraga' ? 'selected' : '' }}>Silat Olahraga</option>
                        <option value="Silat Seni" {{ $course->course_type == 'Silat Seni' ? 'selected' : '' }}>Silat Seni</option>
                        <option value="Pelajaran Silibus" {{ $course->course_type == 'Pelajaran Silibus' ? 'selected' : '' }}>Pelajaran Silibus</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Assigned Instructor:</label>
                    <input type="text" class="form-control" value="{{ $course->instructor->name ?? 'Unknown Instructor' }}" disabled>
                    
                    <input type="hidden" name="instructor_ID" value="{{ $course->instructor_ID }}">
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-icons">update</span> Update Course
                </button>
            </form>

            <!-- INFO CARD EXPLANATION -->
            <div class="info-card">
                <h4><span class="material-icons" style="color: #ff9900;">info</span> Where are the Date, Time, and Location settings?</h4>
                <p>As a System Staff, you are only managing the <b>Course Subject</b> and the assigned <b>Instructor</b>. The specific class schedules are managed separately:</p>
                <ul>
                    <li>The assigned instructor (<b>{{ $course->instructor->name ?? 'TBA' }}</b>) will log into their dashboard to create and manage the session dates, times, and location (Gelanggang).</li>
                    <li>If you need to change a schedule, please inform the respective instructor to update it from their panel.</li>
                </ul>
            </div>

            <div class="back-nav">
                <a href="{{ route('courses.index') }}" class="back-link">
                    <span class="material-icons">cancel</span> Cancel & Back
                </a>
            </div>

        </div>
    </div>

</body>
</html>