<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { width: 80px; height: auto; }
        .school-name { font-size: 24px; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grades-table th, .grades-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .grades-table th { background-color: #f0f0f0; }
        .text-left { text-align: left !important; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        {{-- <img src="{{ public_path('logo.png') }}" class="logo"> --}}
        <div class="school-name">{{ auth()->user()->school->name ?? 'Axia School' }}</div>
        <div>Student Report Card</div>
    </div>

    {{-- Student Info --}}
    <table class="info-table">
        <tr>
            <td><strong>Name:</strong> {{ $student->name }}</td>
            <td><strong>Admission No:</strong> {{ $student->studentProfile->student_id }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong> {{ $student->studentProfile->classLevel->name }}</td>
            <td><strong>Term:</strong> {{ $term->name }} ({{ $session->name }})</td>
        </tr>
    </table>

    {{-- Grades Table --}}
    <table class="grades-table">
        <thead>
            <tr>
                <th class="text-left">Subject</th>
                {{-- Loop through Assessment Types--}}
                @foreach($assessmentTypes as $type)
                <th>{{ $type->name }}</th>
                @endforeach
                <th>Total</th>
                <th>Grade</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $subjectId => $data)
                <tr>
                    <td class="text-left">{{ $data['name'] }}</td>
                    
                    {{-- Loop through Assessment Types for Scores --}}
                    @foreach($assessmentTypes as $type)
                        <td>{{ $data['scores'][$type->name] ?? '-' }}</td>
                    @endforeach

                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['grade'] }}</td>
                    <td>{{ $data['remark'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px;">
        <p><strong>Principal's Comment:</strong> _________________________________________________</p>
        <p><strong>Teacher's Comment:</strong> _________________________________________________</p>
    </div>

    <div class="footer">
        Generated on {{ date('d M Y') }}
    </div>

</body>
</html>