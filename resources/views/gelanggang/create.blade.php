<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Gelanggang</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">

        <h2>Register New Gelanggang (Training Center)</h2>

        @if (session('success'))
            <script>
                alert("{{ session('success') }}");
            </script>
            <p style="background-color: #d4edda; color: #155724; padding: 10px; border-left: 5px solid #28a745;">
                <b>{{ session('success') }}</b>
            </p>
        @endif

        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; background-color: #f8d7da;">
                <b>Whoops! Something went wrong:</b>
                <ul style="margin-top: 5px; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('gelanggangs.store') }}" method="POST">
            @csrf 

            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 5px;">Gelanggang Name:</label>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="address" style="display: block; font-weight: bold; margin-bottom: 5px;">Address:</label>
                <textarea id="address" name="address" rows="4" required style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="caw_ID" style="display: block; font-weight: bold; margin-bottom: 5px;">Assign to Cawangan (Branch):</label>
                <select id="caw_ID" name="caw_ID" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    <option value="" disabled selected>-- Select Cawangan --</option>
                    @foreach($cawangans as $cawangan)
                        <option value="{{ $cawangan->caw_ID ?? $cawangan->id }}">{{ $cawangan->caw_name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="instructor_ID" style="display: block; font-weight: bold; margin-bottom: 5px;">Assign Instructor:</label>
                <select id="instructor_ID" name="instructor_ID" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    <option value="" disabled selected>-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->instructor_ID ?? $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Register Gelanggang
            </button>
            <a href="{{ route('staff.dashboard') }}" style="margin-left: 15px; color: #007bff; text-decoration: none;">Back to Dashboard</a>
        </form>

    </div>
</body>
</html>