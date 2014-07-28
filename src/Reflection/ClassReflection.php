<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Reflection;

/**
 * Extended version of the ReflectionClass
 */
class ClassReflection extends \ReflectionClass
{
    use DocCommentTrait;

    /**
     * Replacement for the original getConstructor() method which makes sure
     * that \YAM\Reflection\MethodReflection objects are returned instead of the
     * original ReflectionMethod instances.
     *
     * @return \YAM\Reflection\MethodReflection Method reflection object of the constructor method
     */
    public function getConstructor()
    {
        $parentConstructor = parent::getConstructor();
        return ($parentConstructor === null) ? $parentConstructor : new MethodReflection($this->getName(), $parentConstructor->getName());
    }

    /**
     * Replacement for the original getInterfaces() method which makes sure
     * that \YAM\Reflection\ClassReflection objects are returned instead of the
     * original ReflectionClass instances.
     *
     * @return array of \YAM\Reflection\ClassReflection Class reflection objects of the properties in this class
     */
    public function getInterfaces()
    {
        $extendedInterfaces = array();
        $interfaces = parent::getInterfaces();
        foreach ($interfaces as $interface) {
            $extendedInterfaces[] = new ClassReflection($interface->getName());
        }
        return $extendedInterfaces;
    }

    /**
     * Replacement for the original getParentClass() method which makes sure
     * that a \YAM\Reflection\ClassReflection object is returned instead of the
     * original ReflectionClass instance.
     *
     * @return \YAM\Reflection\ClassReflection Reflection of the parent class - if any
     */
    public function getParentClass()
    {
        $parentClass = parent::getParentClass();
        return ($parentClass === false) ? false : new ClassReflection($parentClass->getName());
    }

    /**
     * Replacement for the original getMethod() method which makes sure
     * that \YAM\Reflection\MethodReflection objects are returned instead of the
     * original ReflectionMethod instances.
     *
     * @param string $name
     * @return \YAM\Reflection\MethodReflection Method reflection object of the named method
     */
    public function getMethod($name)
    {
        return new MethodReflection($this->getName(), $name);
    }

    /**
     * Replacement for the original getMethods() method which makes sure
     * that \YAM\Reflection\MethodReflection objects are returned instead of the
     * original ReflectionMethod instances.
     *
     * @param integer $filter A filter mask
     * @return \YAM\Reflection\MethodReflection[] Method reflection objects of the methods in this class
     */
    public function getMethods($filter = null)
    {
        $extendedMethods = [];

        $methods = ($filter === null ? parent::getMethods() : parent::getMethods($filter));
        foreach ($methods as $method) {
            $extendedMethods[] = new MethodReflection($this->getName(), $method->getName());
        }
        return $extendedMethods;
    }

    /**
     * Replacement for the original getProperty() method which makes sure
     * that a \YAM\Reflection\PropertyReflection object is returned instead of the
     * original ReflectionProperty instance.
     *
     * @param string $name Name of the property
     * @return \YAM\Reflection\PropertyReflection Property reflection object of the specified property in this class
     */
    public function getProperty($name)
    {
        return new PropertyReflection($this->getName(), $name);
    }

    /**
     * Replacement for the original getProperties() method which makes sure
     * that \YAM\Reflection\PropertyReflection objects are returned instead of the
     * original ReflectionProperty instances.
     *
     * @param integer $filter A filter mask
     * @return \YAM\Reflection\PropertyReflection[] Property reflection objects of the properties in this class
     */
    public function getProperties($filter = null)
    {
        $extendedProperties = [];

        $properties = ($filter === null ? parent::getProperties() : parent::getProperties($filter));
        foreach ($properties as $property) {
            $extendedProperties[] = new PropertyReflection($this->getName(), $property->getName());
        }
        return $extendedProperties;
    }
}