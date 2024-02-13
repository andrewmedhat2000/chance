<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table='surveys';
   protected  $guarded=[];
   public function questions()
{
    return $this->hasMany(Question::class);
}
public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
