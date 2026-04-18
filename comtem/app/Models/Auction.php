<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    // Add properties or relationships as needed
    protected $fillable = [
        'name',
        'description',
        'starting_bid',
        'img',
        'start_time',
        'end_time',
        'user_id',
    ];
    public function bids(): \Illuminate\Database\Eloquent\Relations\HasMany
    { return $this->hasMany(Bid::class, 'item_id'); }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { return $this->belongsTo(User::class); }

}
