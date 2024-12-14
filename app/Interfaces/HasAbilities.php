<?php

namespace App\Interfaces;

interface HasAbilities
{
    /**
     * has the ability
     * @param Ability $ability
     * @return bool
     */
    public function can(Ability $ability): bool;

    /**
     * has any of the abilities
     * @param Ability[] $abilities
     * @return bool
     */
    public function canAny(array $abilities): bool;

    /**
     * hasn't the ability
     * @param Ability $ability
     * @return bool
     */
    public function cant(Ability $ability): bool;

    /**
     * hasn't any of the abilities
     * @param Ability[] $abilities
     * @return bool
     */
    public function cantAny(array $abilities): bool;

    /**
     * get the ability/ties that hos
     * @return Ability[]|Ability|null
     */
    public function getAbilities(): Ability|array|null;
}
