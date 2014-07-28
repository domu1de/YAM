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
 * Extended version of the ReflectionProperty
 */
class PropertyReflection extends \ReflectionProperty
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
     * Returns a property's type if possible.
     * The result may be a special type like "string[]".
     *
     * @return string|null
     * @api
     */
    public function getType()
    {
        if (!$this->isTaggedWith('var')) {
            return null;
        }
        return ltrim($this->getTagValues('var')[0], '\\'); // TODO index check
    }
}