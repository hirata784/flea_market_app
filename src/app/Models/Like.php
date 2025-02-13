<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function scopeLikeSearch($query, $user_id)
    {
        if (!empty($user_id)) {
            $query->where('user_id', 'like', '%' . $user_id . '%');
        }
    }
}

