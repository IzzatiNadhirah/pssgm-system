<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gelanggang - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 800px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #ffcc00; /* Kuning/Emas untuk mode Edit */
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
        textarea.form-control { resize: vertical; min-height: 100px; }

        .btn-submit { 
            background-color: #ffcc00; color: #111; border: none; padding: 14px 24px; 
            font-size: 1em; font-weight: bold; border-radius: 6px; cursor: pointer; 
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; 
            width: 100%; justify-content: center; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-submit:hover { background-color: #e6b800; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,204,0,0.3); }

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
                <span class="material-icons">edit_location_alt</span>
                <h2>Edit Gelanggang: {{ $gelanggang->gel_name }}</h2>
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

            <form action="{{ route('gelanggangs.update', $gelanggang->gel_ID ?? $gelanggang->id) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group">
                    <label for="name">Gelanggang Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $gelanggang->gel_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" required>{{ old('address', $gelanggang->gel_address) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="caw_ID">Assign to Cawangan (Branch):</label>
                    <select id="caw_ID" name="caw_ID" class="form-control" required>
                        <option value="" disabled>-- Select Cawangan --</option>
                        @foreach($cawangans as $cawangan)
                            <option value="{{ $cawangan->caw_ID ?? $cawangan->id }}" {{ (old('caw_ID', $gelanggang->caw_ID) == ($cawangan->caw_ID ?? $cawangan->id)) ? 'selected' : '' }}>
                                {{ $cawangan->caw_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="instructor_ID">Assign Instructor:</label>
                    <select id="instructor_ID" name="instructor_ID" class="form-control" required>
                        <option value="" disabled>-- Select Instructor --</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->instructor_ID ?? $instructor->id }}" {{ (old('instructor_ID', $gelanggang->instructor_ID) == ($instructor->instructor_ID ?? $instructor->id)) ? 'selected' : '' }}>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-icons">update</span> Update Gelanggang
                </button>
            </form>

            <div class="back-nav">
                <a href="{{ route('gelanggangs.index') }}" class="back-link">
                    <span class="material-icons">cancel</span> Cancel & Back
                </a>
            </div>

        </div>
    </div>

</body>
</html>