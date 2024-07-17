<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function posts() : hasMany
    {
        return $this->hasMany(Post::class);
    }

    public function users() : BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }
}
