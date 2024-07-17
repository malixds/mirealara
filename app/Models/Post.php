<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function post_accept() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_accept');
    }

    use HasFactory;
    protected $guarded = false;
}
