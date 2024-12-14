<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_details_id',
        'option',
        'created_at',
        'updated_at'
    ];
    public function questionDetails()
    {
        return $this->belongsTo(QuestionDetail::class, "question_details_id", "id");
    }
}
