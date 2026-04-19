<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bids extends Model
{
    protected $fillable = ['bid_amount', 'user_id', 'item_id'];

    public function auction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { return $this->belongsTo(Auction::class, 'item_id'); }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { return $this->belongsTo(User::class); }
    public function product()
    {
        return $this->auction();
    }
}
