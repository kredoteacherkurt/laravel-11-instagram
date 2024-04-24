<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1; #  Defines constant for admin role ID.
    const USER_ROLE_ID = 2; #  Defines constant for user role ID.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # To get all the posts of a user
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
        # latest() -> to get the newest posts first
    }

    # To get all the followers of a user
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
        # SELECT * FROM follows WHERE following_id = {id};
        # {id} is the ID of the user whose followers are being retrieved.
    }

    # To get all the users that the user is following
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
        # SELECT * FROM follows WHERE follower_id = {id};
        # The SQL query retrieves all records from the follows table where the follower_id column matches the ID of the user ({id}) whose followers are being retrieved.
    }

    # Returns TRUE if the AUTH USER is following a user.
    public function isFollowed()
    {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }
}
