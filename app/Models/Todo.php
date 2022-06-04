<?php

namespace App\Models;

use App\Models\User;
use App\Traits\EnumTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TodoStatusEnum;
use App\Traits\Searchable;

class Todo extends Model
{
    use HasFactory, SoftDeletes, EnumTrait, Searchable;

    public function getDueOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-M-y, h:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
