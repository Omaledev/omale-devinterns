<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use /*HasApiTokens,*/ HasFactory, Notifiable, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone', 
        'password',
        'admission_number',
        'employee_id',
        'school_id',
        'address', 
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    // Teacher relationships-1 user has 1 TeacherProfile
    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    // Student relationships-1 user has 1 StudentProfile
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    // Parent relationships-1 user has 1 ParentProfile
    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }

     // This is for parents to access their children-many students belongsTo many parents
   public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }

     //This is for students to access their parents
    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }

    public function taughtClasses()
    {
        return $this->hasManyThrough(
            ClassroomAssignment::class, // The model needed
            TeacherProfile::class,      // The intermediate model
            'user_id',                  // Foreign key on teacher_profiles table
            'teacher_id',               // Foreign key on classroom_assignments table
            'id',                       // Local key on users table
            'id'                        // Local key on teacher_profiles table
        );
    }
    
    /**
     * Helper to get the first role name as a string
     */
    public function getRoleAttribute()
    {
        // Checking if roles relationship is loaded to avoid N+1, otherwise fetch it
        return $this->roles->first()?->name ?? 'User';
    }

    //  public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

     
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
    }

    public function markedAttendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }

    /**
     * Get all grades recorded for this student.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    /**
     * Get all grades entered by this teacher.
     */
    public function gradesEntered()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    /**
     * Get the invoices for this user (student).
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function messages() 
    {
         return $this->hasMany(Message::class);
    }

    

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'thread_user')
                    ->withPivot('last_read_at') 
                    ->withTimestamps();
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function recordedPayments()
    {
        return $this->hasMany(Payment::class, 'recorded_by');
    }

    public function parentMeetings()
    {
        return $this->hasMany(ParentTeacherMeeting::class, 'parent_id');
    }

    // Get meetings where this user is the STUDENT
    public function studentMeetings()
    {
        return $this->hasMany(ParentTeacherMeeting::class, 'student_id');
    }

}
