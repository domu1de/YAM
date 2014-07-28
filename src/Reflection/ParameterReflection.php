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
 * Extended version of the ReflectionParameter class
 */
class ParameterReflection extends \ReflectionParameter
{
    use DocCommentTrait;

    /**
     * Returns the declaring class
     *
     * @return \YAM\Reflection\ClassReflection The declaring class
     */
    public function getDeclaringClass()
    {
        $declaringClass = parent::getDeclaringClass();
        return $declaringClass !== null ? new ClassReflection($declaringClass->getName()) : null;
    }

    /**
     * Returns the parameter class
     *
     * @return \YAM\Reflection\ClassReflection The parameter class
     */
    public function getClass()
    {
        $class = parent::getClass();
        return $class !== null ? new ClassReflection($class->getName()) : null;
    }

    /**
     * Returns a parameter's type if possible.
     * The result may be a special type like 'string[]'.
     *
     * @return string|null
     * @api
     */
    public function getType()
    {
        // Type hints.
        if ($parameterClass = $this->getClass()) {
            return $parameterClass->getName();
        }

        // Callable type hint
        if ($this->isCallable()) {
            return 'callable';
        }

        // Array type hint. Try to get a more detailed type via PHPDoc.
        $type = null;
        if ($this->isArray()) {
            $type = 'array';
        }

        // PHPDoc.
        if ($this->isTaggedWith('param')) {
            $values = $this->getTagValues('param');
            if (preg_match('/([^\s]++)\s+\$' . $this->getName() . '/', implode(" ", $values), $matches)) {
                $type = $matches[1];

                // TODO: fully qualified namespace
                // TODO: check if type is array when array typehint present

                return ltrim($type, '\\');
            }
        }

        return $type;
    }

    protected function getDocComment()
    {
        return $this->getDeclaringFunction()->getDocComment();
    }
}