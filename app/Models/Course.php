<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public function papers()
    {
        return $this->hasMany(Paper::class, 'course_id', 'id');
    }

    /**
     * Define the relationship with the Examination model.
     */
    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }
}
