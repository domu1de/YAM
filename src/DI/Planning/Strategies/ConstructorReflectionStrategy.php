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
use YAM\DI\Planning\Directives\ConstructorInjectionDirective;
use YAM\DI\Planning\Targets\ParameterTarget;
use YAM\Reflection\ClassReflection;

class ConstructorReflectionStrategy implements PlanningStrategy
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
     */
    public function execute($plan)
    {
        $className = $plan->getType();
        $class = new ClassReflection($className);
        $constructor = $class->getConstructor();

        if ($constructor !== null && $constructor->isPublic()) {
            $plan->add(new ConstructorInjectionDirective($constructor, function($args = []) use ($class, $className) {
                switch (count($args)) {
                    case 0: $object = new $className(); break;
                    case 1: $object = new $className($args[0]); break;
                    case 2: $object = new $className($args[0], $args[1]); break;
                    case 3: $object = new $className($args[0], $args[1], $args[2]); break;
                    case 4: $object = new $className($args[0], $args[1], $args[2], $args[3]); break;
                    case 5: $object = new $className($args[0], $args[1], $args[2], $args[3], $args[4]); break;
                    case 6: $object = new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]); break;
                    default:
                        $object = $class->newInstanceArgs($args);
                }
                return $object;
            }, $this->createTargetsFromParameters($constructor)));
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

        return function ($binding) use ($name) {
            return $binding->getName() === $name;
        };
    }
}