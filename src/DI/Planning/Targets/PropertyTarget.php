<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Targets;


class PropertyTarget extends Target
{
    /**
     * @param \YAM\Reflection\PropertyReflection $member
     * @param callable $constraint
     */
    public function __construct($member, callable $constraint = null)
    {
        parent::__construct($member, $member, $constraint);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('property %s of type %s', $this->member->getName(), $this->getType());
    }
}