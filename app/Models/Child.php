<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
     protected $table = 'childs';

     protected $guarded = [];

     public function user()
     {
         return $this->belongsTo(User::class);
     }
     public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'child_department');
    }
    public function todayChilds()
    {
        return $this->hasMany(TodayChild::class, 'child_id');
    }

}
