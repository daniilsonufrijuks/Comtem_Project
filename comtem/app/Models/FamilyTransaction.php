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
        'order_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the family that owns the transaction.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the user who made the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with this transaction.
     */
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
