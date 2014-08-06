<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Strategies;


use YAM\DI\Planning\Directives\MethodInjectionDirective;

class MethodInjectionStrategy extends ActivationStrategy
{
    public function activate($context, $instance)
    {
        foreach ($context->getPlan()->getAll(MethodInjectionDirective::class) as $directive) {
            $arguments = [];
            foreach ($directive->getTargets() as $target) {
                $arguments[] = $target->resolveWithIn($context);
            }
            $injector = $directive->getInjector();
            $injector($instance, $arguments);
        }
    }
} 