<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'img_url',
        'condition',
        'brand',
        'post_code',
        'address',
        'building',
    ];

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

    public function is_category($item_id)
    {
        return $this->categories()->where('item_id', $item_id)->exists();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function sells()
    {
        return $this->hasOne(Sell::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function evaluation()
    {
        return $this->hasMany(Evaluation::class);
    }
}
