<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning;

use YAM\DI\ObjectManager;

/**
 * A loadable unit that defines bindings for your application.
 *
 * @package YAM\DI
 */
abstract class Module
{
    /**
     * @var \YAM\DI\ObjectManager
     */
    private $objectManager;

    /**
     * @var \YAM\DI\Planning\Bindings\Binding[]
     */
    private $bindings = [];

    /**
     * Called when the module is loaded into an object manager.
     *
     * @param \YAM\DI\ObjectManager $objectManager The object manager that is loading the module
     */
    public function onLoad(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->load();
    }

    /**
     * Loads the module into the object manager.
     */
    abstract public function load();

    /**
     * Called when the module is unloaded from the object manager
     */
    public function onUnload()
    {
        $this->unload();
        array_map([$this->objectManager, 'removeBinding'], $this->bindings);
        $this->objectManager = null;
    }

    /**
     * Unloads the module from the object manager.
     */
    protected function unload() {}

    /**
     * @return string
     */
    final public function getName()
    {
        return get_class($this);
    }

    /**
     * Called after loading the modules. A module can verify here if all other required modules are loaded.
     */
    public function onVerifyRequiredModules()
    {
        $this->verifyRequiredModulesAreLoaded();
    }

    /**
     * Called after loading the modules. A module can verify here if all other required modules are loaded.
     */
    protected function verifyRequiredModulesAreLoaded() {}

    /**
     * Declares a binding for the specified service.
     *
     * @param string $service
     * @return \YAM\DI\Planning\Bindings\BindingBuilder
     */
    protected function bind($service)
    {
        $binding = new \YAM\DI\Planning\Bindings\Binding($service);

        $this->objectManager->addBinding($binding);
        $this->bindings[] = $binding;

        return new \YAM\DI\Planning\Bindings\BindingBuilder($binding);
    }

    /**
     * Unregisters all bindings for the specified service.
     *
     * @param string $service
     */
    protected function unbind($service)
    {
        $this->objectManager->removeAllBindings($service);
    }

    /**
     * Removes any existing bindings for the specified service, and declares a new one.
     *
     * @param string $service
     * @return \YAM\DI\Planning\Bindings\BindingBuilder
     */
    protected function rebind($service)
    {
        $this->unbind($service);
        return $this->bind($service);
    }

    /**
     * @return \YAM\DI\ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}