<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cawangan</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-add { background-color: #28a745; color: white; margin-bottom: 15px; }
        .btn-edit { background-color: #ffc107; color: #212529; }
        .btn-delete { background-color: #dc3545; color: white; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-left: 5px solid #28a745; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        
        <h2 style="margin-top: 0;">Manage Cawangan (Branches)</h2>
        
        @if (session('success'))
            <div class="alert-success">
                <b>{{ session('success') }}</b>
            </div>
        @endif

        <a href="{{ route('cawangans.create') }}" class="btn btn-add">+ Register New Cawangan</a>

        @if($cawangans->isEmpty())
            <p style="text-align: center; color: #666; padding: 20px; background-color: #f8f9fa; border-radius: 4px;">No Cawangans registered yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Cawangan Code</th>
                        <th>Name</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cawangans as $caw)
                    <tr>
                        <td><b>{{ $caw->caw_code ?? $caw->caw_ID }}</b></td>
                        <td>{{ $caw->caw_name }}</td>
                        <td style="display: flex; gap: 10px; justify-content: center;">
                            
                            <a href="{{ route('cawangans.edit', $caw->caw_ID) }}" class="btn btn-edit">Edit</a>

                            <form action="{{ route('cawangans.destroy', $caw->caw_ID) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this Cawangan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('staff.dashboard') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">&larr; Back to Admin Dashboard</a>
        </div>

    </div>
</body>
</html>