<?php

namespace App\Models;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use App\Interfaces\Ability;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     * @returns string[]
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status' => UserStatusEnum::class,
        ];
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['abilities'];

    /**
     * @param UserAbilityEnum $ability
     * @return bool
     */
    public function hasAbility(UserAbilityEnum $ability): bool
    {
        return $this->status->can($ability);
    }

    /**
     * check if the instance has an ability
     * @param UserAbilityEnum[] $abilities
     * @return bool
     */
    public function hasAnyAbility(array $abilities): bool
    {
        return $this->status->canAny($abilities);
    }

    /**
     * @return Ability[]
     */
    public function getAbilities(): array
    {
        return $this->status->getAbilities();
    }

    protected function abilities(): Attribute
    {
        return new Attribute(
            get: fn() => $this->getAbilities(),
        );
    }

}
