<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Utility;

/**
 * Base Enum class
 */
abstract class Enum
{
    /**
     * Store existing constants in a static cache.
     *
     * @var array
     */
    private static $constantsCache = [];

    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Creates a new value of some type.
     *
     * @param mixed $value
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        $possibleValues = self::toArray();
        if (!in_array($value, $possibleValues)) {
            throw new \UnexpectedValueException('Value "' . $value . '" is not part of the enum ' . get_called_class());
        }
        $this->value = $value;
    }

    /**
     * Returns all possible values.
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray()
    {
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constantsCache)) {
            $reflection = new \ReflectionClass($calledClass);
            self::$constantsCache[$calledClass] = $reflection->getConstants();
        }
        return self::$constantsCache[$calledClass];
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant.
     *
     * @param string $name
     * @param array $arguments
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        if (defined('static::' . $name)) {
            return new static(constant('static::' . $name));
        }
        throw new \BadMethodCallException(
            'No static method or enum constant "' . $name . '" in class ' . get_called_class()
        );
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}