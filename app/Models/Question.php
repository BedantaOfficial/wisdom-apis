<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'created_at',
        'updated_at'
    ];

    public function questionDetails()
    {
        return $this->hasMany(QuestionDetail::class, 'question_id', 'id');
    }
}
