<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        "student_id",
        "id",
        "marksheet_filename",
        "certificate_filename"
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, "student_id", "id");
    }
}
