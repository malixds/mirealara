<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    public function user(): morphTo
    // Получить модель (пользователя), к которому привязан отзыв.
    {
        return $this->morphTo();
    }

    // Получить пользователя, который оставил отзыв.
    public function reviewer(): belongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
