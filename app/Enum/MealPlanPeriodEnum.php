<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum MealPlanPeriodEnum: string implements Enum
{
    use EnumTrait;

    case BREAKFAST = 'breakfast';
    case LUNCH = 'lunch';
    case DINNER = 'dinner';

    /**
     * Find the current meal period
     * @return MealPlanPeriodEnum
     */
    public static function getCurrentMealPeriod(): MealPlanPeriodEnum
    {
        $hours = (int)date('H');
        if ($hours < 11)
            return MealPlanPeriodEnum::BREAKFAST;
        elseif ($hours < 18)
            return MealPlanPeriodEnum::LUNCH;
        else
            return MealPlanPeriodEnum::DINNER;
    }
}
