<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Gelanggang Directory - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1200px; width: 100%; background: white; padding: 35px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }
        
        .header-area { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-text h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        .header-text p { margin: 5px 0 0 0; color: #666; font-size: 0.9em; }
        
        /* --- STYLES UNTUK KOTAK FILTER (DROPDOWN) --- */
        .filter-area { margin-bottom: 20px; display: flex; align-items: center; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd; }
        .filter-area label { font-weight: bold; color: #333; display: flex; align-items: center; gap: 5px; margin: 0; }
        .filter-control { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 1em; min-width: 250px; outline: none; cursor: pointer; }
        .filter-control:focus { border-color: #cc0000; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; transition: 0.2s; }
        
        .btn { padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s; }
        .btn-add { background-color: #080a71; color: white; }
        .btn-edit { background-color: #ffcc00; color: #111; padding: 6px 12px; }
        .btn-delete { background-color: #333; color: white; padding: 6px 12px; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px;}
        .badge-active { background-color: #28a745; color: white; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold; }

        .empty-state { text-align: center; padding: 50px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <div class="header-text">
                    @php
                        $isSuperAdmin = Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'admin';
                        $cawanganTitle = '';
                        if (!$isSuperAdmin && $activeGelanggangs->isNotEmpty()) {
                            $cawanganTitle = ' - ' . ($activeGelanggangs->first()->cawangan->caw_name ?? '');
                        }
                    @endphp

                    <h2>Active Gelanggang Directory{{ $cawanganTitle }}</h2>
                    <p>This list contains all currently approved and operational training centers.</p>
                </div>
                
                <a href="{{ route('gelanggangs.create') }}" class="btn btn-add">
                    <span class="material-icons">add</span> Register New Gelanggang
                </a>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 18px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            @if($activeGelanggangs->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">stadium</span>
                    <h3>No Active Gelanggang Found</h3>
                    <p>There are no active training centers under your supervision yet.</p>
                </div>
            @else
                
                @if($isSuperAdmin)
                    <div class="filter-area">
                        <label for="cawanganFilter"><span class="material-icons">filter_alt</span> Filter by Branch:</label>
                        <select id="cawanganFilter" class="filter-control">
                            <option value="all">-- View All Branches --</option>
                            
                            @php
                                $uniqueCawangans = $activeGelanggangs->pluck('cawangan.caw_name')->filter()->unique();
                            @endphp

                            @foreach($uniqueCawangans as $cawName)
                                <option value="{{ $cawName }}">{{ $cawName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Gelanggang Name</th>
                                <th>Address</th>
                                
                                @if($isSuperAdmin)
                                    <th>Cawangan</th>
                                @endif
                                
                                <th>Instructor Name</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeGelanggangs as $gelanggang)
                            <tr class="gelanggang-row" data-cawangan="{{ $gelanggang->cawangan->caw_name ?? 'N/A' }}">
                                <td><b style="color: #222; font-size: 1.1em;">{{ $gelanggang->gel_name }}</b></td>
                                
                                <td>{{ $gelanggang->gel_address }}</td>
                                
                                @if($isSuperAdmin)
                                    <td><b>{{ $gelanggang->cawangan->caw_name ?? 'N/A' }}</b></td>
                                @endif
                                
                                <td>{{ $gelanggang->instructor->name ?? 'Unknown Instructor' }}</td>
                                
                                <td><span class="badge-active">Active</span></td>
                                
                                <td style="display: flex; gap: 8px; justify-content: center;">
                                    
                                    <a href="{{ route('gelanggangs.edit', $gelanggang->gel_ID ?? $gelanggang->id) }}" class="btn btn-edit" title="Edit Gelanggang">
                                        <span class="material-icons" style="font-size: 18px;">edit</span>
                                    </a>
                                    
                                    <form action="{{ route('gelanggangs.destroy', $gelanggang->gel_ID ?? $gelanggang->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Gelanggang?');" style="margin: 0;">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" title="Delete Gelanggang">
                                            <span class="material-icons" style="font-size: 18px;">delete</span>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="footer-nav">
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'admin')
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('staff.dashboard') }}" class="back-link">
                        <span class="material-icons">arrow_back</span> Back to Staff Dashboard
                    </a>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.getElementById('cawanganFilter');
            const tableRows = document.querySelectorAll('.gelanggang-row');

            // Kalau takde dropdown (contoh: staf biasa login), abaikan script ni
            if (!filterDropdown) return;

            filterDropdown.addEventListener('change', function() {
                const selectedCawangan = this.value;

                tableRows.forEach(row => {
                    // Ambil nilai dari attribute data-cawangan yang kita set kat <tr> tadi
                    const rowCawangan = row.getAttribute('data-cawangan');

                    // Kalau pilih 'all' atau cawangan sama macam yang dipilih, tunjukkan baris tu
                    if (selectedCawangan === 'all' || rowCawangan === selectedCawangan) {
                        row.style.display = '';
                    } else {
                        // Kalau tak sama, sorokkan baris tu
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

</body>
</html>