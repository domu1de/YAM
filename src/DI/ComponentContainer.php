<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI;


use YAM\DI\Exception\ExceptionFormatter;
use YAM\Reflection\ClassReflection;

/**
 * An internal IoC-Container that manages and resolves components that contribute to YAM-DI.
 *
 * @package YAM\DI
 */
class ComponentContainer
{
    /**
     * @var array
     */
    private $mappings = [];

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var \YAM\DI\ObjectManager
     */
    private $objectManager;

    /**
     * @param \YAM\DI\ObjectManager $objectManager
     */
    public function __construct($objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $component
     * @param string $implementation
     * @param object $instance
     */
    public function add($component, $implementation, $instance = null)
    {
        $this->mappings[$component][] = $implementation;
        if ($instance !== null) {
            $this->instances[$implementation] = $instance;
        }
    }

    /**
     * Removes the specified registration.
     *
     * @param string $component
     * @param string $implementation
     */
    public function remove($component, $implementation)
    {
        unset($this->instances[$implementation]);

        if (isset($this->mappings[$component])) {
            foreach ($this->mappings[$component] as $i => $impl) {
                if ($impl === $implementation) {
                    unset($this->mappings[$component][$i]);
                }
            }
        }
    }

    /**
     * Removes all registrations for the specified component.
     *
     * @param string $component
     */
    public function removeAll($component)
    {
        if (isset($this->mappings[$component])) {
            foreach ($this->mappings[$component] as $implementation) {
                unset($this->instances[$implementation]);
            }
            unset($this->mappings[$component]);
        }
    }

    /**
     * Gets one available instances of the specified component.
     *
     * @param string $component
     * @throws \Exception
     * @return object
     */
    public function get($component)
    {
        if (substr($component, -2) === '[]') {
            return $this->getAll(substr($component, 0, -2));
        }

        if (!isset($this->mappings[$component]) || empty($this->mappings[$component])) {
            throw new \InvalidArgumentException(ExceptionFormatter::noSuchComponentRegistered($component));
        }

        $implementation = reset($this->mappings[$component]);
        return $this->resolveInstance($component, $implementation);
    }

    /**
     * Gets all available instances of the specified component.
     *
     * @param string $component
     * @return object[]
     */
    public function getAll($component)
    {
        if (!isset($this->mappings[$component]) || empty($this->mappings[$component])) {
            throw new \InvalidArgumentException(ExceptionFormatter::noSuchComponentRegistered($component));
        }

        $resolvedInstances = [];
        foreach ($this->mappings[$component] as $implementation) {
            $resolvedInstances[] = $this->resolveInstance($component, $implementation);
        }

        return $resolvedInstances;
    }

    private function resolveInstance($component, $implementation)
    {
        if (isset($this->instances[$implementation])) {
            return $this->instances[$implementation];
        }

        $arguments = [];
        $clazz = new ClassReflection($implementation);
        if ($constructor = $clazz->getConstructor()) {
            foreach ($constructor->getParameters() as $parameter) {
                $arguments[] = $this->get($parameter->getType());
            }
        }

        return $this->instances[$implementation] = new $implementation(...$arguments);
    }
} 