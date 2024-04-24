<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    # To get the info of a follower
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
        # SELECT * FROM users WHERE id = {follower_id};
        # {follower_id} is the foreign key in the follows table that references the id column in the users table.
    }

    # To get the info of the user being followed
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
        # SELECT * FROM users WHERE id = {following_id};
        #  It retrieves the user information associated with the following_id.

    }
}
