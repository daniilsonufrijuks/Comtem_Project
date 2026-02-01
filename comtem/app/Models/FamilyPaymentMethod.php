<?php
// app/Models/FamilyPaymentMethod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'stripe_payment_method_id',
        'stripe_customer_id',
        'card_last_four',
        'card_brand',
        'is_default',
        'added_by_user_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'added_at' => 'datetime',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }
}
