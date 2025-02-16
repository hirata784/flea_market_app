<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
    ];

    public function is_like($item_id)
    {
        return $this->items()->where('item_id', $item_id)->exists();
    }

    public function is_comment($item_id)
    {
        return $this->comment()->where('item_id', $item_id)->exists();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
        // return $this->hasMany(Item::class);
    }

    // public function like()
    // {
    //     return $this->belongsToMany(Item::class);
    //     // return $this->hasMany(Like::class);
    // }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

}
