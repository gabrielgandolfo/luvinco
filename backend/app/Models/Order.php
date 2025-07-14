<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'external_id',
        'status',
        'message',
        'estimated_delivery'
    ];

    protected $dates = ['estimated_delivery'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
