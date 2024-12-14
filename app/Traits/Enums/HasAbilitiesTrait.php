<?php

namespace App\Traits\Enums;


use App\Interfaces\Ability;

trait HasAbilitiesTrait
{

    public function cant(Ability $ability): bool
    {
        return !$this->can($ability);
    }

    public function cantAny(array $abilities): bool
    {
        return !$this->canAny($abilities);
    }

    public function canAny(array $abilities): bool
    {
        foreach ($abilities as $ability)
            if ($this->can($ability))
                return true;
        return false;
    }

}
