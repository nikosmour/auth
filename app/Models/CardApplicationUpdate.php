<?php

namespace App\Models;

use App\Enum\CardStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * @mixin IdeHelperCardApplicationUpdate
 */
class CardApplicationUpdate extends Pivot
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'card_application_update';
    protected $casts = [
        'status' => CardStatusEnum::class,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(
        /**
         * define default card_application_staff_id when model is created and
         * @param $model
         * @return void
         */
            function ($model) {
                $user = auth()->user();
                if ($model->isClean('card_application_staff_id') && $user instanceof CardApplicationStaff)
                    $model->card_application_staff_id = $user->id;
            });
    }


    public function cardApplicationStaff(): BelongsTo
    {
        return $this->belongsTo(CardApplicationStaff::class);
    }

    public function cardApplication(): BelongsTo
    {
        return $this->belongsTo(CardApplication::class);
    }
}
