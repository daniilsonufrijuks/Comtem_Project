<?php
// app/Models/Family.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_name',
        'invitation_code',
        'parent_id',
        'stripe_customer_id',
        'stripe_payment_method_id',
        'card_last_four',
        'card_brand',
        'max_members',
        'is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class)->where('role', 'child');
    }

    public function paymentMethods()
    {
        return $this->hasMany(FamilyPaymentMethod::class);
    }

    public function transactions()
    {
        return $this->hasMany(FamilyTransaction::class);
    }

    public function defaultPaymentMethod()
    {
        return $this->paymentMethods()->where('is_default', true)->first();
    }

    public function hasPaymentMethod()
    {
        return $this->paymentMethods()->exists();
    }

    public static function generateInvitationCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('invitation_code', $code)->exists());

        return $code;
    }

    public function getAvailableSlots()
    {
        return $this->max_members - $this->users()->count();
    }
}
