<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Gelanggang - PSSGM Melaka</title>
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

        /* --- ALERTS --- */
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px; }
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
        .form-control:focus { border-color: #cc0000; outline: none; box-shadow: 0 0 5px rgba(204,0,0,0.2); }
        .form-control:disabled { background-color: #f5f5f5; cursor: not-allowed; color: #888; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        .btn-submit { 
            background-color: #2a2c04; color: white; border: none; padding: 14px 24px; 
            font-size: 1em; font-weight: bold; border-radius: 6px; cursor: pointer; 
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; 
            width: 100%; justify-content: center; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-submit:hover { background-color: #aa0000; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(204,0,0,0.3); }

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
                <span class="material-icons">stadium</span>
                <h2>Register New Gelanggang</h2>
            </div>

            @if (session('success'))
                <script>
                    alert("{{ session('success') }}");
                </script>
                <div class="alert-success">
                    <span class="material-icons">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <b><span class="material-icons" style="font-size: 18px; vertical-align: bottom;">error</span> Whoops! Something went wrong:</b>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gelanggangs.store') }}" method="POST">
                @csrf 

                <div class="form-group">
                    <label for="name">Gelanggang Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="e.g., Gelanggang UTeM" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" placeholder="Enter full address here..." required>{{ old('address') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="caw_ID">Assign to Cawangan (Branch):</label>
                    <select id="caw_ID" name="caw_ID" class="form-control" required>
                        <option value="" disabled {{ old('caw_ID') ? '' : 'selected' }}>-- Select Cawangan --</option>
                        @foreach($cawangans as $cawangan)
                            <option value="{{ $cawangan->caw_ID ?? $cawangan->id }}" {{ old('caw_ID') == ($cawangan->caw_ID ?? $cawangan->id) ? 'selected' : '' }}>
                                {{ $cawangan->caw_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="instructor_ID">Assign Instructor:</label>
                    {{-- Default disabled, JS akan enable lepas Cawangan dipilih --}}
                    <select id="instructor_ID" name="instructor_ID" class="form-control" required disabled>
                        <option value="" disabled selected>-- Select Cawangan First --</option>
                        
                        {{-- Kita inject data-caw-id ke dalam setiap option supaya JS boleh baca --}}
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->instructor_ID ?? $instructor->id }}" data-caw-id="{{ $instructor->caw_ID }}">
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-icons">save</span> Register Gelanggang
                </button>
            </form>

            <div class="back-nav">
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'super_admin')
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Super Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Staff Dashboard
                    </a>
                @endif
            </div>

        </div>
    </div>

    {{-- KOD JAVASCRIPT UNTUK FILTER INSTRUCTOR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cawSelect = document.getElementById('caw_ID');
            const instSelect = document.getElementById('instructor_ID');
            
            // Simpan senarai asal semua instructor dalam memory browser
            const originalOptions = Array.from(instSelect.options).filter(opt => opt.value !== "");

            function filterInstructors() {
                const selectedCawId = cawSelect.value;

                // Reset kotak instructor jadi kosong semula
                instSelect.innerHTML = '';

                // Buat placeholder baru
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.disabled = true;
                placeholder.selected = true;

                if (!selectedCawId) {
                    placeholder.text = '-- Select Cawangan First --';
                    instSelect.appendChild(placeholder);
                    instSelect.disabled = true;
                    return;
                }

                placeholder.text = '-- Select Instructor --';
                instSelect.appendChild(placeholder);
                instSelect.disabled = false;

                // Masukkan balik Instructor yang sama caw_ID dengan Cawangan dipilih
                let hasInstructor = false;
                originalOptions.forEach(opt => {
                    if (opt.getAttribute('data-caw-id') === selectedCawId) {
                        instSelect.appendChild(opt.cloneNode(true));
                        hasInstructor = true;
                    }
                });

                // Kalau Cawangan tu takde Instructor langsung
                if (!hasInstructor) {
                    const noInst = document.createElement('option');
                    noInst.value = '';
                    noInst.disabled = true;
                    noInst.text = '-- No Instructor available in this Cawangan --';
                    instSelect.appendChild(noInst);
                }
            }

            // Dengar kalau-kalau staf tukar pilihan Cawangan
            cawSelect.addEventListener('change', filterInstructors);

            // Jalankan sekali waktu page baru loading (Penting kalau ralat/validation error)
            filterInstructors();

            // Kalau ada data lama (lepas validation error), pilih balik instructor tu
            const oldInstructorId = "{{ old('instructor_ID') }}";
            if (oldInstructorId) {
                instSelect.value = oldInstructorId;
            }
        });
    </script>
</body>
</html>