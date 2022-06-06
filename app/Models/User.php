<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function todos()
    {
        return $this->hasMany(Todo::class, 'user_id', 'id');
    }
}
