<?php

namespace App\Enum;

use App\Interfaces\Enum;
use App\Traits\Enums\EnumTrait;

enum CardDocumentStatusEnum: string implements Enum
{
    use EnumTrait;

    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case INCOMPLETE = 'incomplete';

    public function canBeUpdated(): bool
    {
        return $this == CardDocumentStatusEnum::SUBMITTED || $this == CardDocumentStatusEnum::INCOMPLETE;
    }

    public function canBeDeleted(): bool
    {
        return $this == CardDocumentStatusEnum::SUBMITTED || $this == CardDocumentStatusEnum::INCOMPLETE;
    }
}
