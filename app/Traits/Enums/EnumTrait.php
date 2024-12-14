<?php

namespace App\Traits\Enums;

use App\Interfaces\Enum;
use Illuminate\Support\Collection;

trait EnumTrait
{
    /**
     * @return array <string,int|string>
     */
    public static function toInverseArray(): array
    {
        return array_combine(self::values()->toArray(), self::names()->toArray());
    }

    /**
     * @return array <string,int|string>
     */
    public static function toArray(): array
    {
        return array_combine(self::names()->toArray(), self::values()->toArray());
    }

    /**
     * @return Collection
     */
    public static function names(): Collection
    {
        return collect(array_column(self::cases(), 'name'));
    }

    /**
     * @return Collection
     */
    public static function values(): Collection
    {
        return collect(array_column(self::cases(), 'value'));
    }

    /**
     * @param string $name
     * @return Enum
     */
    public static function fromName(string $name): Enum
    {
        return self::enumByName()[$name];
    }

    /**
     * @return array <string,Enum>
     */
    public static function enumByName(): array
    {
        return array_combine(self::names()->toArray(), self::cases());
    }

    /**
     * @return array <int|string,Enum>
     */
    public static function enumByValue(): array
    {
        return array_combine(self::values()->toArray(), self::cases());
    }

    /**
     * @return Enum
     */
    public static function random(): Enum
    {
        return collect(self::cases())->random();
    }

    public function valueWithUnderscores(): string
    {
        return str_replace(' ', '_', $this->value);
    }
}
