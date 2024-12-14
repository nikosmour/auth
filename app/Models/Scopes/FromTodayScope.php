<?php

namespace App\Models\Scopes;

class FromTodayScope extends DateTimeScope
{

    /**
     * Define the column name that will apply the Scope
     *
     * @param string $column
     */
    public function __construct(string $column = 'created_at')
    {
        parent::__construct($column, '>=', now()->toDateString());
    }


}
