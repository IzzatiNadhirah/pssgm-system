<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership & Payment</title>
</head>
<body>
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
        <label for="user_ID">Select User:</label><br>
        <select id="user_ID" name="user_ID" required>
            <option value="" disabled selected>-- Select User --</option>
            @foreach($users as $user)
                <option value="{{ $user->id ?? $user->user_ID }}">{{ $user->name }} ({{ $user->icNo }})</option>
            @endforeach
        </select><br><br>

        <label for="member_type">Membership Type:</label><br>
        <select id="member_type" name="member_type" required>
            <option value="" disabled selected>-- Select Type --</option>
            <option value="Tahunan">Tahunan (Annual)</option>
            <option value="Sepanjang Hayat">Sepanjang Hayat (Life)</option>
        </select><br><br>

        <hr>

        <h3>Section 2: Payment Information</h3>
        <label for="amount">Total Amount (RM):</label><br>
        <input type="number" step="0.01" id="amount" name="amount" placeholder="50.00" required><br><br>

        <label for="payment_method">Payment Method:</label><br>
        <select id="payment_method" name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="Online Transfer">Online Transfer</option>
            <option value="Card">Credit/Debit Card</option>
        </select><br><br>

        <label for="payment_date">Date of Payment:</label><br>
        <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required><br><br>

        <button type="submit" style="padding: 10px 20px; cursor: pointer;">Complete Registration & Pay</button>
    </form>
</body>
</html>