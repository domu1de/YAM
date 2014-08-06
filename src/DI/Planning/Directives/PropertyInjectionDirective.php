<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Directives;


class PropertyInjectionDirective implements Directive
{
    /**
     * @var \Closure
     */
    private $injector;

    /**
     * @var \YAM\DI\Planning\Targets\Target
     */
    private $target;

    /**
     * @var \YAM\Reflection\PropertyReflection
     */
    private $propertyInfo;

    public function __construct($propertyInfo, $propertyInjector, $target)
    {
        $this->propertyInfo = $propertyInfo;
        $this->injector = $propertyInjector;

        $this->target = $target;
    }

    /**
     * @return \YAM\DI\Planning\Targets\Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return callable
     */
    public function getInjector()
    {
        return $this->injector;
    }
} 