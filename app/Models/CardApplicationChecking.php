<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperCardApplicationChecking
 */
class CardApplicationChecking extends CardApplicationUpdate
{
    protected static function booted(): void
    {
        static::addGlobalScope('ApplicantComments', function ($builder) {
            $builder->whereNot('card_application_staff_id', null);
        });
    }
}
