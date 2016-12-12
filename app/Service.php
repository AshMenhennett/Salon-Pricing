<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{

    /**
     * Mass assignable fields.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'price', 'category',
    ];

    /**
     * Each Service belongs to a User.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
