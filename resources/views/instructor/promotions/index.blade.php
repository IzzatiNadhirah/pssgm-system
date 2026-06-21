<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Promotions - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        
        .layout-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
            width: 100%;
            max-width: 1400px;
            align-items: start;
        }

        .container { 
            width: 100%; background: white; padding: 35px; box-sizing: border-box;
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; font-size: 1.4em; }

        /* --- FORM STYLES --- */
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 5px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; font-size: 0.9em; text-transform: uppercase; }
        .form-group select, .form-group textarea, .form-group input { 
            width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; 
            font-size: 1em; font-family: inherit; box-sizing: border-box; transition: 0.3s; 
        }
        .form-group select:focus, .form-group textarea:focus, .form-group input:focus { border-color: #ffcc00; outline: none; background-color: #fffdf5; }

        .btn-submit { 
            background-color: #cc0000; color: white; border: none; padding: 15px; width: 100%; 
            border-radius: 8px; font-weight: bold; font-size: 1.1em; cursor: pointer; 
            text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; 
            justify-content: center; gap: 8px; transition: 0.2s; 
        }
        .btn-submit:hover { background-color: #aa0000; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(204,0,0,0.3); }

        /* --- ALERTS --- */
        .alert-box { width: 100%; max-width: 1400px; box-sizing: border-box; }
        .alert { padding: 15px; border-radius: 8px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }
        .alert-error ul { margin: 5px 0 0 0; padding-left: 20px; }

        /* --- TABLE STYLES ASAS --- */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-rejected { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .empty-state { text-align: center; padding: 40px; color: #888; background: #fafafa; border-radius: 8px; border: 2px dashed #ddd; margin-top: 15px; }
        .empty-state .material-icons { font-size: 48px; color: #ccc; margin-bottom: 10px; }

        /* --- CSS DATATABLES TEMA PSSGM --- */
        .dataTables_wrapper { font-family: inherit !important; font-size: 0.9em; color: #111; margin-top: 10px; }
        .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_length { margin-bottom: 15px; color: #111 !important; }
        
        .dataTables_wrapper label { display: inline-block !important; font-weight: bold !important; text-transform: uppercase !important; font-size: 0.85em; margin: 0 !important; color: #111 !important; }
        .dataTables_wrapper select, .dataTables_wrapper input { 
            width: auto !important; display: inline-block !important; 
            padding: 8px 12px !important; border: 2px solid #ddd !important; 
            border-radius: 8px !important; margin: 0 5px !important; 
            font-family: inherit !important; font-size: 1rem !important; transition: 0.3s;
            color: #111 !important;
        }
        .dataTables_wrapper select:focus, .dataTables_wrapper input:focus { border-color: #ffcc00 !important; outline: none; background-color: #fffdf5; }
        
        table.dataTable { border-collapse: collapse !important; border-bottom: 1px solid #eee !important; }
        table.dataTable thead th, table.dataTable thead td { 
            background-color: #111 !important; 
            color: #ffcc00 !important; 
            font-weight: bold !important; 
            text-transform: uppercase !important; 
            padding: 12px !important;
            border-bottom: none !important; 
        }
        table.dataTable tbody tr { background-color: #fff !important; transition: 0.2s; }
        table.dataTable tbody tr:hover { background-color: #f9f9f9 !important; }
        
        table.dataTable tbody td { 
            padding: 15px 12px !important; 
            border-bottom: 1px solid #eee !important; 
            vertical-align: middle; 
            color: #111 !important; 
            font-size: 1em !important;
            font-family: inherit !important; 
        }
        table.dataTable.no-footer { border-bottom: 1px solid #eee !important; margin-bottom: 15px; }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button { 
            padding: 5px 12px !important; margin-left: 2px !important; border-radius: 4px !important; 
            border: 1px solid transparent !important; color: #111 !important; font-family: inherit;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { 
            background: #ffcc00 !important; color: #111 !important; 
            border: 1px solid #e6b800 !important; font-weight: bold; 
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { 
            background: #111 !important; color: #ffcc00 !important; border: 1px solid #111 !important; 
        }

        @media (max-width: 1024px) {
            .layout-grid { grid-template-columns: 1fr; }
            .container { padding: 25px; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">

        @if(session('success'))
            <div class="alert-box">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box">
                <div class="alert alert-error">
                    <b><span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">error</span> Request Failed:</b>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        <div class="layout-grid">

            {{-- KOTAK KIRI: SEJARAH PERMOHONAN (HISTORY) --}}
            <div class="container">
                <div class="header-area">
                    <span class="material-icons">history</span>
                    <h2>My Request History</h2>
                </div>

                @if($requests->isEmpty())
                    <div class="empty-state">
                        <span class="material-icons">inbox</span>
                        <h3>No Requests Sent</h3>
                        <p>You haven't requested any bengkung promotions for your students yet.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table id="historyTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Level Update</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr>
                                    <td data-sort="{{ $request->created_at }}">
                                        <b>{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}</b><br>
                                        <span style="font-size: 0.85em; color: #555;">{{ \Carbon\Carbon::parse($request->created_at)->format('h:i A') }}</span>
                                    </td>
                                    <td><b>{{ $request->user->name ?? 'Unknown Student' }}</b></td>
                                    <td>
                                        <span>{{ $request->current_bengkung }}</span>
                                        <br><span class="material-icons" style="font-size: 14px; color: #111;">arrow_downward</span><br>
                                        <b>{{ $request->requested_bengkung }}</b>
                                    </td>
                                    <td>
                                        @if($request->total_mark)
                                            <b style="color: {{ $request->total_mark >= 60 ? '#28a745' : '#dc3545' }}; font-size: 1.1em;">
                                                {{ $request->total_mark }}%
                                            </b>
                                        @else
                                            <span style="color: #888;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- KITA EJAS SINI: Tambah nama staf jika dah diluluskan/ditolak --}}
                                        @if($request->status == 'Approved')
                                            <span class="status-badge status-approved">Approved</span>
                                            @if($request->staff)
                                                <div style="font-size: 0.8em; color: #555; margin-top: 5px;">
                                                    <span class="material-icons" style="font-size: 12px; vertical-align: middle;">verified_user</span> 
                                                    {{ $request->staff->name }}
                                                </div>
                                            @endif
                                        @elseif($request->status == 'Rejected')
                                            <span class="status-badge status-rejected">Rejected</span>
                                            @if($request->staff)
                                                <div style="font-size: 0.8em; color: #555; margin-top: 5px;">
                                                    <span class="material-icons" style="font-size: 12px; vertical-align: middle;">person_off</span> 
                                                    {{ $request->staff->name }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- KOTAK KANAN: BORANG PERMOHONAN & PEMARKAHAN --}}
            <div class="container">
                <div class="header-area">
                    <span class="material-icons">military_tech</span>
                    <h2>Request New Promotion</h2>
                </div>

                <form action="{{ route('promotions.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Select Student</label>
                            <select name="user_ID" required>
                                <option value="" disabled selected>-- Choose Student from Directory --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->user_ID ?? $student->id }}">
                                        {{ $student->name }} (Current: {{ $student->bengkung_level ?? 'Tiada Rekod' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Requested Bengkung Level</label>
                            <select name="requested_bengkung" required>
                                <option value="" disabled selected>-- Select New Bengkung Level --</option>
                                <optgroup label="Kanak-Kanak">
                                    <option value="Awan Putih Cula Hijau">Awan Putih Cula Hijau</option>
                                    <option value="Awan Putih Cula Merah">Awan Putih Cula Merah</option>
                                    <option value="Awan Putih Cula Kuning">Awan Putih Cula Kuning</option>
                                    <option value="Awan Putih Cula Hitam">Awan Putih Cula Hitam</option>
                                </optgroup>
                                <optgroup label="Dewasa">
                                    <option value="Awan Putih">Awan Putih</option>
                                    <option value="Pelangi Hijau">Pelangi Hijau</option>
                                    <option value="Pelangi Merah I">Pelangi Merah I</option>
                                    <option value="Pelangi Merah II">Pelangi Merah II</option>
                                    <option value="Pelangi Merah III">Pelangi Merah III</option>
                                    <option value="Pelangi Kuning I">Pelangi Kuning I</option>
                                    <option value="Pelangi Kuning II">Pelangi Kuning II</option>
                                    <option value="Pelangi Kuning III">Pelangi Kuning III</option>
                                    <option value="Pelangi Kuning IV">Pelangi Kuning IV</option>
                                    <option value="Hitam Pelangi Cula Sakti I">Hitam Pelangi Cula Sakti I</option>
                                    <option value="Hitam Pelangi Cula Sakti II">Hitam Pelangi Cula Sakti II</option>
                                    <option value="Hitam Pelangi Cula Sakti III">Hitam Pelangi Cula Sakti III</option>
                                    <option value="Hitam Pelangi Cula Sakti IV">Hitam Pelangi Cula Sakti IV</option>
                                    <option value="Hitam Pelangi Cula Sakti V">Hitam Pelangi Cula Sakti V</option>
                                    <option value="Hitam Pelangi Cula Sakti VI">Hitam Pelangi Cula Sakti VI</option>
                                </optgroup>
                            </select>
                        </div>

                        {{-- KOTAK MARKAH UJIAN --}}
                        <div class="form-group" style="background: #fafafa; padding: 20px; border-radius: 8px; border: 2px dashed #ddd; margin-top: 10px;">
                            <label style="color: #cc0000; display: flex; align-items: center; gap: 5px;">
                                <span class="material-icons" style="font-size: 20px;">grading</span> Grading Results
                            </label>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 15px;">
                                <div>
                                    <label style="font-size: 0.8em; color: #555;">Fizikal & Asas (/40)</label>
                                    <input type="number" name="mark_asas" id="mark_asas" max="40" min="0" placeholder="0" class="mark-input" required>
                                </div>
                                <div>
                                    <label style="font-size: 0.8em; color: #555;">Silibus & Seni (/40)</label>
                                    <input type="number" name="mark_silibus" id="mark_silibus" max="40" min="0" placeholder="0" class="mark-input" required>
                                </div>
                                <div>
                                    <label style="font-size: 0.8em; color: #555;">Disiplin (/20)</label>
                                    <input type="number" name="mark_disiplin" id="mark_disiplin" max="20" min="0" placeholder="0" class="mark-input" required>
                                </div>
                            </div>

                            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; text-align: right; font-size: 1.2em;">
                                Total Score: <b id="total_score" style="color: #111;">0</b><b>%</b> 
                                <span id="status_lulus" style="font-size: 0.8em; margin-left: 10px; padding: 3px 10px; border-radius: 12px; background: #eee; color: #888;">-</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Instructor Remarks (Optional)</label>
                            <textarea name="remarks" rows="3" placeholder="State why this student deserves the promotion..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <span class="material-icons">send</span> Submit Grading & Request
                    </button>
                </form>
            </div>

        </div> 

    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                "order": [[0, "desc"]], 
                "pageLength": 5,        
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "search": "Search History:" 
                }
            });

            // SKRIP KIRA MARKAH AUTOMATIK
            $('.mark-input').on('input', function() {
                let asas = parseInt($('#mark_asas').val()) || 0;
                let silibus = parseInt($('#mark_silibus').val()) || 0;
                let disiplin = parseInt($('#mark_disiplin').val()) || 0;

                // Halang user masukkan lebih dari markah penuh
                if(asas > 40) { $('#mark_asas').val(40); asas = 40; }
                if(silibus > 40) { $('#mark_silibus').val(40); silibus = 40; }
                if(disiplin > 20) { $('#mark_disiplin').val(20); disiplin = 20; }

                let total = asas + silibus + disiplin;
                $('#total_score').text(total);

                // Kemaskini warna dan status lulus (> 60%)
                if(total >= 60) {
                    $('#total_score').css('color', '#28a745'); // Hijau
                    $('#status_lulus').text('PASS').css({'background': '#d4edda', 'color': '#155724'});
                } else {
                    $('#total_score').css('color', '#dc3545'); // Merah
                    $('#status_lulus').text('FAIL').css({'background': '#f8d7da', 'color': '#721c24'});
                }
            });
        });
    </script>
</body>
</html>