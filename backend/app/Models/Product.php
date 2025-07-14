<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'product_id',
        'name',
        'description',
        'price',
        'category',
        'brand',
        'stock',
        'image_url',
        'updated_at_api',
    ];
}
