<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    // Add these fields to the fillable array
    protected $fillable = [
        'name',
        'price',
        'description',
        'img',
        'category'
    ];
}
