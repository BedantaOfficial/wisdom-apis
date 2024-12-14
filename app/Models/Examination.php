<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_date',
        'course_id',
        'semester',
        'time_in_seconds',
        'theory_question_id',
        'practical_question_id',
        'mcq_question_id',
        'theory',
        'practical',
        'mcq',
        'created_at',
        'updated_at',
        'theory_total_marks',
        'practical_total_marks',
        'mcq_total_marks',
    ];
    public function theoryQuestion()
    {
        return $this->belongsTo(Question::class, "theory_question_id", "id");
    }
    public function practicalQuestion()
    {
        return $this->belongsTo(Question::class, "practical_question_id", "id");
    }
    public function mcqQuestion()
    {
        return $this->belongsTo(Question::class, "mcq_question_id", "id");
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'examination_students', 'examination_id', 'student_id');
    }
    public function papers()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id')  // Relationship to Course
            ->whereHas('papers', function ($query) {
                $query->where('semester', $this->semester);  // Filter papers based on the semester
            });
    }
}
