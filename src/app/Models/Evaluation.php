<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'purchaser',
        'seller',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
