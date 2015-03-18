<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation;

use YAM\Annotations\Singleton;
use YAM\DI\Exception\ActivationException;
use YAM\DI\Exception\ExceptionFormatter;
use YAM\DI\ObjectManager;
use YAM\DI\Planning\Bindings;
use YAM\Reflection\ReflectionService;

/**
 * Contains information about the activation of a single instance.
 *
 * @package YAM\DI
 */
class Context
{
    /**
     * ObjectManager that is driving the activation.
     *
     * @var \YAM\DI\ObjectManager
     */
    private $objectManager;

    /**
     * @var \YAM\DI\Activation\Request
     */
    private $request;

    /**
     * @var \YAM\DI\Planning\Bindings\Binding
     */
    private $binding;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var \SplObjectStorage
     */
    private $instanceCache;

    /**
     * @var \YAM\DI\Activation\Strategies\ActivationStrategy[]
     */
    private $strategyPipeline;

    /**
     * @var \YAM\DI\Planning\Plan
     */
    private $plan;

    /**
     * @var boolean
     */
    private $inSingletonScope;

    /**
     * @var \YAM\DI\Planning\Planner
     */
    private $planner;

    /**
     * @param \YAM\DI\ObjectManager $objectManager
     * @param \YAM\DI\Activation\Request $request
     * @param \YAM\DI\Planning\Bindings\Binding $binding
     * @param \SplObjectStorage $instanceCache
     * @param \YAM\DI\Planning\Planner $planner
     * @param \YAM\DI\Activation\Strategies\ActivationStrategy[] $activationStrategies
     */
    public function __construct($objectManager, $request, $binding, \SplObjectStorage $instanceCache, $planner, array $activationStrategies)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->binding = $binding;

        $this->planner = $planner;
        $this->instanceCache = $instanceCache;
        $this->strategyPipeline = $activationStrategies;

        $this->parameters; // set union binding.params and request.params
    }

    /**
     * Resolves the instance associated with this hook
     *
     * @throws \YAM\DI\Exception\ActivationException
     * @return object The resolved instance.
     */
    public function resolve()
    {
        if ($this->request->getActiveBindings()->contains($this->binding)) {
            throw new ActivationException(ExceptionFormatter::circularDependenciesDetected($this));
        }

        return $this->resolveInternal();
    }

    /**
     * @return object
     */
    private function resolveInternal()
    {
        if (isset($this->instanceCache[$this->binding])) {
            return $this->instanceCache[$this->binding];
        }

        $this->request->getActiveBindings()->attach($this->binding);

        $instance = $this->getProvider()->create($this);

        if ($this->inSingletonScope()) {
            $this->instanceCache[$this->binding] = $instance;
        }

        if ($this->plan === null) {
            $this->plan = $this->planner->getPlan(get_class($instance));
        }

        foreach ($this->strategyPipeline as $strategy) {
            $strategy->activate($this, $instance);
        }

        $this->request->getActiveBindings()->detach($this->binding);

        return $instance;
    }

    /**
     * @return boolean
     */
    public function inSingletonScope()
    {
        if ($this->inSingletonScope !== null) {
            return $this->inSingletonScope;
        }

        if ($this->binding->isSingleton() !== null) {
            return $this->inSingletonScope = $this->binding->isSingleton();
        }

        $reflectionService = $this->objectManager->getComponents()->get(ReflectionService::class);

        return $reflectionService->isClassAnnotatedWith($this->binding->getImplementationType(), Singleton::class);
    }

    /**
     * @return \YAM\DI\Activation\Providers\Provider
     */
    public function getProvider()
    {
        return $this->binding->getProvider($this);
    }

    /**
     * @return \YAM\DI\Planning\Bindings\Binding
     */
    public function getBinding()
    {
        return $this->binding;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @return \YAM\DI\Planning\Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @param \YAM\DI\Planning\Plan $plan
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;
    }
} 