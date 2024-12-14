<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCouponStaff
 */
class CouponStaff extends User
{
    public function purchaseCoupon(): HasMany
    {
        return $this->hasMany(PurchaseCoupon::class);
    }

    public static function takeStatistics($vData)
    {
        return PurchaseCoupon::takeStatistics($vData)
            ->orderBy('date', 'desc');
    }
}
