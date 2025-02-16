<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // 名前で検索
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    }

    public function scopeItemSearch($query, $item_id)
    {
        if (!empty($item_id)) {
            $query->where('item_id', 'like', '%' . $item_id . '%');
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
        // return $this->belongsTo(User::class);
    }
    // public function like()
    // {
    //     return $this->belongsToMany(User::class);
    //     // return $this->hasMany(Like::class);
    // }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
