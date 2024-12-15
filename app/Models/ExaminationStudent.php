<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationStudent extends Model
{
    use HasFactory;
    protected $fillable = [
        'examination_id',
        'student_id',
        'answer_file_url',
        'started_at',
        'created_at',
        'updated_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class, 'examination_id', 'id');
    }

    public function theoryAnswer()
    {
        return $this->belongsTo(Answer::class, 'theory_answer_id', 'id');
    }
    public function practicalAnswer()
    {
        return $this->belongsTo(Answer::class, 'practical_answer_id', 'id');
    }
    public function mcqAnswer()
    {
        return $this->belongsTo(Answer::class, 'mcq_answer_id', 'id');
    }
}
