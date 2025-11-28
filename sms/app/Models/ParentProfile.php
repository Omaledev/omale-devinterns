<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class ParentProfile extends Model
{
     use HasFactory;

    protected $fillable = ['user_id', 'school_id', 'occupation', 'company', 'emergency_contact', 'address', 'is_active'];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentProfile::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class ParentProfile extends Model
{
     use HasFactory;

    protected $fillable = ['user_id', 'school_id', 'occupation', 'company', 'emergency_contact', 'address', 'is_active'];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentProfile::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }

}
