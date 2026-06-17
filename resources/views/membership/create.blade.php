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

        /* --- NAVIGATION BAR --- */
        .navbar {
            background-color: #000;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #ffcc00;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .nav-left { display: flex; align-items: center; gap: 12px; color: white; font-weight: bold; }
        .nav-logo-small { width: 40px; height: auto; }

        .nav-center { display: flex; gap: 20px; }
        
        .nav-link {
            color: white !important; 
            text-decoration: none !important;
            font-size: 0.9em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }
        .nav-link:hover { color: #ffcc00 !important; }

        .nav-right { display: flex; align-items: center; gap: 20px; }
        .user-meta { text-align: right; color: white; line-height: 1.2; }
        .user-meta .user-name { display: block; font-size: 0.9em; font-weight: bold; }
        .user-meta .user-role { display: block; font-size: 0.75em; color: #ffcc00; }

        .btn-logout-nav {
            background-color: #cc0000; color: white; border: none; padding: 8px 15px; 
            border-radius: 6px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px;
            transition: 0.3s;
        }
        .btn-logout-nav:hover { background-color: #ff0000; }

        /* --- CONTENT AREA --- */
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 650px; width: 100%; background: white; padding: 40px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header { text-align: center; margin-bottom: 30px; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; font-size: 1.5em; }

        .info-box { background-color: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 5px solid #111; margin-bottom: 25px; }

        h3 { font-size: 1.1em; color: #cc0000; border-bottom: 2px solid #eee; padding-bottom: 8px; margin-top: 25px; display: flex; align-items: center; gap: 8px; }

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
        .btn-pay:hover { background-color: #cc0000; color: #fff; transform: translateY(-2px); }

        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9em; }

        .back-nav { margin-top: 25px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .back-nav a { color: #666; text-decoration: none; font-weight: bold; font-size: 0.9em; display: inline-flex; align-items: center; gap: 5px; }
        .back-nav a:hover { color: #cc0000; }
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

            {{-- Borang dah sempurna ada enctype. KITA EJAS SINI: Pastikan dia hantar ke laluan controller bayaran yang tepat --}}
            <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 

                <h3><span class="material-icons">person</span> Section 1: Member Profile</h3>
                <div class="info-box">
                    <span style="display: block; font-size: 0.8em; color: #666; text-transform: uppercase;">Registering as:</span>
                    <b style="font-size: 1.1em;">{{ Auth::user()->name }}</b><br>
                    <span style="font-family: monospace; color: #444;">IC: {{ Auth::user()->icNo }}</span>
                </div>

                <div class="form-group">
                    <label for="member_type">Select Membership Package:</label>
                    <select id="member_type" name="member_type" required>
                        <option value="" disabled selected>-- Pilih Jenis Keahlian --</option>
                        <option value="Tahunan" {{ old('member_type') == 'Tahunan' ? 'selected' : '' }}>Ahli Tahunan (RM 20.00)</option>
                        <option value="Sepanjang Hayat" {{ old('member_type') == 'Sepanjang Hayat' ? 'selected' : '' }}>Ahli Sepanjang Hayat (RM 200.00)</option>
                    </select>
                </div>

                <h3><span class="material-icons" style="color: #cc0000;">account_balance_wallet</span> Section 2: Payment Details</h3>
                
                <div class="form-group">
                    <label for="payment_method">Preferred Payment Method:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="" disabled selected>-- Pilih Kaedah Bayaran --</option>
                        <option value="ToyyibPay" {{ old('payment_method') == 'ToyyibPay' ? 'selected' : '' }}>Online Banking (ToyyibPay)</option>
                        <option value="Manual Transfer" {{ old('payment_method') == 'Manual Transfer' ? 'selected' : '' }}>Manual Bank Transfer</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash to Instructor / Staff</option>
                    </select>
                    <p style="font-size: 0.8em; color: #888; margin-top: 10px; font-style: italic;">
                        * The system will automatically record today's date for this transaction.
                    </p>
                </div>

                {{-- RUANGAN UPLOAD RESIT (Disembunyikan pada awalnya) --}}
                <div class="form-group" id="receipt-section" style="display: none; background: #fafafa; padding: 20px; border-radius: 8px; border: 2px dashed #ddd;">
                    <label style="color: #cc0000; display: flex; align-items: center; gap: 5px;">
                        <span class="material-icons" style="font-size: 18px;">receipt</span> Upload Payment Receipt
                    </label>
                    <p style="font-size: 0.85em; color: #555; margin-bottom: 10px;">Please transfer the exact amount to <b>MAYBANK 5600 1234 5678 (PSSGM Melaka)</b> and upload the receipt here.</p>
                    
                    <input type="file" id="receipt_file" name="receipt_file" accept=".pdf, image/png, image/jpeg, image/jpg" style="background: white;">
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#payment_method').on('change', function() {
                if ($(this).val() === 'Manual Transfer') {
                    $('#receipt-section').slideDown();
                    $('#receipt_file').prop('required', true); // Jadikan ruangan ni wajib kalau pilih Manual
                } else {
                    $('#receipt-section').slideUp();
                    $('#receipt_file').prop('required', false); // Tak wajib kalau pilih kaedah lain
                }
            });
            
            // Trigger incase page loading semula (kerana error) dan Manual Transfer dah dipilih
            $('#payment_method').trigger('change');
        });
    </script>
</body>
</html>