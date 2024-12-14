<?php

namespace App\Traits\Enums;

use Illuminate\Support\Collection;

trait Values
{
    /**
     * @return Collection
     */
    public static function values(): Collection
    {
        return collect(array_column(self::cases(), 'value'));
    }
}
