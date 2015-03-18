<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2015 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Reflection;

/**
 * Represents type declarations: class types, interface types, array types, value types
 *
 * @package YAM\Reflection
 */
class Type
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var \ReflectionClass
     */
    private $classReflection;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = (string) $type;
        // TODO check if valid
    }

    /**
     * @return boolean
     */
    public function isArray()
    {
        return $this->type === 'array' || substr($this->type, -2) === '[]';
    }

    /**
     * @return boolean
     */
    public function isScalar()
    {
        $scalarTypes = [
            'bool',
            'boolean',
            'string',
            'int',
            'integer',
            'float',
            'double',
        ];

        return in_array($this->type, $scalarTypes);
    }

    /**
     * @return boolean
     */
    public function isClass()
    {
        return class_exists($this->type);
    }

    /**
     * @return boolean
     */
    public function isInterface()
    {
        return interface_exists($this->type);
    }

    /**
     * @return boolean
     */
    public function isTrait()
    {
        return trait_exists($this->type);
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        if (!$this->isClass()) {
            return false;
        }

        return $this->getClassReflection()->isAbstract();
    }

    /**
     * @return \YAM\Reflection\Type|null
     */
    public function getElementType()
    {
        // TODO better check
        if (substr($this->type, -2) === '[]') {
            return new Type(substr($this->type, 0, -2));
        }

        return null;
    }

    /**
     * @param string|\YAM\Reflection\Type $type
     * @return boolean
     */
    public function isSubtypeOf($type)
    {
        return is_subclass_of($this->type, (string) $type);
    }

    /**
     * @return boolean
     */
    public function isInstantiable()
    {
        return $this->isClass() && $this->getClassReflection()->isInstantiable();
    }

    /**
     * @param \YAM\Reflection\Type $type
     * @return boolean
     */
    public function equals($type)
    {
        return $this->type === (string) $type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }

    /**
     * @return \ReflectionClass
     */
    private function getClassReflection()
    {
        if ($this->classReflection === null) {
            $this->classReflection = new \ReflectionClass($this->type);
        }
        return $this->classReflection;
    }
} 