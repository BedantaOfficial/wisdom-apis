<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student_admissions';
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function examinations()
    {
        return $this->hasMany(ExaminationStudent::class, 'student_id', 'id');
    }
}
