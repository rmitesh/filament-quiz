<?php

namespace App\Models;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quize extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'key', 'password', 'status', 'visibility', 'number_of_participants', 'created_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'visibility' => 'boolean',
    ];

    public function questions(): HasOne
    {
        return $this->hasOne(Question::class, 'quize_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
