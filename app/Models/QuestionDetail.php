<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'question',
        'created_at',
        'updated_at'
    ];
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_details_id', 'id');
    }
}
