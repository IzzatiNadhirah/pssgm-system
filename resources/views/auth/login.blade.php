<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PSSGM System</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <h2 style="text-align: center;">System Login</h2>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <label for="email">Email Address:</label><br>
            <input type="email" id="email" name="email" style="width: 100%;" required value="{{ old('email') }}"><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" style="width: 100%;" required><br><br>

            <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Login
            </button>
        </form>

        <hr>
        <p style="text-align: center;">Don't have an account? <br>
            <a href="{{ route('users.create') }}">Register as Member</a> | 
            <a href="{{ route('staffs.create') }}">Staff</a> | 
            <a href="{{ route('instructors.create') }}">Instructor</a>
        </p>
    </div>
</body>
</html>