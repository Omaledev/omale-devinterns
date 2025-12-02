<?php

namespace App\Imports;

use App\Models\StudentProfile;
use App\Models\User;
use App\Models\ClassLevel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;

class StudentsImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validate class_level_id exists
        $classLevel = ClassLevel::where('id', $row['class_level_id'])
                               ->where('school_id', auth()->user()->school_id)
                               ->first();

        if (!$classLevel) {
            throw new \Exception("Invalid class level ID: {$row['class_level_id']}");
        }

        // First find or create the user
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make('password'),
                'school_id' => auth()->user()->school_id,
            ]
        );
    
        // Assign the student role (only if not already assigned)
        if (!$user->hasRole('Student')) {
            $user->assignRole('Student');
        }
    
        // Check if student profile already exists
        $existingProfile = StudentProfile::where('user_id', $user->id)
                                       ->where('school_id', auth()->user()->school_id)
                                       ->first();

        if ($existingProfile) {
            // Update existing profile instead of creating duplicate
            $existingProfile->update([
                'class_level_id' => $row['class_level_id'],
                'section_id' => $row['section_id'] ?? null,
                'student_id' => $row['student_id'],
            ]);
            return null; // Don't create new model
        }
    
        // Create new student profile
        return new StudentProfile([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'class_level_id' => $row['class_level_id'],
            'section_id' => $row['section_id'] ?? null,
            'student_id' => $row['student_id'],
            'admission_date' => $this->parseDate($row['admission_date'] ?? now()),
            'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
            'gender' => $row['gender'] ?? 'Other',
            'address' => $row['address'] ?? '',
            'contact' => $row['contact'] ?? '',
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            '*.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->where('school_id', auth()->user()->school_id);
                })
            ],
            '*.name' => 'required|string|max:255',
            '*.class_level_id' => [
                'required',
                'integer',
                Rule::exists('class_levels', 'id')->where('school_id', auth()->user()->school_id)
            ],
            '*.student_id' => 'required|string|max:50',
            '*.admission_date' => 'nullable|date',
            '*.date_of_birth' => 'nullable|date',
            '*.gender' => 'nullable|in:Male,Female,Other',
            '*.contact' => 'nullable|string|max:20',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'email.required' => 'Email is required for each student',
            'email.unique' => 'Email :input already exists in this school',
            'class_level_id.exists' => 'The selected class does not exist in your school',
            'name.required' => 'Student name is required',
        ];
    }

    /**
     * Parse date from various Excel formats
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Handle Excel serial date numbers
            if (is_numeric($date)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
            }
            
            // Handle string dates
            return new \DateTime($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}