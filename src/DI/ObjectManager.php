<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI;

use YAM\DI\Activation\Context;
use YAM\DI\Activation\Request;
use YAM\DI\Activation\Strategies\ActivationStrategy;
use YAM\DI\Exception\ExceptionFormatter;
use YAM\DI\Exception\PlanningException;
use YAM\DI\Planning\Bindings\Binding;
use YAM\DI\Planning\Bindings\BindingBuilder;
use YAM\DI\Planning\Bindings\Resolvers\MissingBindingResolver;
use YAM\DI\Planning\Planner;

abstract class ObjectManager
{
    /**
     * Singleton instances indexed by class name.
     *
     * @var \SplObjectStorage
     */
    protected $instances;

    /**
     * Modules loaded into the object manager.
     *
     * @var \YAM\DI\Planning\Module[]
     */
    protected $modules = [];

    /**
     * @var \YAM\DI\Planning\Bindings\Binding[][]
     */
    protected $bindings = [];

    /**
     * A SplObjectStorage containing those objects which need to be shutdown when the container
     * shuts down. Each value of each entry is the respective shutdown method name.
     *
     * @var \SplObjectStorage
     */
    protected $shutdownObjects;

    /**
     * @var \YAM\DI\ComponentContainer
     */
    protected $components;

    public function __construct(array $settings = [])
    {
        $this->components = new ComponentContainer($this);
        $this->instances = new \SplObjectStorage();

        $this->bindObjectManager();
        $this->addComponents();
    }

    /**
     * Loads the module(s) into the object manager.
     *
     * @param \YAM\DI\Planning\Module[] $modules
     * @throws \YAM\DI\Exception\PlanningException
     * @api
     */
    public function load(array $modules)
    {
        foreach ($modules as $module) {
            if (!$module instanceof Planning\Module) {
                throw new PlanningException(ExceptionFormatter::unsupportedModuleType($module));
            }

            if ($this->hasModule($module->getName())) {
                throw new PlanningException(ExceptionFormatter::moduleWithSameNameIsAlreadyLoaded($module));
            }

            $module->onLoad($this);
            $this->modules[$module->getName()] = $module;
        }

        foreach ($modules as $module) {
            $module->onVerifyRequiredModules();
        }
    }

    /**
     * Unloads the module with the specified name.
     *
     * @param string $moduleName
     * @throws \YAM\DI\Exception\PlanningException
     * @api
     */
    public function unload($moduleName)
    {
        if (!$this->hasModule($moduleName)) {
            throw new PlanningException(ExceptionFormatter::noModuleLoadedWithTheSpecifiedName($moduleName));
        }

        $this->modules[$moduleName]->onUnload();
        unset($this->modules[$moduleName]);
    }

    /**
     * Return TRUE if a module with the given name exists.
     *
     * @param string $moduleName
     * @return boolean
     */
    public function hasModule($moduleName)
    {
        return array_key_exists($moduleName, $this->modules);
    }

    /**
     * Registers the specified binding.
     *
     * @param \YAM\DI\Planning\Bindings\Binding $binding
     */
    public function addBinding(\YAM\DI\Planning\Bindings\Binding $binding)
    {
        $this->bindings[$binding->getService()][] = $binding;
    }

    /**
     * Unregisters all bindings for the given service.
     *
     * @param string $service
     */
    public function removeAllBindings($service)
    {
        unset($this->bindings[$service]);
    }

    /**
     * Unregisters the specified binding.
     *
     * @param \YAM\DI\Planning\Bindings\Binding $binding
     */
    public function removeBinding(\YAM\DI\Planning\Bindings\Binding $binding)
    {
        // Ensure not null
        $service = $binding->getService();
        if (isset($this->bindings[$service])) {
            foreach ($this->bindings[$service] as $key => $definedBinding) {
                if ($definedBinding === $binding) {
                    unset($this->bindings[$service][$key]);
                }
            }
        }
    }

    /**
     * Gets an instance of the specified service.
     *
     * @param string $service The service to resolve.
     * @return object An instance of the service.
     * @api
     */
    public function get($service)
    {
        $request = new Request($service, null, [], true, false);
        return $this->resolve($request)[0];
    }

