<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        
        /* --- CONTENT AREA --- */
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 900px; width: 100%; background: white; padding: 40px; border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }

        .header { margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        h2 { margin: 0; color: #111; text-transform: uppercase; font-size: 1.5em; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #111; color: #ffcc00; text-transform: uppercase; font-size: 0.85em; }
        
        /* --- KITA EJAS SINI: Tambah pelbagai warna status --- */
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .status-paid, .status-approved { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        
        .empty-state { text-align: center; padding: 40px; color: #888; }
        .back-nav { margin-top: 30px; }
        .back-nav a { color: #cc0000; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px; transition: 0.2s; }
        .back-nav a:hover { transform: translateX(-5px); color: #111; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            <div class="header">
                <h2><span class="material-icons" style="vertical-align: bottom; color: #cc0000;">history</span> Payment History</h2>
            </div>

            @if($payments->isEmpty())
                <div class="empty-state">
                    <span class="material-icons" style="font-size: 48px; color: #ddd;">payments</span>
                    <p>No payment records found.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            
                            {{-- KITA EJAS SINI: Buang tag <code>, biarkan teks biasa je --}}
                            <td>{{ $payment->payment_code }}</td>
                            
                            <td><b>RM {{ number_format($payment->amount, 2) }}</b></td>
                            <td>
                                @if($payment->payment_status == 'Pending Verification' || $payment->payment_status == 'Pending')
                                    <span class="status-badge status-pending">Pending</span>
                                @elseif($payment->payment_status == 'Approved' || $payment->payment_status == 'Paid')
                                    <span class="status-badge status-approved">Approved</span>
                                @elseif($payment->payment_status == 'Rejected')
                                    <span class="status-badge status-rejected">Rejected</span>
                                @else
                                    <span class="status-badge" style="background: #eee; color: #333;">{{ $payment->payment_status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="back-nav">
                <a href="{{ route('dashboard') }}">
                    <span class="material-icons">arrow_back</span> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

</body>
</html>