<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
