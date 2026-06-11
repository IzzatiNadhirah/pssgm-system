<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Staff PSSGM</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .container { width: 100%; max-width: 1400px; background: white; padding: 35px; box-sizing: border-box; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; }
        
        .header-area { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-title { display: flex; align-items: center; gap: 15px; }
        .header-title .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; font-size: 1.4em; }

        .alert-box { width: 100%; max-width: 1400px; box-sizing: border-box; }
        .alert { padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 10px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }

        .action-cell { display: flex; gap: 10px; justify-content: flex-start; flex-wrap: wrap;}
        .btn-action { border: none; padding: 8px 15px; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 0.9em; transition: 0.2s; text-transform: uppercase; text-decoration: none; }
        .btn-approve { background-color: #28a745; }
        .btn-approve:hover { background-color: #218838; transform: translateY(-2px); }
        .btn-reject { background-color: #dc3545; }
        .btn-reject:hover { background-color: #c82333; transform: translateY(-2px); }
        .btn-view { background-color: #17a2b8; }
        .btn-view:hover { background-color: #138496; transform: translateY(-2px); }

        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-rejected { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .empty-state { text-align: center; padding: 50px; color: #888; background: #fafafa; border-radius: 8px; border: 2px dashed #ddd; margin-top: 15px; }
        .empty-state .material-icons { font-size: 56px; color: #ccc; margin-bottom: 10px; }

        .dataTables_wrapper { font-family: inherit !important; font-size: 0.95em; color: #111; margin-top: 10px; }
        .dataTables_wrapper label { font-weight: bold !important; text-transform: uppercase !important; font-size: 0.85em; color: #111 !important; }
        .dataTables_wrapper select, .dataTables_wrapper input { padding: 8px 12px !important; border: 2px solid #ddd !important; border-radius: 8px !important; color: #111 !important; }
        .dataTables_wrapper select:focus, .dataTables_wrapper input:focus { border-color: #ffcc00 !important; outline: none; }
        
        table.dataTable thead th { background-color: #111 !important; color: #ffcc00 !important; text-transform: uppercase !important; padding: 12px !important; }
        table.dataTable tbody tr { background-color: #fff !important; transition: 0.2s; }
        table.dataTable tbody tr:hover { background-color: #f9f9f9 !important; }
        table.dataTable tbody td { padding: 15px 12px !important; border-bottom: 1px solid #eee !important; vertical-align: middle; color: #111 !important; font-size: 1em !important; font-family: inherit !important; }
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
                    <span class="material-icons">account_balance_wallet</span>
                    <h2>Manage Payment Verifications</h2>
                </div>
            </div>

            @if($payments->isEmpty())
                <div class="empty-state">
                    <span class="material-icons">task_alt</span>
                    <h3>All Caught Up!</h3>
                    <p>There are no pending manual payment receipts to verify.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table id="staffPaymentsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date / Ref Code</th>
                                <th>Student Details</th>
                                <th>Amount & Method</th>
                                <th>Receipt</th>
                                <th>Status / Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td data-sort="{{ $payment->created_at }}">
                                    <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</span><br>
                                    <b style="font-family: monospace; color: #cc0000; font-size: 1.1em;">{{ $payment->payment_code }}</b>
                                </td>
                                <td>
                                    <b>{{ $payment->user->name ?? 'Unknown Student' }}</b><br>
                                    <span style="font-size: 0.85em; color: #666;">ID: {{ $payment->member_ID }}</span>
                                </td>
                                <td>
                                    <b style="font-size: 1.1em;">RM {{ number_format($payment->amount, 2) }}</b><br>
                                    <span style="font-size: 0.85em; color: #555;"><span class="material-icons" style="font-size: 12px; vertical-align: middle;">account_balance</span> Manual Transfer</span>
                                </td>
                                <td>
                                    @if($payment->receipt_path)
                                        <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank" class="btn-action btn-view" title="Open Receipt in New Tab">
                                            <span class="material-icons">visibility</span> View Receipt
                                        </a>
                                    @else
                                        <span style="color: #888; font-style: italic;">No Receipt Uploaded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->payment_status == 'Pending Verification' || $payment->payment_status == 'Pending')
                                        <div class="action-cell">
                                            <form action="{{ route('staff.payments.approve', $payment->payment_ID ?? $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to APPROVE this payment?');">
                                                @csrf
                                                <button type="submit" class="btn-action btn-approve" title="Verify Payment">
                                                    <span class="material-icons">check_circle</span> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('staff.payments.reject', $payment->payment_ID ?? $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to REJECT this receipt?');">
                                                @csrf
                                                <button type="submit" class="btn-action btn-reject" title="Reject Payment">
                                                    <span class="material-icons">cancel</span> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        @if($payment->payment_status == 'Approved')
                                            <span class="status-badge status-approved">Approved</span>
                                        @else
                                            <span class="status-badge status-rejected">Rejected</span>
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#staffPaymentsTable').DataTable({
                "order": [[0, "desc"]], 
                "pageLength": 10,        
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Filter Records:"
                }
            });
        });
    </script>
</body>
</html>