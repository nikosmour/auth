<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DateTimeScope implements Scope
{
    private string $column;
    private string $operator;
    private string $datetime;

    /**
     * Define the properties that will apply in the Scope
     *
     * @param string $column Define the column name
     * @param string $operator Define the operator
     * @param String $datetime Define the datetime
     */
    public function __construct(string $column = 'created_at', string $operator = '>=', string $datetime = '')
    {
        $this->column = $column;
        $this->operator = $operator;
        if (empty($datetime))
            $datetime = now()->toDateTimeString();
        $this->datetime = $datetime;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($this->column, $this->operator, $this->datetime);
    }


}
