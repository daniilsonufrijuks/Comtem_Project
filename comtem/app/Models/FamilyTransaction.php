<?php
// app/Models/FamilyTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'user_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'description',
        'status',
        'payment_method_used',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
