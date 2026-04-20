<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cawangan</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">

        <h2>Edit Cawangan (Branch)</h2>

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

        <form action="{{ route('cawangans.update', $cawangan->caw_ID) }}" method="POST">
            @csrf 
            @method('PUT') <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 5px;">Cawangan Name:</label>
                <input type="text" id="name" name="name" value="{{ $cawangan->caw_name }}" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="address" style="display: block; font-weight: bold; margin-bottom: 5px;">Address:</label>
                <textarea id="address" name="address" rows="4" required style="width: 100%; padding: 8px; box-sizing: border-box;">{{ $cawangan->caw_address }}</textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="staff_ID" style="display: block; font-weight: bold; margin-bottom: 5px;">Assign Branch Manager (Staff):</label>
                <select id="staff_ID" name="staff_ID" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    <option value="" disabled>-- Select Staff --</option>
                    @foreach($staffs as $staff)
                        <option value="{{ $staff->staff_ID ?? $staff->id }}" 
                            {{ ($cawangan->staff_ID == ($staff->staff_ID ?? $staff->id)) ? 'selected' : '' }}>
                            {{ $staff->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" style="padding: 10px 20px; background-color: #ffc107; color: #212529; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Update Cawangan
            </button>
            <a href="{{ route('cawangans.index') }}" style="margin-left: 15px; color: #007bff; text-decoration: none;">Cancel</a>
        </form>

    </div>
</body>
</html>