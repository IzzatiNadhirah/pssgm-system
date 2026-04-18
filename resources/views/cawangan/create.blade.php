<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Cawangan</title>
</head>
<body>
    <h2>Register New Cawangan (Branch)</h2>

    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <b>Whoops! Something went wrong:</b>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cawangans.store') }}" method="POST">
        @csrf 

        <label for="name">Cawangan Name:</label><br>
        <input type="text" id="name" name="name" placeholder="e.g., Cawangan Melaka" required><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" rows="4" cols="30" required></textarea><br><br>

        <label for="staff_ID">Assign Staff (Person In Charge):</label><br>
        <select id="staff_ID" name="staff_ID" required>
            <option value="" disabled selected>-- Select Staff --</option>
            @foreach($staffs as $staff)
                <option value="{{ $staff->staff_ID ?? $staff->id }}">{{ $staff->name }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Register Cawangan</button>
    </form>

</body>
</html>