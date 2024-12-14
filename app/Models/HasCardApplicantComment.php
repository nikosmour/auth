<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperHasCardApplicantComment
 */
class HasCardApplicantComment extends CardApplicationUpdate
{
    protected static function booted(): void
    {
        static::addGlobalScope('ApplicantComments', function ($builder) {
            $builder->whereNull('card_application_staff_id')->except('card_application_staff_id');
        });
    }
}
