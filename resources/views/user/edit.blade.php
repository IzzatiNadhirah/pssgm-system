<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit System User - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #111; /* Hitam PSSGM */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .form-container {
            background: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-top: 8px solid #cc0000; /* Merah */
            border-bottom: 8px solid #ffcc00; /* Kuning */
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        h2 {
            margin: 0 0 5px 0;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 24px;
            font-weight: 800;
        }

        .subtitle {
            color: #666;
            margin: 0;
            font-size: 15px;
        }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-left: 5px solid #cc0000;
            margin-bottom: 25px;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width { grid-column: span 2; }

        .form-group { margin-bottom: 5px; }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111;
            font-size: 14px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
            font-family: inherit;
            font-size: 15px;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #ffcc00;
            outline: none;
            background-color: #fffdf5;
        }

        .password-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #111;
            margin-top: 10px;
        }

        small {
            color: #777;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            align-items: center;
        }

        .btn-submit {
            padding: 15px 30px;
            background-color: #111;
            color: #ffcc00;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.3s;
            letter-spacing: 1px;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: #cc0000;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-cancel {
            padding: 15px 30px;
            background-color: #eee;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s;
            text-align: center;
        }

        .btn-cancel:hover {
            background-color: #ddd;
        }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .button-group { flex-direction: column; }
            .btn-cancel { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>

    <div class="form-container">
        
        <div class="header-section">
            <h2>Edit User Profile</h2>
            <p class="subtitle">Updating record for: <b style="color: #111;">{{ $user->name }}</b> (ID: {{ $user->user_ID }})</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <b>Please correct the following errors:</b>
                <ul style="margin-top: 5px; margin-bottom: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->user_ID) }}" method="POST">
            @csrf 
            @method('PUT') 
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="icNo">IC Number</label>
                    <input type="text" id="icNo" name="icNo" value="{{ old('icNo', $user->icNo) }}" pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}" required>
                    <small>* Format: 000000-00-0000</small>
                </div>

                <div class="form-group">
                    <label for="tel_number">Phone Number</label>
                    <input type="text" id="tel_number" name="tel_number" value="{{ old('tel_number', $user->tel_number) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="bengkung_level">Bengkung Level</label>
                    <select id="bengkung_level" name="bengkung_level" required>
                        <option value="{{ $user->bengkung_level }}" selected>{{ $user->bengkung_level }} (Current)</option>
                        <option disabled>--- KANAK-KANAK ---</option>
                        <option value="Hitam Kosong">Hitam Kosong</option>
                        <option value="Awan Putih Cula Hijau">Awan Putih Cula Hijau</option>
                        <option value="Awan Putih Cula Merah">Awan Putih Cula Merah</option>
                        <option value="Awan Putih Cula Kuning">Awan Putih Cula Kuning</option>
                        <option value="Awan Putih Cula Hitam">Awan Putih Cula Hitam</option>
                        <option disabled>--- DEWASA ---</option>
                        <option value="Awan Putih">Awan Putih</option>
                        <option value="Pelangi Hijau">Pelangi Hijau</option>
                        <option value="Pelangi Merah I">Pelangi Merah I</option>
                        <option value="Pelangi Merah II">Pelangi Merah II</option>
                        <option value="Pelangi Merah III">Pelangi Merah III</option>
                        <option value="Pelangi Kuning I">Pelangi Kuning I</option>
                        <option value="Pelangi Kuning II">Pelangi Kuning II</option>
                        <option value="Pelangi Kuning III">Pelangi Kuning III</option>
                        <option value="Pelangi Kuning IV">Pelangi Kuning IV</option>
                        <option value="Hitam Pelangi Cula Sakti I">Hitam Pelangi Cula Sakti I</option>
                        <option value="Hitam Pelangi Cula Sakti II">Hitam Pelangi Cula Sakti II</option>
                        <option value="Hitam Pelangi Cula Sakti III">Hitam Pelangi Cula Sakti III</option>
                        <option value="Hitam Pelangi Cula Sakti IV">Hitam Pelangi Cula Sakti IV</option>
                        <option value="Hitam Pelangi Cula Sakti V">Hitam Pelangi Cula Sakti V</option>
                        <option value="Hitam Pelangi Cula Sakti VI">Hitam Pelangi Cula Sakti VI</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="address">Mailing Address</label>
                    <textarea id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="password-section">
                <label for="password">Reset Password (Optional)</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" style="padding-right: 40px; background: #fff;">
                    <span id="togglePasswordIcon" class="material-icons" onclick="togglePassword('password', 'togglePasswordIcon')" style="position: absolute; right: 10px; cursor: pointer; color: #666; user-select: none;">
                        visibility_off
                    </span>
                </div>
                <small style="color: #cc0000; font-weight: bold;">Only fill this out if the member requested a password reset.</small>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">
                    <span class="material-icons" style="font-size: 18px;">save</span> Update User Data
                </button>
                <a href="{{ route('users.index') }}" class="btn-cancel">Cancel</a>
            </div>

        </form>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            var pwdInput = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            
            if (pwdInput.type === "password") {
                pwdInput.type = "text";
                icon.textContent = "visibility";
                icon.style.color = "#cc0000"; 
            } else {
                pwdInput.type = "password";
                icon.textContent = "visibility_off";
                icon.style.color = "#666"; 
            }
        }
    </script>
</body>
</html>