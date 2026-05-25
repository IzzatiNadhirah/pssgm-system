<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Instructor - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #111; /* Hitam PSSGM */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background: #fff; /* Putih PSSGM */
            width: 100%;
            max-width: 750px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-top: 8px solid #cc0000; /* Merah PSSGM */
            border-bottom: 8px solid #ffcc00; /* Kuning PSSGM */
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

        /* Success Alert */
        .alert-success { 
            background-color: #d4edda; 
            color: #155724; 
            padding: 15px; 
            border-left: 5px solid #28a745; 
            margin-bottom: 25px; 
            border-radius: 6px; 
            font-size: 14px;
        }

        /* Error Alert */
        .alert-error {
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

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
            font-family: inherit;
        }

        input:focus, textarea:focus {
            border-color: #ffcc00; /* Kuning PSSGM bila focus */
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
            <h2>Register New Instructor</h2>
            <p style="color: #666; font-size: 14px; margin-top: 5px;">Instructor Panel Registration</p>
        </div>

        @if (session('success'))
            <script>alert("{{ session('success') }}");</script>
            <div class="alert-success">
                <b>Success!</b> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('instructors.store') }}" method="POST">
            @csrf 
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="ENTER FULL NAME">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="instructor@example.com">
                </div>

                <div class="form-group">
                    <label for="tel_number">Telephone Number</label>
                    <input type="text" id="tel_number" name="tel_number" required value="{{ old('tel_number') }}" placeholder="012-3456789">
                </div>

                <div class="form-group full-width">
                    <label for="password">Account Password</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="password" name="password" required placeholder="••••••••" style="padding-right: 40px;">
                        <span id="togglePasswordIcon" class="material-icons" onclick="togglePassword('password', 'togglePasswordIcon')" style="position: absolute; right: 10px; cursor: pointer; color: #666; user-select: none;">
                            visibility_off
                        </span>
                    </div>
                    <small>* Minimum 8 characters long.</small>
                </div>

                <div class="form-group full-width">
                    <label for="address">Mailing Address</label>
                    <textarea id="address" name="address" rows="3" required placeholder="Enter full home address">{{ old('address') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-register">Register Instructor</button>
        </form>

        <div class="footer-link">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
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
    </script>
</body>
</html>