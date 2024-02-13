<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $guarded = [];
    
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
