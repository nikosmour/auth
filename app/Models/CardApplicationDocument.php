<?php

namespace App\Models;

use App\Enum\CardDocumentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperCardApplicationDocument
 */
class CardApplicationDocument extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    public $fillable = ['file_name', 'description', 'status'];
    /**
     * The attributes that should be cast.
     * @var string[]
     */
    protected $casts = [
        'status' => CardDocumentStatusEnum::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'card_application_id',
        'updated_at',
        'deleted_at',
    ];

    public function cardApplication(): BelongsTo
    {
        return $this->belongsTo(CardApplication::class);
    }

    public function canBeUpdated(): bool
    {
        return $this->status->canBeUpdated();
    }
}
