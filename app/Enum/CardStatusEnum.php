<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum CardStatusEnum: string implements Enum
{
    use EnumTrait;

    case TEMPORARY_SAVED = 'temporary saved';
    case SUBMITTED = 'submitted';
    case CHECKING = 'checking';
    case TEMPORARY_CHECKED = 'temporary checked';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';

    /**
     * Check if the User can edit the card
     * @param Boolean $isAcademic
     * @return bool
     */
    public function canBeEdited($isAcademic = true): bool
    {
        return match ($this) {
            CardStatusEnum::TEMPORARY_SAVED => $isAcademic,
            CardStatusEnum::INCOMPLETE,
            CardStatusEnum::SUBMITTED => true,
            CardStatusEnum::CHECKING => false,
            CardStatusEnum::ACCEPTED,
            CardStatusEnum::REJECTED,
            CardStatusEnum::TEMPORARY_CHECKED => !$isAcademic
        };
    }

    /**
     * Check if the User can update the card
     * @param Boolean $isAcademic
     * @return bool
     */
    public function canBeUpdated($isAcademic = true): bool
    {
        return match ($this) {
            CardStatusEnum::TEMPORARY_SAVED => $isAcademic,
            CardStatusEnum::INCOMPLETE,
            CardStatusEnum::SUBMITTED,
            CardStatusEnum::CHECKING,
            CardStatusEnum::ACCEPTED,
            CardStatusEnum::REJECTED,
            CardStatusEnum::TEMPORARY_CHECKED => false
        };
    }
}
