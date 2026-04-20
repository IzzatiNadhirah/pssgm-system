<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit System User</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .form-container { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        input[type="text"], input[type="email"], input[type="password"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { padding: 10px 20px; background-color: #ffc107; color: #212529; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 16px; }
        .btn-cancel { margin-left: 15px; color: #007bff; text-decoration: none; font-weight: bold; }
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2 style="margin-top: 0; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px;">Edit User Profile</h2>
        <p style="color: #666; margin-bottom: 20px;">Updating record for: <b>{{ $user->name }}</b> (ID: {{ $user->user_ID }})</p>

        @if ($errors->any())
            <div class="error-box">
                <b>Please correct the following errors:</b>
                <ul style="margin-top: 5px; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->user_ID) }}" method="POST">
            @csrf 
            @method('PUT') <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="icNo">IC Number (Format: 000000-00-0000):</label>
                <input type="text" id="icNo" name="icNo" value="{{ old('icNo', $user->icNo) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="tel_number">Phone Number:</label>
                <input type="text" id="tel_number" name="tel_number" value="{{ old('tel_number', $user->tel_number) }}" required>
            </div>

            <div class="form-group">
                <label for="bengkung_level">Bengkung Level:</label>
                <input type="text" id="bengkung_level" name="bengkung_level" value="{{ old('bengkung_level', $user->bengkung_level) }}" required>
            </div>

            <div class="form-group">
                <label for="address">Mailing Address:</label>
                <textarea id="address" name="address" rows="4" required>{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-group" style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #17a2b8;">
                <label for="password">New Password (Optional):</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep the current password">
                <small style="color: #666; display: block; margin-top: 5px;">Only fill this out if the user requested a password reset.</small>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Update User Data</button>
                <a href="{{ route('users.index') }}" class="btn-cancel">Cancel and Return</a>
            </div>
        </form>

    </div>

</body>
</html>