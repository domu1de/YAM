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
use YAM\DI\Planning\Targets\ParameterTarget;

abstract class MethodReflectionStrategyBase implements PlanningStrategy
{
    /**
     * @var \YAM\Reflection\ReflectionService
     */
    protected $reflectionService;

    public function __construct(\YAM\Reflection\ReflectionService $reflectionService)
    {
        $this->reflectionService = $reflectionService;
    }

    /**
     * @param \YAM\Reflection\MethodReflection $methodInfo
     * @return \YAM\DI\Planning\Targets\Target[]
     */
    protected function createTargetsFromParameters($methodInfo)
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
    protected function createConstraintFromParameter($parameter)
    {
        $methodInfo = $parameter->getDeclaringFunction();
        $annotation = $this->reflectionService->getMethodAnnotation(
            $methodInfo->getDeclaringClass()->getName(),
            $methodInfo->getName(), Named::class
        );

        if ($annotation === null || !is_array($annotation->value)) {
            return null;
        }

        $name = isset($annotation->value[$parameter->getName()]) ? (string)$annotation->value[$parameter->getName()] : null;

        if ($name === null) {
            return null;
        }

        return function ($binding) use ($name) {
            return $binding->getName() === $name;
        };
    }
}