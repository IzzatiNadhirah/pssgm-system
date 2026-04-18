<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
</head>
<body>
    <h2>Register New User</h2>

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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf 

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="icNo">IC Number:</label><br>
        <input type="text" id="icNo" name="icNo" placeholder="900101-01-1234" pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}" title="Format: 000000-00-0000" required><br>
        <small style="color: gray;">* Format must include dashes: 000000-00-0000</small><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <small style="color: gray;">* Password must be at least 8 characters long.</small><br><br>

        <label for="tel_number">Telephone Number:</label><br>
        <input type="text" id="tel_number" name="tel_number" required><br><br>

        <label for="age_category">Age Category:</label><br>
        <select id="age_category" onchange="updateBengkung()">
            <option value="" disabled selected>-- Select Category --</option>
            <option value="kanak">Kanak-Kanak</option>
            <option value="dewasa">Dewasa</option>
        </select><br><br>

        <label for="bengkung_level">Bengkung Level:</label><br>
        <select id="bengkung_level" name="bengkung_level" required>
            <option value="" disabled selected>-- Select Age Category First --</option>
        </select><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" rows="4" cols="30" required></textarea><br><br>

        <button type="submit">Register User</button>
    </form>

    <script>
    function updateBengkung() {
        const category = document.getElementById('age_category').value;
        const bengkungDropdown = document.getElementById('bengkung_level');

        // Clear existing options
        bengkungDropdown.innerHTML = '<option value="" disabled selected>-- Select Bengkung Level --</option>';

        let options = [];

        // Define options based on selection
        if (category === 'kanak') {
            options = ['Hitam Kosong', 'Awan Putih Cula Hijau', 'Awan Putih Cula Merah', 'Awan Putih Cula Kuning', 'Awan Putih Cula Hitam' ]; 
        } else if (category === 'dewasa') {
            options = ['Hitam Kosong', 'Awan Putih', 'Pelangi Hijau', 'Pelangi Merah (I - III)', 'Pelangi Kuning (I - IV)', 'Hitam Pelangi Cula Sakti (I - VI)']; 
        }

        // Populate the second dropdown
        options.forEach(function(level) {
            let newOption = document.createElement('option');
            newOption.value = level;
            newOption.textContent = level;
            bengkungDropdown.appendChild(newOption);
        });
    }
    </script>

</body>
</html>