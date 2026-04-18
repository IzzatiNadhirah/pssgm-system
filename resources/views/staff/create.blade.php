<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff</title>
</head>
<body>
    <h2>Register New Staff</h2>

    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    <form action="{{ route('staff.store') }}" method="POST">
        @csrf 

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register Staff</button>
    </form>

</body>
</html>