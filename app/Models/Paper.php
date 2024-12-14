<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;

    /**
     * Define the relationship with the Course model.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Define the relationship with the Examination model.
     */
    public function examinations()
    {
        return $this->hasMany(Examination::class, 'course_id', 'course_id');
    }
}
