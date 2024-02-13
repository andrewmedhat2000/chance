<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodayChild extends Model
{
    use HasFactory;
    protected $table = 'today_childs';

    protected $guarded = [];

    public $timestamps = true;
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
