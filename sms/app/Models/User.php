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
        return $this->hasMany(ClassroomAssignment::class, 'teacher_id');
    }
    

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }


    public function uploadedBooks()
    {
        return $this->hasMany(Book::class, 'teacher_id');
    }

    //  public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

      public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

}
