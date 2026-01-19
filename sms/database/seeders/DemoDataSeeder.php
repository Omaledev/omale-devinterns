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

        // Creating Academic Structure (Classes & Sections)
        // i need these so we can assign students to them later
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
        ]);
        $bursar->assignRole('Bursar');


        // Create Teachers 
        $this->command->info('Hiring Teachers...');
        User::factory(5)->create(['school_id' => $school->id])
            ->each(function ($user) use ($school) {
                $user->assignRole('Teacher');
                
                // Creating the profile linked to the user and school
                TeacherProfile::factory()->create([
                    'user_id' => $user->id,
                    'school_id' => $school->id
                ]);
            });


        // Creating Students 
        $this->command->info('Enrolling Students...');
        User::factory(20)->create(['school_id' => $school->id])
            ->each(function ($user) use ($school, $sections) {
                $user->assignRole('Student');

                // Assigning random class and section
                $randomSection = $sections->random();
                
                StudentProfile::factory()->create([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    'class_level_id' => $randomSection->class_level_id,
                    'section_id' => $randomSection->id,
                ]);
            });


        // Create Parents 
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