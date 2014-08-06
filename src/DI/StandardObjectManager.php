<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Psr\Log\LoggerInterface;
use Stash\Interfaces\PoolInterface;
use YAM\Annotations as YAM;
use YAM\DI\Activation\Strategies\ActivationStrategy;
use YAM\DI\Activation\Strategies\MethodInjectionStrategy;
use YAM\DI\Activation\Strategies\PropertyInjectionStrategy;
use YAM\DI\Planning\Planner;
use YAM\DI\Planning\Strategies\ConstructorReflectionStrategy;
use YAM\DI\Planning\Strategies\MethodReflectionStrategy;
use YAM\DI\Planning\Strategies\PlanningStrategy;
use YAM\DI\Planning\Strategies\PropertyReflectionStrategy;
use YAM\Reflection\ReflectionService;

/**
 * @YAM\Scope("singleton")
 */
class StandardObjectManager extends ObjectManager
{
    /**
     * @var \Stash\Interfaces\PoolInterface
     */
    private $factoryCache;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Constructor for this DI Manager
     *
     * @param \Stash\Interfaces\PoolInterface $factoryCache
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(PoolInterface $factoryCache = null, LoggerInterface $logger = null)
    {
        parent::__construct();

        $this->factoryCache = $factoryCache;
        $this->logger = $logger;
    }

    protected function addComponents()
    {
        $this->components->add(ReflectionService::class, ReflectionService::class);

        $annotationReader = new SimpleAnnotationReader();
        $annotationReader->addNamespace('YAM\Annotations');
        $this->components->add(Reader::class, SimpleAnnotationReader::class, $annotationReader);

        $this->components->add(ActivationStrategy::class, MethodInjectionStrategy::class);
        $this->components->add(ActivationStrategy::class, PropertyInjectionStrategy::class);

        $this->components->add(Planner::class, Planner::class);

        $this->components->add(PlanningStrategy::class, ConstructorReflectionStrategy::class);
        $this->components->add(PlanningStrategy::class, MethodReflectionStrategy::class);
        $this->components->add(PlanningStrategy::class, PropertyReflectionStrategy::class);
    }
} 