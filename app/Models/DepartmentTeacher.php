<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTeacher extends Model
{
    use HasFactory;
    protected $table = 'department_teacher';
    protected $fillable = ['department_id', 'teacher_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship with Teacher model
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

}