    /**
     * Gets all available instances of the specified service.
     *
     * @param string $service The service to resolve.
     * @return object[] A series of instances of the service.
     * @api
     */
    public function getAll($service)
    {
        $request = new Request($service, null, [], false, true);
        return $this->resolve($request);
    }

    /**
     * Tries to get an instance of the specified service.
     *
     * @param string $service The service to resolve
     * @return object|null An instance of the service, or NULL if no implementation was available.
     * @api
     */
    public function tryGet($service)
    {
        $request = new Request($service, null, [], true, true);
        $result = $this->resolve($request);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Injects all dependencies into the specified existing instance, without managing its lifecycle.
     *
     * @param object $instance The instance to inject into.
     * @api
     */
    public function inject($instance)
    {
        $type = get_class($instance);
        $planner = $this->components->get(Planner::class);
        $activationPipeline = $this->components->getAll(ActivationStrategy::class);

        $binding = new Binding($type);
        $request = new Request($type, null, [], false, false);
        $context = new Context($this, $request, $binding, $this->instances, $this->components->get(Planner::class), $activationPipeline);

        $context->setPlan($planner->getPlan($type));

        foreach ($activationPipeline as $strategy) {
            $strategy->activate($context, $instance);
        }
    }

    /**
     * Resolves instances for the specified request.
     *
     * @param \YAM\DI\Activation\Request $request
     * @throws \YAM\DI\Exception\PlanningException
     * @return object[] An array of instances that match the request.
     */
    public function resolve($request)
    {
        $satisfiedBindings = array_filter($this->getBindings($request->getService()), $this->satisfiesRequest($request));

        if (empty($satisfiedBindings)) {
            if ($this->handleMissingBinding($request)) {
                return $this->resolve($request);
            }

            if ($request->isOptional()) {
                return [];
            }

            throw new PlanningException(ExceptionFormatter::couldNotResolveBinding($request));
        }

        if ($request->isUnique() && count($satisfiedBindings) > 1) {
            throw new PlanningException(ExceptionFormatter::couldNotUniquelyResolveBinding($request, $satisfiedBindings));
        }

        $resolvedServices = [];
        foreach ($satisfiedBindings as $binding) {
            $context = new Context($this, $request, $binding, $this->instances, $this->components->get(Planner::class), $this->components->getAll(ActivationStrategy::class));
            $resolvedServices[] = $context->resolve();
        }

        return $resolvedServices;
    }

    /**
     * Adds components to the kernel during startup.
     *
     * @api
     */
    abstract protected function addComponents();

    /**
     * Gets the bindings registered for the given service.
     *
     * @param string $service The service in question.
     * @return Planning\Bindings\Binding[]
     */
    private function getBindings($service)
    {
        return isset($this->bindings[$service]) ? $this->bindings[$service] : [];
    }

    /**
     * @param \YAM\DI\Activation\Request $request
     * @return \Closure
     */
    private function satisfiesRequest(Activation\Request $request)
    {
        return function(Binding $binding) use ($request) {
            return $binding->matches($request) && $request->matches($binding);
        };
    }

    /**
     * Handles missing bindings.
     *
     * @param \YAM\DI\Activation\Request $request
     * @return boolean Returns TRUE if missing binding could be handled and FALSE if not
     */
    private function handleMissingBinding($request)
    {
        $resolvers = $this->components->getAll(MissingBindingResolver::class);
        foreach ($resolvers as $resolver) {
            $bindings = $resolver->resolve($this->bindings, $request);
            if (!empty($bindings)) {
                $this->bindings[$request->getService()] = $bindings;
                return true;
            }
        }

        return false;
    }

    private function bindObjectManager()
    {
        $binding = new Binding(self::class);
        $builder = new BindingBuilder($binding);
        $builder->toConstant($this)->inTransientScope();

        $this->addBinding($binding);
    }

    /**
     * @return \YAM\DI\ComponentContainer
     */
    public function getComponents()
    {
        return $this->components;
    }
}