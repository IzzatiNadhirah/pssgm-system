<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Instructor</title>
</head>
<body>
    <h2>Register New Instructor</h2>

    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    <form action="{{ route('instructors.store') }}" method="POST">
        @csrf 

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <small style="color: gray;">* Password must be at least 8 characters long.</small><br><br>

        <label for="tel_number">Telephone Number:</label><br>
        <input type="text" id="tel_number" name="tel_number" required><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" rows="4" cols="30" required></textarea><br><br>

        <button type="submit">Register Instructor</button>
    </form>

</body>
</html>