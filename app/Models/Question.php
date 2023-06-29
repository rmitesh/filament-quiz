<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quize_id', 'questions',
    ];

    protected $casts = [
        'questions' => 'json',
    ];

    public function getTotalQuestionsAttribute()
    {
        return count($this->questions ?? []);
    }
}
