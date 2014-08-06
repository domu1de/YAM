<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Strategies;


use YAM\Annotations\Named;
use YAM\DI\Exception\PlanningException;
use YAM\DI\Planning\Directives\MethodInjectionDirective;
use YAM\DI\Planning\Targets\ParameterTarget;

class MethodReflectionStrategy implements PlanningStrategy
{
    /**
     * @var \YAM\Reflection\ReflectionService
     */
    private $reflectionService;

    public function __construct(\YAM\Reflection\ReflectionService $reflectionService)
    {
        $this->reflectionService = $reflectionService;
    }

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

    /**
     * @param \YAM\Reflection\MethodReflection $methodInfo
     * @return \YAM\DI\Planning\Targets\Target[]
     */
    private function createTargetsFromParameters($methodInfo)
    {
        $targets = [];
        foreach ($methodInfo->getParameters() as $parameter) {
            $targets[] = new ParameterTarget($methodInfo, $parameter, $this->createConstraintFromParameter($parameter));
        }

        return $targets;
    }

    /**
     * @param \YAM\Reflection\ParameterReflection $parameter
     * @return \Closure
     */
    private function createConstraintFromParameter($parameter)
    {
        $methodInfo = $parameter->getDeclaringFunction();
        $annotation = $this->reflectionService->getMethodAnnotation(
            $methodInfo->getDeclaringClass()->getName(),
            $methodInfo->getName(), Named::class
        );

        if ($annotation === null || !is_array($annotation->value)) {
            return null;
        }

        $name = isset($annotation->value[$parameter->getName()]) ? (string) $annotation->value[$parameter->getName()] : null;

        if ($name === null) {
            return null;
        }

        return function ($b) use ($name) {
            return $b->getName() === $name;
        };
    }
}