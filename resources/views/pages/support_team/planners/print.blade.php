<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheme of Studies - {{ $planner->my_class->name }} - {{ $planner->subject->name }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fff; color: #333; }
        .print-header { border-bottom: 3px double #007bff; padding-bottom: 15px; margin-bottom: 20px; }
        .planner-table th { background-color: #007bff !important; color: #fff !important; text-align: center; }
        .planner-table td, .planner-table th { border: 1px solid #dee2e6 !important; padding: 10px; vertical-align: top; }
        .signature-box { margin-top: 50px; }
        .signature-line { border-top: 1px solid #333; width: 80%; margin: 40px auto 5px auto; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .container { width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="p-4">

    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="icon-printer mr-2"></i> Print Scheme of Studies
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg ml-2">
            Close
        </button>
    </div>

    <div class="container">
        {{-- Header --}}
        <div class="text-center print-header">
            <h2 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h2>
            <h4 class="mb-2">SCHEME OF STUDIES / LESSON PLANNER</h4>
            <h5 class="text-dark mb-1">
                <strong>{{ $planner->planner_type_name }}</strong> (Academic Session {{ $planner->session }})
            </h5>
            <div class="row mt-3 text-left bg-light p-3 rounded border">
                <div class="col-md-3"><strong>Class:</strong> {{ $planner->my_class->name }}</div>
                <div class="col-md-3"><strong>Subject:</strong> {{ $planner->subject->name }}</div>
                <div class="col-md-3"><strong>Teacher:</strong> {{ $planner->teacher->name ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Status:</strong> {{ strtoupper($planner->status) }}</div>
            </div>
        </div>

        {{-- Table --}}
        <table class="table table-bordered planner-table mt-4">
            <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Month</th>
                <th>Chapters & Main Topics</th>
                <th style="width: 25%;">Teaching Methods & Pedagogy</th>
                <th style="width: 20%;">Assessment Strategy</th>
            </tr>
            </thead>
            <tbody>
            @foreach($planner->items as $key => $item)
                <tr>
                    <td class="text-center font-weight-bold">{{ $key + 1 }}</td>
                    <td class="font-weight-bold">{{ $item->month_name }}</td>
                    <td>
                        <div class="font-weight-semibold">{{ $item->chapters }}</div>
                        @if($item->topics)
                            <div class="small text-muted mt-1"><strong>Details:</strong> {{ $item->topics }}</div>
                        @endif
                    </td>
                    <td>{{ $item->teaching_methods ?: 'Standard Classroom Lecture & Discussion' }}</td>
                    <td>{{ $item->assessment ?: 'Monthly Test / Homework Assignment' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Review Info --}}
        @if($planner->isApproved())
            <div class="alert alert-success mt-4">
                <strong>Approved by Administration:</strong> {{ $planner->reviewer->name ?? 'Principal' }} on {{ $planner->reviewed_at ? $planner->reviewed_at->format('d M Y, h:i A') : 'N/A' }}
            </div>
        @endif

        {{-- Signatures --}}
        <div class="row signature-box text-center">
            <div class="col-4">
                <div class="signature-line"></div>
                <strong>Subject Teacher Signature</strong>
                <div class="small text-muted">{{ $planner->teacher->name ?? '' }}</div>
            </div>
            <div class="col-4">
                <div class="signature-line"></div>
                <strong>HOD / Coordinator Signature</strong>
                <div class="small text-muted">Academic Department</div>
            </div>
            <div class="col-4">
                <div class="signature-line"></div>
                <strong>Principal Stamp & Approval</strong>
                <div class="small text-muted">{{ $planner->reviewer->name ?? 'Principal / Headmaster' }}</div>
            </div>
        </div>
    </div>

</body>
</html>
