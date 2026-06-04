<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students - PSSGM Melaka</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    {{-- 1. ADD DATATABLES CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    {{-- ADD DATATABLES BUTTONS CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #111; margin: 0; min-height: 100vh; }
        .content-area { padding: 40px 20px; display: flex; justify-content: center; }
        
        .container { 
            max-width: 1000px; width: 100%; background: white; padding: 35px; 
            border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            border-top: 8px solid #cc0000; border-bottom: 8px solid #ffcc00; 
        }
        
        .header-area { display: flex; align-items: center; gap: 15px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .header-area .material-icons { font-size: 36px; color: #cc0000; }
        h2 { margin: 0; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        
        .course-section { margin-bottom: 40px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; padding: 10px; }
        .course-header { background: #111; color: #ffcc00; padding: 15px 20px; font-size: 1.1em; font-weight: bold; display: flex; justify-content: space-between; align-items: center; border-radius: 6px; margin-bottom: 15px; border-left: 4px solid #cc0000; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 20px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95em; }
        th { background-color: #111; color: #ffcc00; font-weight: bold; text-transform: uppercase; font-size: 0.85em; cursor: pointer; }
        tr:hover { background-color: #fffdf5; }

        .empty-state { text-align: center; padding: 30px; color: #888; font-style: italic; }
        
        .footer-nav { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: left; }
        .back-link { color: #cc0000; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .back-link:hover { transform: translateX(-5px); color: #111; }

        /* --- CUSTOM DATATABLES CSS --- */
        .dataTables_wrapper .dataTables_filter input { border: 2px solid #eee; border-radius: 6px; padding: 5px 10px; outline: none; background: white; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #cc0000; }
        .dataTables_wrapper .dataTables_length select { border: 2px solid #eee; border-radius: 6px; padding: 5px; background: white; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ffcc00 !important; color: #111 !important; border: none; font-weight: bold; border-radius: 6px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #111 !important; color: #ffcc00 !important; border: none; border-radius: 6px; }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 15px; }
        .dataTables_wrapper .dataTables_length { margin-bottom: 15px; }
        .dataTables_wrapper .dataTables_info { margin-top: 15px; padding-top: 10px; font-size: 0.9em; color: #666; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 15px; padding-top: 10px; }

        /* --- CUSTOM PRINT BUTTON CSS --- */
        .dt-buttons { margin-bottom: 15px; }
        .dt-button.btn-print { 
            background: #cc0000 !important; 
            color: white !important; 
            border: none !important; 
            border-radius: 6px !important; 
            padding: 8px 16px !important; 
            font-weight: bold !important; 
            font-size: 0.9em !important;
            transition: 0.2s !important;
        }
        .dt-button.btn-print:hover { background: #aa0000 !important; transform: translateY(-2px); }

        /* --- PRINT MEDIA QUERY (Hides UI elements & fixes colors when printing) --- */
        @media print {
            body { background-color: white !important; color: black !important; }
            .content-area { padding: 0 !important; }
            .container { box-shadow: none !important; border: none !important; max-width: 100% !important; padding: 0 !important; }
            .footer-nav, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate, .dt-buttons { display: none !important; }
            .course-header { border-left: none !important; background-color: #f1f1f1 !important; color: #111 !important; }
            .course-header span { background-color: transparent !important; color: #111 !important; }
            
            /* FORCE TABLE HEADERS TO BE BLACK & WHITE FOR PRINTING */
            th { background-color: #f1f1f1 !important; color: black !important; border-bottom: 2px solid black !important; }
            td { color: black !important; border-bottom: 1px solid #ccc !important; }
            b { color: black !important; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="content-area">
        <div class="container">
            
            <div class="header-area">
                <span class="material-icons">groups</span>
                <h2>Enrolled Students Directory</h2>
            </div>

            @if($courses->isEmpty())
                <div class="empty-state" style="border: 2px dashed #ddd; border-radius: 8px; padding: 50px;">
                    <span class="material-icons" style="font-size: 48px; color: #ccc;">assignment_late</span>
                    <p>You have not been assigned to any courses yet.</p>
                </div>
            @else
                @foreach($courses as $course)
                    <div class="course-section">
                        <div class="course-header">
                            <div>
                                <span class="material-icons" style="vertical-align: bottom; font-size: 20px; margin-right: 5px;">menu_book</span>
                                {{ $course->course_type }}
                            </div>
                            <span style="font-size: 0.85em; background: #333; padding: 4px 10px; border-radius: 12px; color: white;">
                                Total Enrolled: {{ $course->enrollments ? $course->enrollments->count() : 0 }} 
                            </span>
                        </div>

                        @if(!$course->enrollments || $course->enrollments->isEmpty())
                            <div class="empty-state">No students have enrolled in this class yet.</div>
                        @else
                            <div style="overflow-x: auto; background: white; padding: 15px; border-radius: 8px;">
                                <table class="dataTable" data-course="{{ $course->course_type }}">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th>Student Name</th>
                                            <th>Bengkung Level</th>
                                            <th>Enrollment Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->enrollments as $index => $enrollment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><b>{{ $enrollment->user->name ?? 'Unknown Student' }}</b></td>
                                            <td>{{ $enrollment->user->bengkung_level ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($enrollment->created_at ?? $enrollment->enroll_date)->format('d M Y') }}</td>
                                            <td><b style="color: #28a745;">Active</b></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            <div class="footer-nav">
                <a href="{{ route('instructor.dashboard') }}" class="back-link">
                    <span class="material-icons">arrow_back</span> Back to Instructor Dashboard
                </a>
            </div>

        </div>
    </div>

    {{-- JQUERY & DATATABLES JS SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    {{-- ADDED DATATABLES BUTTONS SCRIPTS FOR PRINT FUNCTION --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    {{-- --- JAVASCRIPT TO INITIALIZE DATATABLES --- --}}
    <script>
        $(document).ready(function() {
            // Loop through each table to initialize them independently 
            // so the print title reflects the specific course
            $('.dataTable').each(function() {
                var courseName = $(this).data('course'); // Grabs the course name from the data attribute
                
                $(this).DataTable({
                    "pageLength": 10,
                    "lengthMenu": [5, 10, 25, 50, 100],
                    "language": {
                        "search": "Search Student:",
                        "lengthMenu": "Show _MENU_ entries"
                    },
                    "order": [], // Disable auto-sort on initial load
                    "dom": '<"dt-buttons"B>lfrtip', // Defines where the buttons appear
                    "buttons": [
                        {
                            extend: 'print',
                            text: '<span class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">print</span> Print List',
                            className: 'btn-print',
                            title: 'Enrolled Students List - ' + courseName, // Adds course name to the printed document header
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4] // Ensures all columns are printed
                            }
                        }
                    ]
                });
            });
        });
    </script>
</body>
</html>