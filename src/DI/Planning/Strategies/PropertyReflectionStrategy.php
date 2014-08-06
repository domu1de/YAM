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
use YAM\DI\Planning\Directives\PropertyInjectionDirective;
use YAM\DI\Planning\Targets\PropertyTarget;

class PropertyReflectionStrategy implements PlanningStrategy
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
        foreach ($this->reflectionService->getPropertiesAnnotatedWith($plan->getType(), \YAM\Annotations\Inject::class) as $property) {
            if ($property->isPrivate()) {
                throw new PlanningException('Cannot inject private properties');
            }

            $plan->add(new PropertyInjectionDirective($property, function($target, $value) use ($property) {
                $property->setAccessible(true);
                $property->setValue($target, $value);
            }, new PropertyTarget($property, $this->createConstraintFromProperty($property))));
        }
    }

    /**
     * @param \YAM\Reflection\PropertyReflection $propertyInfo
     * @return \Closure|null
     */
    public function createConstraintFromProperty($propertyInfo)
    {
        $annotation = $this->reflectionService->getPropertyAnnotation(
            $propertyInfo->getDeclaringClass()->getName(),
            $propertyInfo->getName(),
            Named::class
        );

        if ($annotation === null || !is_string($annotation->value)) {
            return null;
        }

        $name = $annotation->value;
        return function ($binding) use ($name) {
            return $binding->getName() === $name;
        };
    }
} 