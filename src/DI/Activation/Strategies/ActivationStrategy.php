<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Strategies;


abstract class ActivationStrategy
{
    /**
     * Contributes to the activation of the instance in the specified context.
     *
     * @param \YAM\DI\Activation\Context $context
     * @param object $instance A reference to the instance being activated.
     */
    public function activate($context, $instance) {}

    /**
     * Contributes to the deactivation of the instance in the specified context.
     *
     * @param \YAM\DI\Activation\Context $context
     * @param object $instance A reference to the instance being deactivated.
     */
    public function deactivate($context, $instance) {}
}