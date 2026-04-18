<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Gelanggang</title>
</head>
<body>
    <h2>Register New Gelanggang (Training Center)</h2>

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

    <form action="{{ route('gelanggangs.store') }}" method="POST">
        @csrf 

        <label for="name">Gelanggang Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" rows="4" cols="30" required></textarea><br><br>

        <label for="caw_ID">Assign to Cawangan (Branch):</label><br>
        <select id="caw_ID" name="caw_ID" required>
            <option value="" disabled selected>-- Select Cawangan --</option>
            @foreach($cawangans as $cawangan)
                <option value="{{ $cawangan->caw_ID ?? $cawangan->id }}">{{ $cawangan->caw_name }}</option>
            @endforeach
        </select><br><br>

        <label for="instructor_ID">Assign Instructor:</label><br>
        <select id="instructor_ID" name="instructor_ID" required>
            <option value="" disabled selected>-- Select Instructor --</option>
            @foreach($instructors as $instructor)
                <option value="{{ $instructor->instructor_ID ?? $instructor->id }}">{{ $instructor->name }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Register Gelanggang</button>
    </form>

</body>
</html>