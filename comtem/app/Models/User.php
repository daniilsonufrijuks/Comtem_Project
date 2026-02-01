<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'awards',
        'family_id',
        'role',
        'is_family_admin',
        'can_use_family_card'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

//    public static function create(array $attributes)
//    {
//        return static::query()->create($attributes);
//    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Orders::class, 'user_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function ownedFamily(): HasOne
    {
        return $this->hasOne(Family::class, 'parent_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FamilyTransaction::class);
    }


    public function canUseFamilyCard(): bool
    {
        return $this->can_use_family_card && $this->family && $this->family->hasPaymentMethod();
    }

    public function getDefaultPaymentMethod()
    {
        if (!$this->family) return null;

        return $this->family->defaultPaymentMethod();
    }

    public function familyTransactions(): HasMany
    {
        return $this->hasMany(FamilyTransaction::class);
    }

    public function isFamilyAdmin()
    {
        return $this->is_family_admin || ($this->family && $this->family->parent_id === $this->id);
    }
}
