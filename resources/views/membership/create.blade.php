<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership & Payment - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body, button, input, select, textarea, span, div, a, p, h1, h2, h3, h4, h5, h6 { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; 
        }
        .material-icons {
            font-family: 'Material Icons' !important; 
        }
        body { background-color: #111; margin: 0; min-height: 100vh; }

        /* --- CONTENT AREA --- */
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 650px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #ffcc00; border-bottom: 8px solid #111; 
        }

        .header { text-align: center; margin-bottom: 30px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; font-size: 1.5em; }

        .info-box { background-color: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 5px solid #111; margin-bottom: 25px; }

        /* KITA EJAS SINI: Tukar warna h3 kepada hitam */
        h3 { font-size: 1.1em; color: #111; border-bottom: 2px solid #eee; padding-bottom: 8px; margin-top: 25px; display: flex; align-items: center; gap: 8px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; font-size: 0.9em; }

        select, input[type="file"] { width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; font-family: inherit; font-size: 1em; cursor: pointer; transition: 0.3s; box-sizing: border-box; }
        select:focus, input[type="file"]:focus { border-color: #ffcc00; outline: none; background-color: #fffdf5; }

        .btn-pay {
            width: 100%; padding: 15px; background-color: #111; color: #ffcc00; border: none; 
            border-radius: 8px; font-size: 1.1em; font-weight: bold; cursor: pointer; 
            text-transform: uppercase; transition: 0.3s; margin-top: 20px; 
            display: flex; justify-content: center; align-items: center; gap: 10px;
        }
        /* KITA EJAS SINI: Tukar warna hover butang supaya bukan merah */
        .btn-pay:hover { background-color: #ffcc00; color: #111; transform: translateY(-2px); }

        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9em; }

        .back-nav { margin-top: 25px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .back-nav a { color: #666; text-decoration: none; font-weight: bold; font-size: 0.9em; display: inline-flex; align-items: center; gap: 5px; }
        /* KITA EJAS SINI: Tukar warna hover pautan back */
        .back-nav a:hover { color: #ffcc00; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header">
                <h2>Membership Registration</h2>
                <p style="color: #666; font-size: 0.9em;">Official Silat Seni Gayong Malaysia Membership</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <b>Sila betulkan ralat berikut:</b>
                    <ul style="margin: 5px 0 0 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 

                <h3><span class="material-icons" style="color: #ffcc00;">person</span> Section 1: Member Profile</h3>
                <div class="info-box">
                    <span style="display: block; font-size: 0.8em; color: #666; text-transform: uppercase;">Registering as:</span>
                    <b style="font-size: 1.1em;">{{ Auth::user()->name }}</b><br>
                    <span style="font-family: monospace; color: #444;">IC: {{ Auth::user()->icNo ?? Auth::user()->ic_no }}</span>
                </div>

                <div class="form-group">
                    <label for="member_type">Select Membership Package:</label>
                    <select id="member_type" name="member_type" required>
                        <option value="" disabled selected>-- Pilih Jenis Keahlian --</option>
                        <option value="Tahunan" {{ old('member_type') == 'Tahunan' ? 'selected' : '' }}>Ahli Tahunan (RM 20.00)</option>
                        <option value="Sepanjang Hayat" {{ old('member_type') == 'Sepanjang Hayat' ? 'selected' : '' }}>Ahli Sepanjang Hayat (RM 200.00)</option>
                    </select>
                </div>

                <h3><span class="material-icons" style="color: #ffcc00;">account_balance_wallet</span> Section 2: Payment Details</h3>
                
                <input type="hidden" name="payment_method" value="Manual Transfer">
                
                <div class="form-group" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 20px;">
                    <span style="display: flex; align-items: center; gap: 5px; font-weight: bold; margin-bottom: 5px;">
                        <span class="material-icons" style="font-size: 18px;">info</span> Notice
                    </span>
                    <p style="margin: 0; font-size: 0.9em;">
                        All membership fees must be paid via <b>Manual Bank Transfer</b>. The system will automatically record today's date for this transaction.
                    </p>
                </div>

                {{-- RUANGAN UPLOAD RESIT --}}
                <div class="form-group" style="background: #fafafa; padding: 20px; border-radius: 8px; border: 2px dashed #ddd;">
                    {{-- KITA EJAS SINI: Tukar warna label ikon resit kepada hitam --}}
                    <label style="color: #111; display: flex; align-items: center; gap: 5px;">
                        <span class="material-icons" style="color: #ffcc00; font-size: 18px;">receipt</span> Upload Payment Receipt
                    </label>
                    <p style="font-size: 0.85em; color: #555; margin-bottom: 10px;">Please transfer the exact amount to <b>MAYBANK 5600 1234 5678 (PSSGM Melaka)</b> and upload the receipt here.</p>
                    
                    <input type="file" id="receipt_file" name="receipt_file" accept=".pdf, image/png, image/jpeg, image/jpg" style="background: white;" required>
                </div>

                <button type="submit" class="btn-pay">
                    <span class="material-icons">verified_user</span> Complete Registration & Pay
                </button>

                <div class="back-nav">
                    <a href="{{ route('dashboard') }}">
                        <span class="material-icons" style="font-size: 16px;">arrow_back</span> 
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>