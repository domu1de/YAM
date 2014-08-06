<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Providers;


class ConstantProvider implements Provider
{
    /**
     * @var object
     */
    private $value;

    /**
     * @param object $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function create(\YAM\DI\Activation\Context $context)
    {
        return $this->value;
    }
} 