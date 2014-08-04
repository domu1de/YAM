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
 * Extended version of the ReflectionMethod
 */
class MethodReflection extends \ReflectionMethod
{
    use DocCommentTrait;

    /**
     * Returns the declaring class
     *
     * @return \YAM\Reflection\ClassReflection The declaring class
     */
    public function getDeclaringClass()
    {
        return new ClassReflection(parent::getDeclaringClass()->getName());
    }

    /**
     * Replacement for the original getParameters() method which makes sure
     * that \YAM\Reflection\ParameterReflection objects are returned instead of the
     * original ReflectionParameter instances.
     *
     * @return \YAM\Reflection\ParameterReflection[]
     */
    public function getParameters()
    {
        $extendedParameters = [];
        foreach (parent::getParameters() as $parameter) {
            $extendedParameters[] = new ParameterReflection(
                [$this->getDeclaringClass()->getName(), $this->getName()],
                $parameter->getName()
            );
        }
        return $extendedParameters;
    }

    /**
     * Gets the method prototype (if there is one).
     *
     * @return \YAM\Reflection\MethodReflection
     */
    public function getPrototype()
    {
        $prototype = parent::getPrototype();
        return new MethodReflection($prototype->getDeclaringClass()->getName(), $prototype->getName());
    }
}