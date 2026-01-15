<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .school-name { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grades-table th, .grades-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .grades-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-left { text-align: left !important; }
        
        .summary { margin-top: 20px; text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">UNIVERSITY OF JOS PRIMARY SCHOOL</div>
        <div>Bauchi Road, Jos, Plateau State</div>
        <h3>STUDENT REPORT CARD</h3>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Name:</strong> {{ $child->name }}</td>
            <td><strong>Class:</strong> {{ $child->studentProfile->classLevel->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Admission No:</strong> {{ $child->studentProfile->admission_number ?? '-' }}</td>
            <td><strong>Session:</strong> {{ $grades->first()->academicSession->name ?? 'Current' }}</td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th class="text-left">Subject</th>
                <th>C.A (40)</th>
                <th>Exam (60)</th>
                <th>Total (100)</th>
                <th>Grade</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
            <tr>
                <td class="text-left">{{ $grade->subject->name }}</td>
                <td>{{ $grade->ca_score }}</td>
                <td>{{ $grade->exam_score }}</td>
                <td><strong>{{ $grade->total_score }}</strong></td>
                <td>
                    @php
                        $score = $grade->total_score;
                        if($score >= 70) echo 'A';
                        elseif($score >= 60) echo 'B';
                        elseif($score >= 50) echo 'C';
                        elseif($score >= 45) echo 'D';
                        else echo 'F';
                    @endphp
                </td>
                <td>{{ $grade->remark ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Overall Average:</strong> {{ number_format($average, 2) }}%</p>
        <p><strong>Principal's Signature:</strong> ____________________</p>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>