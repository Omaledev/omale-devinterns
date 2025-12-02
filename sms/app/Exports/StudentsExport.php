<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
             return StudentProfile::with(['user', 'classLevel', 'section'])->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Email', 
            'Class',
            'Section',
            'Admission Date',
            'Gender',
            'Contact'
        ];
    }

     public function map($studentProfile): array
    {
        return [
            $studentProfile->student_id,
            $studentProfile->user->name, // Access through the user relationship
            $studentProfile->user->email, // Access through  the user relationship
            $studentProfile->classLevel->name ?? 'N/A',
            $studentProfile->section->name ?? 'N/A',
            $studentProfile->admission_date->format('Y-m-d'),
            $studentProfile->gender,
            $studentProfile->contact
        ];
    }
    
}
