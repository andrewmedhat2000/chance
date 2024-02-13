<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $guarded = [];
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'department_teacher');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_department');
    }
}
