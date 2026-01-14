<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $user->name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 20px; color: #000; }
        .container { max-width: 800px; margin: auto; border: 2px solid #000; padding: 20px; }
        
        /* Header */
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .address { font-size: 14px; margin-bottom: 5px; }
        .report-title { font-weight: bold; font-size: 18px; text-decoration: underline; margin-top: 10px; }

        /* Student Details Grid */
        .details-box { display: flex; flex-wrap: wrap; margin-bottom: 20px; border: 1px solid #000; padding: 10px; }
        .detail-item { width: 50%; margin-bottom: 5px; }
        .label { font-weight: bold; }

        /* Grades Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; text-transform: uppercase; font-size: 12px; }
        td { font-size: 14px; }
        .subject-col { text-align: left; padding-left: 10px; }

        /* Footer / Remarks */
        .footer-section { margin-top: 30px; }
        .signature-line { border-bottom: 1px dotted #000; width: 200px; display: inline-block; }
        .summary-box { border: 1px solid #000; padding: 10px; width: 40%; float: right; }

        @media print {
            .no-print { display: none; }
            .container { border: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right; max-width: 800px; margin: auto; padding-bottom: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #000; color: #fff; border: none;">🖨️ Print Report Card</button>
    </div>

    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="school-name">{{ $user->school->name ?? 'SCHOOL NAME' }}</div>
            <div class="address">{{ $user->school->address ?? 'School Address' }}</div>
            <div class="report-title">STUDENT REPORT SHEET</div>
        </div>

        {{-- Student Info --}}
        <div class="details-box">
            <div class="detail-item"><span class="label">Name:</span> {{ $user->name }}</div>
            <div class="detail-item"><span class="label">Admission No:</span> {{ $user->admission_number }}</div>
            <div class="detail-item"><span class="label">Class:</span> {{ $studentProfile->classLevel->name ?? 'N/A' }}</div>
            <div class="detail-item"><span class="label">Term:</span> {{ $stats['term_name'] }}</div>
            <div class="detail-item"><span class="label">Session:</span> {{ $stats['session_name'] }}</div>
            <div class="detail-item"><span class="label">Date Issued:</span> {{ now()->format('d M, Y') }}</div>
        </div>

        {{-- Grades Table --}}
        <table>
            <thead>
                <tr>
                    <th class="subject-col">Subject</th>
                    <th>C.A. (40)</th>
                    <th>Exam (60)</th>
                    <th>Total (100)</th>
                    <th>Grade</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grades as $grade)
                    <tr>
                        <td class="subject-col">{{ $grade->subject->name }}</td>
                        <td>{{ $grade->ca_score ?? '-' }}</td>
                        <td>{{ $grade->exam_score ?? '-' }}</td>
                        <td style="font-weight: bold;">{{ $grade->total_score }}</td>
                        <td>{{ $grade->grade }}</td>
                        <td style="font-size: 12px; font-style: italic;">{{ $grade->remark }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer --}}
        <div class="footer-section">
            <div class="summary-box">
                <div><strong>No. of Subjects:</strong> {{ $stats['total_subjects'] }}</div>
                <div><strong>Total Score:</strong> {{ $stats['total_score'] }}</div>
                <div><strong>Average Score:</strong> {{ number_format($stats['average'], 1) }}%</div>
            </div>

            <div style="clear: both; padding-top: 40px;">
                <p><strong>Teacher's Remark:</strong> __________________________________________________</p>
                <p><strong>Principal's Signature:</strong> <span class="signature-line"></span></p>
                <p><strong>School Stamp:</strong></p>
            </div>
        </div>
    </div>

</body>
</html>