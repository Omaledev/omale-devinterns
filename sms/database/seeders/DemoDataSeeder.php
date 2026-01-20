<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\ClassLevel;
use App\Models\Section;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use App\Models\ParentProfile;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating Axia Demo Academy...');

        // Creating the School
        $school = School::factory()->create([
            'name' => 'Axia Demo Academy',
            'email' => 'info@axiademo.com'
        ]);

        // Defining Prefix and Year for consistent IDs
        $prefix = 'AXI'; 
        $year = date('Y');

        // Creating Academic Structure
        $classLevels = ClassLevel::factory(3)->sequence(
            ['name' => 'JSS 1'],
            ['name' => 'JSS 2'],
            ['name' => 'SSS 1']
        )->create(['school_id' => $school->id]);

        foreach ($classLevels as $class) {
            Section::factory(2)->sequence(
                ['name' => 'A'],
                ['name' => 'B']
            )->create([
                'school_id' => $school->id,
                'class_level_id' => $class->id
            ]);
        }
        
        $sections = Section::where('school_id', $school->id)->get();

        // Creating School Admin 
        $admin = User::factory()->create([
            'name' => 'Demo Principal',
            'email' => 'principal@demo.com',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
        ]);
        $admin->assignRole('SchoolAdmin');


        // Creating Bursar 
        $bursar = User::factory()->create([
            'name' => 'Demo Bursar',
            'email' => 'bursar@demo.com',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
            // Manually setting ID
            'employee_id' => "{$prefix}BUR{$year}001", 
        ]);
        $bursar->assignRole('Bursar');


        // Creating Teachers 
        $this->command->info('Hiring Teachers...');
        
        User::factory(5)
            ->sequence(fn ($sequence) => [
                'school_id' => $school->id,
                // Generates AXITCH2026001, AXITCH2026002...
                'employee_id' => $prefix . 'TCH' . $year . str_pad($sequence->index + 1, 3, '0', STR_PAD_LEFT),
            ])
            ->create()
            ->each(function ($user) use ($school) {
                $user->assignRole('Teacher');
                
                TeacherProfile::factory()->create([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    // Ensure profile uses the same ID
                    'employee_id' => $user->employee_id 
                ]);
            });


        // Creating Students 
        $this->command->info('Enrolling Students...');
        
        User::factory(20)
            ->sequence(fn ($sequence) => [
                'school_id' => $school->id,
                // Generates AXI/2026/001, AXI/2026/002...
                'admission_number' => $prefix . '/' . $year . '/' . str_pad($sequence->index + 1, 3, '0', STR_PAD_LEFT),
            ])
            ->create()
            ->each(function ($user) use ($school, $sections) {
                $user->assignRole('Student');

                $randomSection = $sections->random();
                
                StudentProfile::factory()->create([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    'class_level_id' => $randomSection->class_level_id,
                    'section_id' => $randomSection->id,
                    // Sync student_id in profile with admission_number
                    'student_id' => $user->admission_number 
                ]);
            });


        // Creating Parents 
        $this->command->info('Registering Parents...');
        User::factory(10)->create(['school_id' => $school->id])
            ->each(function ($user) use ($school) {
                $user->assignRole('Parent');

                ParentProfile::factory()->create([
                    'user_id' => $user->id,
                    'school_id' => $school->id
                ]);
            });

        $this->command->info('---------------------------------------');
        $this->command->info('Demo Data Created Successfully!');
        $this->command->info('Login Details:');
        $this->command->info('Principal: principal@demo.com / password');
        $this->command->info('Bursar:    bursar@demo.com / password');
        $this->command->info('---------------------------------------');
    }
}