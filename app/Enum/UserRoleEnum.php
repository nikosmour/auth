<?php

namespace App\Enum;

use App\Interfaces\Ability;
use App\Interfaces\Enum;
use App\Interfaces\HasAbilities;
use App\Traits\Enums\EnumTrait;
use App\Traits\Enums\HasAbilitiesTrait;

enum UserRoleEnum: string implements Enum, HasAbilities
{
    use EnumTrait, HasAbilitiesTrait;

    case STUDENT = 'student';
    case RESEARCHER = 'researcher';
    case STAFF_COUPON = 'staff coupon';
    case STAFF_CARD = 'staff card application';
    case STAFF_ENTRY = 'staff entry';

    public function can(Ability $ability): bool
    {
        return in_array($ability, $this->getAbilities());
    }

    public function getAbilities(): array
    {
        return match ($this) {
            UserRoleEnum::STUDENT => [UserAbilityEnum::COUPON_OWNERSHIP, UserAbilityEnum::CARD_OWNERSHIP],
            UserRoleEnum::RESEARCHER => [UserAbilityEnum::COUPON_OWNERSHIP],
            UserRoleEnum::STAFF_COUPON => [UserAbilityEnum::COUPON_SELL],
            UserRoleEnum::STAFF_ENTRY => [UserAbilityEnum::ENTRY_CHECK],
            UserRoleEnum::STAFF_CARD => [UserAbilityEnum::CARD_APPLICATION_CHECK]
        };
    }

    public function getCouponCategory(): int|null
    {
        return match ($this) {
            UserRoleEnum::STUDENT => 1,
            UserRoleEnum::RESEARCHER => 2,
            UserRoleEnum::STAFF_COUPON,
            UserRoleEnum::STAFF_ENTRY,
            UserRoleEnum::STAFF_CARD => null
        };
    }

}
