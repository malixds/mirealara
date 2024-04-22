<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    use HasFactory;
    protected $guarded = false;
}
