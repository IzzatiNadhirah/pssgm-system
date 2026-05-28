<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #111;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-top: 8px solid #cc0000;
            border-bottom: 8px solid #ffcc00;
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 110px;
            margin-bottom: 15px;
        }

        h2 {
            margin: 0;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 22px;
            font-weight: 800;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #cc0000;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width { grid-column: span 2; }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #ffcc00;
            outline: none;
            background-color: #fffdf5;
        }

        small {
            color: #777;
            font-size: 11px;
            display: block;
            margin-top: 5px;
        }

        .btn-register {
            width: 100%;
            padding: 15px;
            background-color: #111;
            color: #ffcc00;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.3s;
            margin-top: 25px;
            letter-spacing: 1px;
        }

        .btn-register:hover {
            background-color: #cc0000;
            color: #fff;
            transform: translateY(-2px);
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .footer-link a {
            color: #cc0000;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="header-section">
            <img src="{{ asset('images/logo_gayong.png') }}" alt="PSSGM Logo" class="logo">
            <h2>Register New Member</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <b>Registration Failed:</b>
                <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf 
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="AMIR BIN AZMAN">
                </div>

                <div class="form-group">
                    <label for="icNo">IC Number</label>
                    <input type="text" id="icNo" name="icNo" placeholder="000000-00-0000" pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}" required value="{{ old('icNo') }}">
                    <small>* Format: 000000-00-0000</small>
                </div>

                <div class="form-group">
                    <label for="tel_number">Telephone Number</label>
                    <input type="text" id="tel_number" name="tel_number" required value="{{ old('tel_number') }}" placeholder="012-3456789">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="amir@example.com">
                </div>

                <div class="form-group">
                    <label for="password">Account Password</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="password" name="password" required placeholder="••••••••" style="padding-right: 40px;">
                        <span id="togglePasswordIcon" class="material-icons" onclick="togglePassword('password', 'togglePasswordIcon')" style="position: absolute; right: 10px; cursor: pointer; color: #666; user-select: none;">
                            visibility_off
                        </span>
                    </div>
                    <small>* Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="age_category">Age Category</label>
                    <select id="age_category" name="age_category" onchange="updateBengkung()" required>
                        <option value="" disabled selected>-- Select Category --</option>
                        <option value="kanak" {{ old('age_category') == 'kanak' ? 'selected' : '' }}>Kanak-Kanak</option>
                        <option value="dewasa" {{ old('age_category') == 'dewasa' ? 'selected' : '' }}>Dewasa</option>
                    </select>
                    <small id="age_display" style="color: #28a745; font-weight: bold;"></small>
                </div>

                <div class="form-group">
                    <label for="bengkung_level">Bengkung Level</label>
                    <select id="bengkung_level" name="bengkung_level" required>
                        <option value="" disabled selected>-- Select Age Category First --</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="address">Home Address</label>
                    <textarea id="address" name="address" rows="3" required placeholder="Enter full home address">{{ old('address') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-register">Register Member</button>
        </form>

        {{-- SEMAKAN: Hanya tunjuk kalau BUKAN admin/staff yang tengah login --}}
        @if(!Auth::guard('staff')->check())
            <div class="footer-link">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        @endif
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        var pwdInput = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        
        if (pwdInput.type === "password") {
            pwdInput.type = "text";
            icon.textContent = "visibility";
            icon.style.color = "#cc0000"; // Merah PSSGM bila nampak password
        } else {
            pwdInput.type = "password";
            icon.textContent = "visibility_off";
            icon.style.color = "#666"; // Kembali kelabu
        }
    }

    function updateBengkung() {
        const category = document.getElementById('age_category').value;
        const bengkungDropdown = document.getElementById('bengkung_level');
        bengkungDropdown.innerHTML = '<option value="" disabled selected>-- Select Bengkung Level --</option>';

        let options = [];
        if (category === 'kanak') {
            options = ['Hitam Kosong', 'Awan Putih Cula Hijau', 'Awan Putih Cula Merah', 'Awan Putih Cula Kuning', 'Awan Putih Cula Hitam']; 
        } else if (category === 'dewasa') {
            options = ['Hitam Kosong', 'Awan Putih', 'Pelangi Hijau', 'Pelangi Merah (I - III)', 'Pelangi Kuning (I - IV)', 'Hitam Pelangi Cula Sakti (I - VI)']; 
        }

        options.forEach(function(level) {
            let newOption = document.createElement('option');
            newOption.value = level;
            newOption.textContent = level;
            // Kekalkan pilihan lama jika ada error validation dari server
            if("{{ old('bengkung_level') }}" === level) {
                newOption.selected = true;
            }
            bengkungDropdown.appendChild(newOption);
        });
    }

    function calculateAgeFromIC() {
        let icValue = document.getElementById('icNo').value.replace(/-/g, ''); // Buang sengkang
        let categorySelect = document.getElementById('age_category');
        let ageDisplay = document.getElementById('age_display');

        if (icValue.length >= 6) {
            let year = parseInt(icValue.substring(0, 2));
            let month = parseInt(icValue.substring(2, 4));
            let day = parseInt(icValue.substring(4, 6));

            if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
                let currentYear = new Date().getFullYear();
                let currentYear2Digits = parseInt(currentYear.toString().slice(-2));

                // Tentukan abad kelahiran (jika lahir tahun <= tahun semasa, cth: 26 -> 2026. Jika 99 -> 1999)
                let birthYear = (year <= currentYear2Digits) ? 2000 + year : 1900 + year;
                let birthDate = new Date(birthYear, month - 1, day);
                let today = new Date();

                // Kira umur yang tepat
                let age = today.getFullYear() - birthDate.getFullYear();
                let m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Pilih kategori & kunci (lock) dropdown
                if (age >= 13) {
                    categorySelect.value = 'dewasa';
                    ageDisplay.textContent = 'Auto-calculated Age: ' + age + ' (Dewasa)';
                } else {
                    categorySelect.value = 'kanak';
                    ageDisplay.textContent = 'Auto-calculated Age: ' + age + ' (Kanak-Kanak)';
                }
                
                // Halang user dari mengubah kategori secara manual
                categorySelect.style.pointerEvents = 'none';
                categorySelect.style.backgroundColor = '#eee';
                
                // Terus kemaskini senarai bengkung
                updateBengkung();
            }
        } else {
            // Jika IC dipadam, buka balik kunci dropdown
            categorySelect.style.pointerEvents = 'auto';
            categorySelect.style.backgroundColor = '#fff';
            ageDisplay.textContent = '';
        }
    }

    // Pasang 'listener' supaya kiraan berjalan setiap kali pengguna menaip di ruangan IC
    document.getElementById('icNo').addEventListener('input', calculateAgeFromIC);

    // Semak pada waktu page loading (penting jika page direfresh kerana ada validation error)
    window.onload = function() {
        if(document.getElementById('icNo').value) {
            calculateAgeFromIC();
        } else if(document.getElementById('age_category').value) {
            updateBengkung();
        }
    };
    </script>
</body>
</html>