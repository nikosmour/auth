<?php

namespace App\Enum;

use App\Interfaces\Ability;
use App\Interfaces\Enum;
use App\Interfaces\HasAbilities;
use App\Traits\Enums\EnumTrait;
use App\Traits\Enums\HasAbilitiesTrait;

enum UserStatusEnum: string implements Enum, HasAbilities
{
    use EnumTrait, HasAbilitiesTrait;

    case UNDERGRADUATE = 'undergraduate';
    case POSTGRADUATE = 'postgraduate';
    case PHD = 'phd';
    case ERASMUS = 'erasmus';
    case RESEARCHER = 'researcher';
    case STAFF_COUPON = 'staff coupon';
    case STAFF_CARD = 'staff card application';
    case STAFF_ENTRY = 'staff entry';

    /**
     * determine if the user has the specific $role
     * @param UserRoleEnum $role
     * @return bool
     */
    public function hasRole(UserRoleEnum $role): bool
    {
        return $this->hasAnyRole([$role]);
    }

    /**
     * determine if the user has any of the specific $roles
     * @param UserRoleEnum[] $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role(), $roles);
    }

    /**
     * @return UserRoleEnum
     */
    private function role(): UserRoleEnum
    {
        return match ($this) {
            UserStatusEnum::STAFF_CARD => UserRoleEnum::STAFF_CARD,
            UserStatusEnum::STAFF_COUPON => UserRoleEnum::STAFF_COUPON,
            UserStatusEnum::STAFF_ENTRY => UserRoleEnum::STAFF_ENTRY,
            UserStatusEnum::UNDERGRADUATE,
            UserStatusEnum::POSTGRADUATE,
            UserStatusEnum::PHD,
            UserStatusEnum::ERASMUS => UserRoleEnum::STUDENT,
            UserStatusEnum::RESEARCHER => UserRoleEnum::RESEARCHER,
        };
    }

    public function can(Ability $ability): bool
    {
        return $this->role()->can($ability);
    }


    public function getAbilities(): Ability|array|null
    {
        return $this->role()->getAbilities();
    }

    public function getCouponCategory(): int|null
    {
        return $this->role()->getCouponCategory();
    }

}
