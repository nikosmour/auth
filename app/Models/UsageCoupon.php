<?php

namespace App\Models;

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @mixin IdeHelperUsageCoupon
 */
class UsageCoupon extends Model
{
    use HasFactory;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    public $fillable = ['academic_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(
        /**
         * define default status and entry_staff_id when model is created
         * @param $model
         * @return void
         */
            callback: function ($model): void {
                if ($model->isClean('period'))
                    $model->period = MealPlanPeriodEnum::getCurrentMealPeriod();
                if ($model->isClean('entry_staff_id'))
                    /** @noinspection PhpUndefinedFieldInspection */ $model->entry_staff_id = auth()->user()->id;
                $model->couponOwner()->decrement($model->period->name);

            });
    }

    /**
     * Get the couponOwner model associate with the usageCoupon
     * @return BelongsTo
     */
    public function couponOwner(): BelongsTo
    {
        return $this->belongsTo(CouponOwner::class, 'academic_id');
    }

    /**
     * Get the couponOwner model associate with the usageCoupon
     * @return BelongsTo
     */
    public function academic(): BelongsTo
    {
        return $this->belongsTo(Academic::class, 'academic_id');
    }

    /**
     * Get the entryStaff model associate with the usageCoupon
     * @return BelongsTo
     */
    public function entryStaff(): BelongsTo
    {
        return $this->belongsTo(EntryStaff::class);
    }

    public function scopeTakeStatistics(Builder $query, $vData)
    {
        $selectColumns = [
            DB::raw('DATE(usage_coupons.created_at) as date'),
            DB::raw("(CASE WHEN academics.status = 'researcher' THEN 'coupons staff' ELSE 'coupon students' END) as category"),
//            'academics.status as category',
        ];

        foreach ($vData['meal_category'] as $period) {
            $selectColumns[$period] = DB::raw("SUM(CASE WHEN period = '{$period}' THEN 1 ELSE 0 END) as {$period}");
        }

        return $query->select($selectColumns)
            ->join('academics', 'usage_coupons.academic_id', '=', 'academics.academic_id')
            ->whereBetween(DB::raw('DATE(usage_coupons.created_at)'), [$vData['from_date'], $vData['to_date']])
            ->groupBy('date', 'category');
    }
}
