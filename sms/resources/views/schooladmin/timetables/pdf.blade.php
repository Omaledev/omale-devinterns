<!DOCTYPE html>
<html>
<head>
    <title>School Timetable</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>School Master Timetable</h2>
        <p>Generated on: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
            </tr>
        </thead>
        <tbody>
            @php
                $timeSlots = [];
                foreach ($weeklyTimetable as $day => $entries) {
                    $timeSlots = array_merge($timeSlots, $entries->keys()->all());
                }
                $timeSlots = array_unique($timeSlots);
                usort($timeSlots, function($a, $b) {
                    return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
                });
            @endphp

            @foreach($timeSlots as $slot)
                @php [$startTime, $endTime] = explode('-', $slot); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($startTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}</td>
                    
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                        <td>
                            @if(isset($weeklyTimetable[$day][$slot]))
                                @php $entry = $weeklyTimetable[$day][$slot]; @endphp
                                <strong>{{ $entry->subject->name }}</strong><br>
                                {{ $entry->classLevel->name }} ({{ $entry->section->name }})
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>