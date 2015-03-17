<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Strategies;


use YAM\DI\Planning\Directives\ConstructorInjectionDirective;
use YAM\Reflection\ClassReflection;

class ConstructorReflectionStrategy extends MethodReflectionStrategyBase
{
    /**
     * Contributes to the specified plan.
     *
     * @param \YAM\DI\Planning\Plan $plan
     */
    public function execute($plan)
    {
        $className = $plan->getType();
        $class = new ClassReflection($className);
        $constructor = $class->getConstructor();

        if ($constructor !== null && $constructor->isPublic()) {
            $plan->add(new ConstructorInjectionDirective($constructor, function($args = []) use ($className) {
                return new $className(...$args);
            }, $this->createTargetsFromParameters($constructor)));
        }
    }
}