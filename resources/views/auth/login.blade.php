<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PSSGM Melaka Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #111; /* Hitam PSSGM */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #fff; /* Putih PSSGM */
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.7);
            text-align: center;
            border-top: 8px solid #cc0000; /* Merah PSSGM */
            border-bottom: 8px solid #ffcc00; /* Kuning PSSGM */
        }

        .logo {
            width: 130px;
            height: auto;
            margin-bottom: 15px;
        }

        h2 {
            margin: 0;
            color: #111;
            font-size: 22px;
            text-transform: uppercase;
            font-weight: 800;
        }

        .welcome-msg {
            color: #555;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111;
            font-size: 14px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
            font-size: 15px;
        }

        input:focus {
            border-color: #ffcc00; /* Kuning PSSGM */
            outline: none;
            background-color: #fffdf5;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background-color: #111; /* Hitam PSSGM */
            color: #ffcc00; /* Tulisan Kuning */
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #cc0000; /* Merah bila hover */
            color: #fff;
            transform: translateY(-2px);
        }

        hr {
            margin: 25px 0;
            border: 0;
            border-top: 1px solid #eee;
        }

        .register-links {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .register-links a {
            color: #cc0000; /* Merah PSSGM */
            text-decoration: none;
            font-weight: bold;
        }

        .register-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="{{ asset('images/logo_gayong.png') }}" alt="PSSGM Logo" class="logo">
        
        <h2>System Login</h2>
        <p class="welcome-msg">Welcome to PSSGM Melaka Management System</p>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div style="margin-bottom: 5px;">• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <hr>
        
        <div class="register-links">
            Don't have an account? <br>
            <a href="{{ route('users.create') }}">Member Registration</a> | 
            <a href="{{ route('staffs.create') }}">Staff</a> | 
            <a href="{{ route('instructors.create') }}">Instructor</a>
        </div>
    </div>

</body>
</html>