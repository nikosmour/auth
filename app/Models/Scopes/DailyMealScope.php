<?php

namespace App\Models\Scopes;

use App\Enum\MealPlanPeriodEnum;
use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DailyMealScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder2 = MealPlan::where(
            'period', '=', MealPlanPeriodEnum::LUNCH)->
        select(['id as lunch_id', 'date as lunch_date']);
        $builder3 = MealPlan::where(
            'period', '=', MealPlanPeriodEnum::DINNER)->
        select(['id as dinner_id', 'date as dinner_date']);
        $builder->
        select(['date', 'id as breakfast_id', 'lunch_id', 'dinner_id'])
            ->where(
                'period', '=', MealPlanPeriodEnum::BREAKFAST);
        $builder->joinSub(
            $builder2, 'lunch_meal', function ($join) {
            $join->on('date', '=', 'lunch_date');
        })->joinSub(
            $builder3, 'dinner_meal', function ($join) {
            $join->on('date', '=', 'dinner_date');
        });
    }

}
