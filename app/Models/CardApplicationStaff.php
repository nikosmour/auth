<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCardApplicationStaff
 */
class CardApplicationStaff extends User
{
    public function cardApplication(): BelongsToMany
    {
        return $this->belongsToMany(CardApplication::class, (new CardApplicationChecking)->getTable())->using(CardApplicationChecking::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(CardApplicationUpdate::class)
            ->groupBy('status')
            ->selectRaw('count(*) as total, status')
            ->whereIn('id', function ($query) {
                $query->selectRaw('max(id)')
                    ->from((new CardApplicationUpdate())->getTable())
                    ->groupBy('card_application_id');
            });
    }
}
