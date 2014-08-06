<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Targets;


abstract class Target
{
    /**
     * Member that contains the target.
     *
     * @var \YAM\Reflection\MethodReflection|\YAM\Reflection\PropertyReflection
     */
    protected $parent;

    /**
     * Member that represents the target.
     *
     * @var \YAM\Reflection\ParameterReflection|\YAM\Reflection\PropertyReflection
     */
    protected $member;

    /**
     * @var callable|null
     */
    protected $constraint;

    /**
     * @param \YAM\Reflection\MethodReflection|\YAM\Reflection\PropertyReflection $reflection
     * @param \YAM\Reflection\ParameterReflection|\YAM\Reflection\PropertyReflection $member
     * @param callable $constraint
     */
    public function __construct($reflection, $member, callable $constraint = null)
    {
        $this->parent = $reflection;
        $this->member = $member;
        $this->constraint = $constraint;
    }

    /**
     * Resolves a value for the target within the specified parent context.
     *
     * @param \YAM\DI\Activation\Context $parentContext
     * @return object|object[]
     */
    public function resolveWithIn($parentContext)
    {
        if ($this->isArray()) {
            return $this->getValues(substr($this->getType(), 0, -2), $parentContext);
        }

        return $this->getValue($this->getType(), $parentContext);
    }

    /**
     * @return bool
     */
    protected function isArray()
    {
        return substr($this->getType(), -2) === '[]';
    }

    /**
     * @param string $service
     * @param \YAM\DI\Activation\Context $parentContext
     * @return object[]
     */
    protected function getValues($service, $parentContext)
    {
        $request = $parentContext->getRequest()->createChild($service, $parentContext, $this, false, true);
        return $parentContext->getObjectManager()->resolve($request);
    }

    /**
     * @param string $service
     * @param \YAM\DI\Activation\Context $parentContext
     * @return object
     */
    protected function getValue($service, $parentContext)
    {
        $request = $parentContext->getRequest()->createChild($service, $parentContext, $this, true, false);
        return $parentContext->getObjectManager()->resolve($request)[0];
    }

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return string
     */
    public function getType()
    {
        return $this->member->getType();
    }

    /**
     * @return callable|null
     */
    public function getConstraint()
    {
        return $this->constraint;
    }
} 