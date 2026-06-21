<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage System Users - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    {{-- 1. TAMBAH DATATABLES CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #111; 
            margin: 0; 
            min-height: 100vh;
        }

        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1200px; 
            width: 100%;
            background: white; 
            padding: 35px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000;
            border-bottom: 8px solid #ffcc00;
        }
        
        .header-area {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;
            flex-wrap: wrap; gap: 15px;
        }

        .header-text h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        .header-text p { margin: 5px 0 0 0; color: #666; font-size: 0.9em; }

        /* --- BUTANG ACTION BARU --- */
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-add-member { background-color: #28a745; color: white; }
        .btn-add-staff { background-color: #6f42c1; color: white; }
        .btn-add-instructor { background-color: #ff9900; color: #111; }

        /* --- FILTER TABS STYLE --- */
        .filter-tabs {
            display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;
        }
        
        .tab-btn {
            background: none; border: none; padding: 10px 20px; font-size: 1em; font-weight: bold;
            color: #666; cursor: pointer; border-radius: 6px 6px 0 0; transition: 0.3s;
            display: inline-flex; align-items: center; gap: 8px; position: relative;
        }
        
        .tab-btn:hover { background-color: #f9f9f9; color: #111; }
        
        .tab-btn.active { color: #111; }
        .tab-btn.active::after {
            content: ''; position: absolute; bottom: -12px; left: 0; width: 100%; height: 4px;
            background-color: #ffcc00; border-radius: 4px 4px 0 0;
        }

        /* --- SECTION HIDING --- */
        .user-section { display: none; animation: fadeIn 0.4s; }
        .user-section.active-section { display: block; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        tr:hover { background-color: #fffdf5; }
        
        .btn { 
            padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px; 
            font-weight: bold; text-decoration: none; display: inline-flex; 
            align-items: center; gap: 5px; font-size: 0.85em; transition: 0.2s;
        }
        
        .btn-edit { background-color: #ffcc00; color: #111; padding: 6px 12px; }
        .btn-delete { background-color: #333; color: white; padding: 6px 12px; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 4px; font-weight: bold; display: flex; align-items: center; gap: 10px; }

        .empty-state { text-align: center; padding: 40px; color: #888; background: #f9f9f9; border-radius: 8px; border: 2px dashed #ddd; margin-top: 10px; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        
        .back-link { color: #111; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #ffcc00; }

        /* --- CUSTOM DATATABLES CSS --- */
        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #ffcc00; } 
        .dataTables_wrapper .dataTables_length select { border: 2px solid #eee; border-radius: 6px; padding: 5px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ffcc00 !important; color: #111 !important; border: none; font-weight: bold; border-radius: 6px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: none; border-radius: 6px; }

        .dataTables_wrapper .dataTables_filter { margin-bottom: 15px; }
        .dataTables_wrapper .dataTables_length { margin-bottom: 15px; }
        .dataTables_wrapper .dataTables_info { margin-top: 15px; padding-top: 10px; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 15px; padding-top: 10px; }

        /* --- KITA EJAS SINI: CSS UNTUK POP-UP MODAL BENGKUNG --- */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; width: 90%; max-width: 450px; border-radius: 10px; overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.3); }
        .modal-header { background: #111; color: #ffcc00; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #ffcc00; }
        .modal-header h3 { margin: 0; font-size: 1.1em; display: flex; align-items: center; gap: 8px; }
        .close-modal { background: none; border: none; color: white; cursor: pointer; font-size: 20px; }
        .close-modal:hover { color: #ffcc00; }
        .modal-body { padding: 25px 20px; max-height: 400px; overflow-y: auto; }
        .student-name-link { color: #111; text-decoration: none; border-bottom: 1px dashed #111; cursor: pointer; transition: 0.2s; }
        .student-name-link:hover { color: #ffcc00; border-bottom-color: #ffcc00; }
        .history-list { list-style: none; padding: 0; margin: 0; }
        .history-list li { padding: 12px 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start; }
        .history-list li:last-child { border-bottom: none; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <div class="header-text">
                    <h2>System Users</h2>
                    <p>Manage all registered members, instructors, and administrative staff.</p>
                </div>
                
                @if(Auth::guard('staff')->check() && Auth::guard('staff')->user()->role === 'admin')
                    <div class="action-buttons">
                        <a href="{{ route('users.create') }}" class="btn btn-add-member" title="Add New Member">
                            <span class="material-icons">person_add</span> Add Member
                        </a>
                        <a href="{{ route('staffs.create') }}" class="btn btn-add-staff" title="Add New Staff">
                            <span class="material-icons">badge</span> Add Staff
                        </a>
                        <a href="{{ route('instructors.create') }}" class="btn btn-add-instructor" title="Add New Instructor">
                            <span class="material-icons">sports</span> Add Instructor
                        </a>
                    </div>
                @endif
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <span class="material-icons" style="font-size: 18px;">check_circle</span> 
                    {{ session('success') }}
                </div>
            @endif

            {{-- --- FILTER TABS --- --}}
            <div class="filter-tabs">
                <button class="tab-btn active" onclick="showSection('members', this)" data-target="members">
                    <span class="material-icons">sports_martial_arts</span> Members
                </button>
                <button class="tab-btn" onclick="showSection('staff', this)" data-target="staff">
                    <span class="material-icons">admin_panel_settings</span> Management Staff
                </button>
                <button class="tab-btn" onclick="showSection('instructors', this)" data-target="instructors">
                    <span class="material-icons">sports</span> Instructors
                </button>
            </div>

            {{-- --- BAHAGIAN MEMBERS --- --}}
            <div id="members-section" class="user-section active-section">
                @if($members->isEmpty())
                    <div class="empty-state">
                        <span class="material-icons" style="font-size: 48px; color: #ccc;">group_off</span>
                        <h3>No Members Found</h3>
                        <p>No training members are currently registered.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="dataTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Bengkung Level</th>
                                    <th>Membership Type</th>
                                    <th>Registration Date</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                @php
                                    // KITA EJAS SINI: Tarik data history bengkung budak ni secara senyap-senyap
                                    $user_id = $member->user_ID ?? $member->id;
                                    $bengkungHistory = \App\Models\PromotionRequest::with('instructor')
                                                                                   ->where('user_ID', $user_id)
                                                                                   ->where('status', 'Approved')
                                                                                   ->orderBy('created_at', 'desc')
                                                                                   ->get();
                                @endphp
                                <tr>
                                    <td>
                                        {{-- KITA EJAS SINI: Jadikan nama butang pop-up --}}
                                        <a href="javascript:void(0)" 
                                           class="student-name-link" 
                                           onclick="openStudentModal(this)"
                                           data-name="{{ $member->name ?? 'Unknown Student' }}"
                                           data-ic="{{ $member->icNo ?? $member->ic_no ?? 'N/A' }}"
                                           data-history="{{ json_encode($bengkungHistory) }}">
                                           <b>{{ $member->name ?? 'Unknown Student' }}</b>
                                        </a>
                                    </td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->bengkung_level ?? 'N/A' }}</td> 
                                    <td><b>{{ $member->membership->member_type ?? 'None' }}</b></td>
                                    <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d M Y') }}</td>
                                    <td style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('users.edit', $member->user_ID) }}" class="btn btn-edit" title="Edit User">
                                            <span class="material-icons" style="font-size: 18px;">edit</span>
                                        </a>

                                        <form action="{{ route('users.destroy', $member->user_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete" title="Delete User">
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
            </div>

            {{-- --- BAHAGIAN STAFF --- --}}
            <div id="staff-section" class="user-section">
                @if($staffs->isEmpty())
                    <div class="empty-state">
                        <span class="material-icons" style="font-size: 48px; color: #ccc;">admin_panel_settings</span>
                        <h3>No Staff Found</h3>
                        <p>No management staff accounts exist in the system.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="dataTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Registration Date</th>
                                    <th>Role</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffs as $staff)
                                <tr style="{{ $staff->role === 'admin' ? 'background-color: #fdf5f6;' : '' }}">
                                    <td><b>{{ $staff->name }}</b></td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($staff->created_at)->format('d M Y') }}</td>
                                    <td>
                                        @if($staff->role === 'admin')
                                            <span style="background-color: #dc3545; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: bold; color: white;">Admin</span>
                                        @else
                                            <span style="background-color: #6f42c1; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: bold; color: white;">Staff</span>
                                        @endif
                                    </td>
                                    <td style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        
                                        @if($staff->role !== 'admin')
                                            <a href="{{ route('staffs.edit', $staff->staff_ID) }}" class="btn btn-edit" title="Edit Staff">
                                                <span class="material-icons" style="font-size: 18px;">edit</span>
                                            </a>

                                            <form action="{{ route('staffs.destroy', $staff->staff_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff member?');" style="margin: 0; display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" title="Delete Staff">
                                                    <span class="material-icons" style="font-size: 18px;">delete</span>
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: #999; font-style: italic; font-size: 0.85em;">Restricted</span>
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- --- BAHAGIAN INSTRUCTORS --- --}}
            <div id="instructors-section" class="user-section">
                @if($instructors->isEmpty())
                    <div class="empty-state">
                        <span class="material-icons" style="font-size: 48px; color: #ccc;">sports</span>
                        <h3>No Instructors Found</h3>
                        <p>No instructors are currently registered in the system.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="dataTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Registration Date</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($instructors as $instructor)
                                <tr>
                                    <td><b>{{ $instructor->name }}</b></td>
                                    <td>{{ $instructor->email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($instructor->created_at)->format('d M Y') }}</td>
                                    <td style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('instructors.edit', $instructor->instructor_ID ?? $instructor->id) }}" class="btn btn-edit" title="Edit Instructor">
                                            <span class="material-icons" style="font-size: 18px;">edit</span>
                                        </a>

                                        <form action="{{ route('instructors.destroy', $instructor->instructor_ID ?? $instructor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this instructor?');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete" title="Delete Instructor">
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
            </div>

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

    {{-- KITA EJAS SINI: KOTAK POP-UP MODAL --}}
    <div class="modal-overlay" id="studentModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><span class="material-icons">badge</span> Member Details</h3>
                <button class="close-modal" onclick="closeStudentModal()"><span class="material-icons">close</span></button>
            </div>
            <div class="modal-body">
                <h2 id="modal-name" style="margin-top: 0; margin-bottom: 5px; font-size: 1.3em;"></h2>
                <p style="color: #666; font-size: 0.9em; margin-top: 0; margin-bottom: 20px;">
                    <span class="material-icons" style="font-size: 14px; vertical-align: middle;">pin</span> IC: <span id="modal-ic"></span>
                </p>

                <h4 style="border-bottom: 2px solid #eee; padding-bottom: 5px; margin-bottom: 0; color: #111;">
                    <span class="material-icons" style="font-size: 16px; vertical-align: bottom; color: #ffcc00;">military_tech</span> Bengkung History
                </h4>
                <ul class="history-list" id="modal-history-list">
                    </ul>
            </div>
        </div>
    </div>

    {{-- 3. JQUERY & DATATABLES JS SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- --- JAVASCRIPT UNTUK TABS, PAGINATION & MODAL --- --}}
    <script>
        // KITA EJAS SINI: Fungsi JavaScript untuk kawal modal
        function openStudentModal(element) {
            const name = element.getAttribute('data-name');
            const ic = element.getAttribute('data-ic');
            const historyJson = element.getAttribute('data-history');
            
            document.getElementById('modal-name').innerText = name;
            document.getElementById('modal-ic').innerText = ic;

            const historyListEl = document.getElementById('modal-history-list');
            historyListEl.innerHTML = ''; 

            try {
                const historyData = JSON.parse(historyJson);
                
                if(!historyData || historyData.length === 0) {
                    historyListEl.innerHTML = '<li><span style="color:#888; font-style:italic;">No belt promotion history yet.</span></li>';
                } else {
                    historyData.forEach(record => {
                        const li = document.createElement('li');
                        
                        const dateObj = new Date(record.updated_at || record.created_at);
                        const dateStr = dateObj.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                        
                        const instructorName = record.instructor ? record.instructor.name : 'Unknown Instructor';
                        const previousBengkung = record.current_bengkung || 'N/A';

                        li.innerHTML = `
                            <div style="flex: 1;">
                                <b style="font-size: 1.1em; color: #111;">${record.requested_bengkung}</b>
                                <div style="font-size: 0.85em; color: #666; margin-top: 4px; display: flex; flex-direction: column; gap: 2px;">
                                    <span><span class="material-icons" style="font-size:14px; vertical-align:text-bottom; color:#888;">history</span> Prev: <b>${previousBengkung}</b></span>
                                    <span><span class="material-icons" style="font-size:14px; vertical-align:text-bottom; color:#888;">person</span> By: ${instructorName}</span>
                                </div>
                            </div>
                            <div style="text-align: right; color: #666; font-size: 0.85em; font-family: monospace;">
                                <span class="material-icons" style="font-size:14px; vertical-align:text-bottom;">event_available</span><br>
                                ${dateStr}
                            </div>
                        `;
                        historyListEl.appendChild(li);
                    });
                }
            } catch (e) {
                historyListEl.innerHTML = '<li><span style="color:red;">Error loading history.</span></li>';
            }

            document.getElementById('studentModal').classList.add('active');
        }

        function closeStudentModal() {
            document.getElementById('studentModal').classList.remove('active');
        }

        $(document).ready(function() {
            // Setup Pagination (DataTables)
            $('.dataTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "search": "Cari Rekod:",
                    "lengthMenu": "Papar _MENU_ rekod"
                }
            });

            // --- FUNGSI INGAT TAB TERAKHIR (localStorage) ---
            const lastTab = localStorage.getItem('activeUserTab') || 'members';
            
            const lastBtn = document.querySelector(`.tab-btn[data-target="${lastTab}"]`);
            if (lastBtn) {
                showSection(lastTab, lastBtn);
            }
        });

        // Setup Tukar Tab
        function showSection(sectionName, btnElement) {
            localStorage.setItem('activeUserTab', sectionName);

            const sections = document.querySelectorAll('.user-section');
            sections.forEach(sec => sec.classList.remove('active-section'));

            const targetSection = document.getElementById(sectionName + '-section');
            if(targetSection) {
                targetSection.classList.add('active-section');
            }

            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => btn.classList.remove('active'));

            if (btnElement) {
                btnElement.classList.add('active');
            }
        }
    </script>

</body>
</html>