<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Providers;


interface Provider
{
    /**
     * @param \YAM\DI\Activation\Context $context
     * @return object
     */
    public function create(\YAM\DI\Activation\Context $context);
} 