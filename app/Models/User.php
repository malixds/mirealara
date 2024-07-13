<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'contact_link',
        'subjects',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function role() : HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function subjects() : BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'user_subjects');
    }

    public function post_accept() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_accept');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function inboxes(): hasOne
    {
        return $this->hasOne(Inbox::class);
    }

    public function chats(): hasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->slug = UserRolesEnum::ADMIN->value;
    }
}

