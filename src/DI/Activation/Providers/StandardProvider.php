<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Providers;


use YAM\DI\Planning\Directives\ConstructorInjectionDirective;
use YAM\DI\Planning\Planner;

class StandardProvider implements Provider
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var \YAM\DI\Planning\Planner
     */
    private $planner;

    /**
     * @param string $type
     * @param \YAM\DI\Planning\Planner $planner
     */
    public function __construct($type, $planner)
    {
        $this->type = $type;
        $this->planner = $planner;
    }

    public function create(\YAM\DI\Activation\Context $context)
    {
        $plan = $context->getPlan();
        if ($plan === null) {
            $plan = $this->planner->getPlan($this->type);
            $context->setPlan($plan);
        }

        /** @var \YAM\DI\Planning\Directives\ConstructorInjectionDirective $constructorInjectionDirective */
        $constructorInjectionDirective = $plan->getOne(ConstructorInjectionDirective::class);

        if ($constructorInjectionDirective !== null) {
            $arguments = [];
            foreach ($constructorInjectionDirective->getTargets() as $target) {
                $arguments[] = $target->resolveWithIn($context);
            }

            $injector = $constructorInjectionDirective->getInjector();
            return $injector($arguments);
        }

        $className = $this->type;
        return new $className();
    }

    /**
     * @param string $type
     * @return callable
     */
    public static function getCreationCallback($type)
    {
        return function($context) use ($type) {
            return new static($type, $context->getObjectManager()->getComponents()->get(Planner::class));
        };
    }
} 