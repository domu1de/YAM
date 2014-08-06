<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Directives;


class MethodInjectionDirectiveBase implements Directive
{
    /**
     * @var \Closure
     */
    private $injector;

    /**
     * @var \YAM\DI\Planning\Targets\Target[]
     */
    private $targets = [];

    /**
     * @var \YAM\Reflection\MethodReflection
     */
    private $methodInfo;

    public function __construct($methodInfo, $injector, array $targets)
    {
        $this->methodInfo = $methodInfo;
        $this->injector = $injector;

        $this->targets = $targets;
    }

    /**
     * @return \YAM\DI\Planning\Targets\Target[]
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @return callable
     */
    public function getInjector()
    {
        return $this->injector;
    }
} 