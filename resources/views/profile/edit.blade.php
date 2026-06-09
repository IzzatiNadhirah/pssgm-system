<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { max-width: 900px; width: 100%; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #ffcc00; border-bottom: 8px solid #cc0000; }
        
        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px; }
        .header-area .material-icons { font-size: 32px; color: #111; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
        
        .form-group { margin-bottom: 5px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; font-size: 14px;}
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em; font-family: inherit; box-sizing: border-box; transition: 0.3s; }
        input:focus:not(.readonly-input), textarea:focus { border-color: #ffcc00; outline: none; background-color: #fffdf5;}
        
        /* Input khas untuk data yang tak boleh diubah */
        .readonly-input { background-color: #f5f5f5; color: #666; cursor: not-allowed; font-weight: bold; }

        .btn-submit { background-color: #ffcc00; color: #111; border: none; padding: 15px; width: 100%; border-radius: 8px; font-weight: bold; font-size: 1.1em; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; margin-top: 25px; }
        .btn-submit:hover { background-color: #e6b800; transform: translateY(-2px); }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 5px solid #dc3545; margin-bottom: 20px; border-radius: 4px; }

        .back-nav { margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; text-align: center; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">manage_accounts</span>
                <h2>My Profile</h2>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>IC Number</label>
                        <input type="text" class="readonly-input" value="{{ $user->icNo ?? $user->ic_number ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="tel_number" value="{{ old('tel_number', $user->tel_number) }}" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Bengkung Level</label>
                        <input type="text" class="readonly-input" value="{{ $user->bengkung_level ?? 'Tiada Rekod' }}" readonly title="Please contact your instructor to update your Bengkung level.">
                    </div>

                    <div class="form-group full-width">
                        <label>Home Address</label>
                        <textarea id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-icons">save</span> Save Changes
                </button>
            </form>

            <div class="back-nav">
                <a href="{{ route('dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>