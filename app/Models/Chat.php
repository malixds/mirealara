<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users() : belongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function messages() : HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function inboxes() : belongsToMany
    {
        return $this->belongsToMany(Inbox::class);
    }
}
