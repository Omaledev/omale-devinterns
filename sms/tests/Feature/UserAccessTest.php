<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\School; 

class UserAccessTest extends TestCase
{
    use RefreshDatabase; 

    public function setUp(): void
    {
        parent::setUp();
        // Run ning seeders so Roles (Student, Teacher,Parent, Bursar SchoolAdmin) exist
        $this->seed(); 
    }

    /**
     * Test 1: Can the login page load?
     */
    public function test_login_page_loads_successfully()
    {
        $response = $this->get('/sign-in');
        $response->assertStatus(200);
    }

    /**
     * Test 2: Can a Student Login and see their dashboard?
     */
    public function test_student_can_login_and_access_dashboard()
    {
        $school = School::factory()->create();

        $student = User::factory()->create([
            'school_id' => $school->id
        ]);
        $student->assignRole('Student');

        \App\Models\StudentProfile::factory()->create([
            'user_id' => $student->id,
            'school_id' => $school->id,
            'class_level_id' => \App\Models\ClassLevel::factory()->create([
                'school_id' => $school->id,
                'name' => 'JSS 1' 
            ])->id,
        ]);

        $response = $this->actingAs($student)->get('/student/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test 3: Can a Teacher Login?
     */
    public function test_teacher_can_login()
    {
        $school = School::factory()->create();

        $teacher = User::factory()->create([
            'school_id' => $school->id
        ]);
        $teacher->assignRole('Teacher');

        \App\Models\TeacherProfile::factory()->create([
            'user_id' => $teacher->id,
            'school_id' => $school->id
        ]);

        $response = $this->actingAs($teacher)->get('/teacher/dashboard');
        $response->assertStatus(200);
    }
    /**
     * Test 4: Security - Student cannot see Admin Dashboard
     */
    public function test_student_cannot_access_admin_dashboard()
    {
        $school = School::factory()->create();

        $student = User::factory()->create([
            'school_id' => $school->id
        ]);
        $student->assignRole('Student');

        $response = $this->actingAs($student)->get('/admin/dashboard'); 

        // Assert Forbidden (403)
        $response->assertStatus(403); 
    }
}