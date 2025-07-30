<?php

namespace eseperio\verifactu\models\enums;

use ReflectionClass;

abstract class EnumHelper
{
    public static function isValidValue($value): bool
    {
        $reflectionClass = new ReflectionClass(static::class);
        return in_array($value, $reflectionClass->getConstants(), true);
    }
}
