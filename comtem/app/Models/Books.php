<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    // Add properties or relationships as needed
    protected $fillable = [
        'name',
        'description',
        'file_path',
        'img',
    ];
}
