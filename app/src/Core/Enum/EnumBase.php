<?php

namespace Up\Core\Enum;

use ReflectionClass;
use ReflectionException;

abstract class EnumBase
{
    private static ?array $constants = null;

    /**
     * @throws ReflectionException
     */
    private static function getConstants()
    {
        if (self::$constants == null) {
            self::$constants = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constants)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constants[$calledClass] = $reflect->getConstants();
        }
        return self::$constants[$calledClass];
    }

    /**
     * @throws ReflectionException
     */
    public static function isValidValue($value, $strict = true): bool
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}
