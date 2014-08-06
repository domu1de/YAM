<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Targets;


class ParameterTarget extends Target
{
    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->parent->isConstructor()) {
            $string = 'parameter %s of constructor';
        } else {
            $string = 'parameter %s of method ' . $this->parent->getName();
        }
        return sprintf($string . ' of type %s', $this->member->getName(), $this->getType());
    }
}