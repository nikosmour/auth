<?php

namespace App\Traits\Enums;

use Illuminate\Support\Collection;

trait Names
{
    /**
     * @return Collection
     */
    public static function names(): Collection
    {
        return collect(array_column(self::cases(), 'name'));
    }
}
