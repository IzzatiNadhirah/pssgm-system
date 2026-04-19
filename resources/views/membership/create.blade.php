<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership & Payment</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <h2>Membership Registration & Payment</h2>

        @if (session('success'))
            <script>alert("{{ session('success') }}");</script>
            <p style="color: green;"><b>{{ session('success') }}</b></p>
        @endif

        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <b>Validation Errors:</b>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('memberships.store') }}" method="POST">
            @csrf 

            <h3>Section 1: Membership Details</h3>
            
            <p><b>Registering as:</b> {{ Auth::user()->name }} ({{ Auth::user()->icNo }})</p><br>

            <label for="member_type">Membership Type:</label><br>
            <select id="member_type" name="member_type" required style="width: 100%; padding: 8px; margin-top: 5px;">
                <option value="" disabled selected>-- Select Type --</option>
                <option value="Tahunan">Ahli Tahunan (RM 20.00)</option>
                <option value="Sepanjang Hayat">Ahli Sepanjang Hayat (RM 200.00)</option>
            </select><br><br>

            <hr>

            <h3>Section 2: Payment Information</h3>
            <p style="font-size: 0.9em; color: #555;"><i>* The system will automatically calculate your total and record today's date.</i></p>

            <label for="payment_method">Payment Method:</label><br>
            <select id="payment_method" name="payment_method" required style="width: 100%; padding: 8px; margin-top: 5px;">
                <option value="" disabled selected>-- Select Payment Method --</option>
                <option value="ToyyibPay">Online Banking (ToyyibPay)</option>
                <option value="Cash">Cash to Instructor</option>
                <option value="Manual Transfer">Manual Bank Transfer</option>
            </select><br><br>

            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                Complete Registration & Pay
            </button>
        </form>
    </div>
</body>
</html>