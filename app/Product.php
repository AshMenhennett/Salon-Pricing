<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'brand', 'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
