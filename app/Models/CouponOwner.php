<?php

namespace App\Models;

use App\Enum\MealPlanPeriodEnum;
use App\Traits\CouponOwnerTrait;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCouponOwner
 */
class CouponOwner extends Model
{
    use HasFactory, CouponOwnerTrait;

    public $incrementing = false;
    protected $primaryKey = 'academic_id';

    protected $hidden = ['created_at', 'updated_at'];

    public function getMoneyAttribute($money): float|int
    {
        return $money / 100;
    }

    public function setMoneyAttribute($money)
    {
        $this->attributes['money'] = $money * 100;
    }

    public function academic(): BelongsTo
    {
        return $this->belongsTo(Academic::class, 'academic_id');
    }

    public function purchaseCoupon(): HasMany
    {
        return $this->hasMany(PurchaseCoupon::class, 'academic_id');
    }

    public function sendingCoupon(): HasMany
    {
        return $this->hasMany(TransferCoupon::class, 'sender_id');
    }

    public function receivingCoupon(): HasMany
    {
        return $this->hasMany(TransferCoupon::class, 'receiver_id');
    }

    public function usageCoupon(): HasMany
    {
        return $this->hasMany(UsageCoupon::class, 'academic_id');
    }

    /**
     *  relationship for all the couponTransaction of the user
     * @return HasMany
     */
    public function couponTransactions(): HasMany
    {
        $meals = MealPlanPeriodEnum::names();
        $mealColumnsUsing = collect($meals)->map(function ($meal) {
            return "CASE WHEN period = '$meal' THEN -1 ELSE 0 END as $meal";
        })->join(', ');
        $mealColumnsSending = collect($meals)->map(function ($meal) {
            return "CAST($meal AS SIGNED) * -1 as $meal";
        })->join(', ');

        $sending = $this->sendingCoupon()->select('id', DB::raw('"sending" as transaction, receiver_id as other_person_id, 0 as money'), 'created_at', DB::raw($mealColumnsSending));
        $receiving = $this->receivingCoupon()->select('id', DB::raw('"receiving" as transaction, sender_id as other_person_id, 0 as money'), 'created_at', ...$meals);
        $buying = $this->purchaseCoupon()->select('id', DB::raw('"buying" as transaction, 0 as other_person_id, money/100 as money'), 'created_at', ...$meals);
        $using = $this->usageCoupon()->select('id', DB::raw('"using" as transaction, 0 as other_person_id, 0 as money'), 'created_at', DB::raw($mealColumnsUsing));

        return $sending->union($receiving)->union($buying)->union($using)->orderByDesc('created_at');
    }


}
