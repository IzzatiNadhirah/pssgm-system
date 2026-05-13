<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff - PSSGM Melaka</title>
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
            max-width: 450px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-top: 8px solid #cc0000; /* Merah PSSGM */
            border-bottom: 8px solid #ffcc00; /* Kuning PSSGM */
            text-align: center;
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
            text-align: left;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #cc0000;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
            font-family: inherit;
            font-size: 15px;
        }

        input:focus {
            border-color: #ffcc00;
            outline: none;
            background-color: #fffdf5;
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
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .btn-register:hover {
            background-color: #cc0000;
            color: #fff;
            transform: translateY(-2px);
        }

        .footer-link {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .footer-link a {
            color: #cc0000;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <img src="{{ asset('images/logo_gayong.png') }}" alt="PSSGM Logo" class="logo">
        <h2>Register New Staff</h2>
        <p style="color: #666; font-size: 14px; margin-bottom: 25px;">Administrative Access Registration</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staffs.store') }}" method="POST">
            @csrf 
            
            <div class="form-group">
                <label for="name">Staff Name</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="ENTER FULL NAME">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="staff@pssgm.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-register">Register Staff</button>
        </form>

        <div class="footer-link">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>

</body>
</html>