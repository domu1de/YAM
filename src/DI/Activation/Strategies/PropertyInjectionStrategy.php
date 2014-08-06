<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Strategies;

use YAM\DI\Planning\Directives\PropertyInjectionDirective;

/**
 * Injects properties on an instance during activation.
 *
 * @package YAM\DI\Activation\Strategies
 */
class PropertyInjectionStrategy extends ActivationStrategy
{
    public function activate($context, $instance)
    {
        foreach ($context->getPlan()->getAll(PropertyInjectionDirective::class) as $directive) {
            $injector = $directive->getInjector();
            $injector($instance, $directive->getTarget()->resolveWithIn($context));
        }
    }
} 