<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Strategies;


use YAM\DI\Exception\PlanningException;
use YAM\DI\Planning\Directives\MethodInjectionDirective;

class MethodReflectionStrategy extends MethodReflectionStrategyBase
{
    /**
     * Contributes to the specified plan.
     *
     * @param \YAM\DI\Planning\Plan $plan
     * @throws \YAM\DI\Exception\PlanningException
     */
    public function execute($plan)
    {
        foreach ($this->reflectionService->getMethodsAnnotatedWith($plan->getType(), \YAM\Annotations\Inject::class) as $method) {
            if (!$method->isPublic()) {
                throw new PlanningException('Cannot inject into non-public methods');
            }

            $plan->add(new MethodInjectionDirective($method, function ($target, $args = []) use ($method) {
                $method->invokeArgs($target, $args);
            }, $this->createTargetsFromParameters($method)));
        }
    }
}