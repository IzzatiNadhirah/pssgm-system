<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Promotions - Staff PSSGM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        
        .container { 
            width: 100%; max-width: 1400px; background: white; padding: 35px; box-sizing: border-box;
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header-area { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-title { display: flex; align-items: center; gap: 15px; }
        .header-title .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; font-size: 1.4em; }

        /* --- ALERTS --- */
        .alert-box { width: 100%; max-width: 1400px; box-sizing: border-box; }
        .alert { padding: 15px; border-radius: 8px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }

        /* --- ACTION BUTTONS --- */
        .action-cell { display: flex; gap: 10px; justify-content: flex-start; }
        .btn-action { 
            border: none; padding: 8px 15px; border-radius: 6px; color: white; 
            font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; 
            font-size: 0.9em; transition: 0.2s; text-transform: uppercase;
        }
        .btn-approve { background-color: #28a745; }
        .btn-approve:hover { background-color: #218838; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(40,167,69,0.3); }
        .btn-reject { background-color: #dc3545; }
        .btn-reject:hover { background-color: #c82333; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(220,53,69,0.3); }

        /* --- TABLE STYLES ASAS --- */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-rejected { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .empty-state { text-align: center; padding: 50px; color: #888; background: #fafafa; border-radius: 8px; border: 2px dashed #ddd; margin-top: 15px; }
        .empty-state .material-icons { font-size: 56px; color: #ccc; margin-bottom: 10px; }

        /* --- CSS DATATABLES TEMA PSSGM --- */
        .dataTables_wrapper { font-family: inherit !important; font-size: 0.95em; color: #111; margin-top: 10px; }
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
            background-color: #111 !important; color: #ffcc00 !important; 
            font-weight: bold !important; text-transform: uppercase !important; 
            padding: 12px !important; border-bottom: none !important; 
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
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #ffcc00 !important; color: #111 !important; border: 1px solid #e6b800 !important; font-weight: bold; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: 1px solid #111 !important; }
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

        <div class="container">
            <div class="header-area">
                <div class="header-title">
                    <span class="material-icons">fact_check</span>
                    <h2>Manage Bengkung Approvals</h2>
                </div>
            </div>

            @if($requests->isEmpty())
                <div class="empty-state">
                    <span class="material-icons">done_all</span>
                    <h3>All Caught Up!</h3>
                    <p>There are no pending bengkung promotion requests from your instructors right now.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table id="staffPromotionsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date / Instructor</th>
                                <th>Student Details</th>
                                <th>Promotion Request</th>
                                <th>Remarks</th>
                                <th>Status / Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td data-sort="{{ $request->created_at }}">
                                    <span>{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y, h:i A') }}</span><br>
                                    <b>{{ $request->instructor->name ?? 'Unknown Instructor' }}</b>
                                </td>
                                <td>
                                    <b>{{ $request->user->name ?? 'Unknown Student' }}</b><br>
                                    <span>Current: {{ $request->current_bengkung }}</span>
                                </td>
                                <td>
                                    <b>{{ $request->requested_bengkung }}</b><br>
                                    
                                    @if($request->total_mark)
                                        <span style="font-size: 1em; color: #111;">
                                            Score: <b style="color: {{ $request->total_mark >= 60 ? '#28a745' : '#dc3545' }};">{{ $request->total_mark }}%</b>
                                        </span>
                                    @else
                                        <span style="font-size: 1em; color: #111;">Score: <b>-</b></span>
                                    @endif
                                    
                                </td>
                                <td style="max-width: 200px; font-style: italic;">
                                    {{ $request->remarks ?: '-' }}
                                </td>
                                <td>
                                    @if($request->status == 'Pending')
                                        <div class="action-cell">
                                            <form action="{{ route('staff.promotions.approve', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to APPROVE this promotion? The student\'s bengkung will be officially updated.');">
                                                @csrf
                                                <button type="submit" class="btn-action btn-approve" title="Approve Promotion">
                                                    <span class="material-icons" style="font-size: 20px;">check_circle</span> Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('staff.promotions.reject', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to REJECT this request?');">
                                                @csrf
                                                <button type="submit" class="btn-action btn-reject" title="Reject Promotion">
                                                    <span class="material-icons" style="font-size: 20px;">cancel</span> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- KITA EJAS SINI: Tunjuk nama staf yang laksanakan tindakan ni --}}
                                        @if($request->status == 'Approved')
                                            <span class="status-badge status-approved">Approved</span>
                                        @else
                                            <span class="status-badge status-rejected">Rejected</span>
                                        @endif
                                        
                                        @if($request->staff)
                                            <div style="margin-top: 8px; font-size: 0.85em; color: #555;">
                                                <b>By:</b> {{ $request->staff->name }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    <!-- DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#staffPromotionsTable').DataTable({
                "order": [[0, "desc"]], 
                "pageLength": 10,        
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Filter Requests:"
                }
            });
        });
    </script>
</body>
</html>