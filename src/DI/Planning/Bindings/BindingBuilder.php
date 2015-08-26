<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Bindings;


use YAM\DI\Activation\Providers\CallbackProvider;
use YAM\DI\Activation\Providers\ConstantProvider;
use YAM\DI\Activation\Providers\StandardProvider;

/**
 * Provides a root for the fluent syntax associated with an YAM\DI\Bindings\Binding.
 *
 * @package YAM\DI\Bindings
 */
class BindingBuilder
{
    /**
     * @var \YAM\DI\Planning\Bindings\Binding
     */
    private $binding;

    public function __construct(\YAM\DI\Planning\Bindings\Binding $binding)
    {
        $this->binding = $binding;
    }

    /**
     * Indicates that the service should be bound to the specified implementation type.
     *
     * @param string $type
     * @return $this
     * @api
     */
    public function to($type)
    {
        $this->binding->setTarget(BindingTarget::TYPE());
        $this->binding->setImplementationType($type);
        $this->binding->setProviderCallback(StandardProvider::getCreationCallback($type));
        return $this;
    }

    /**
     * Indicates that the service should be self-bound.
     *
     * @return $this
     * @api
     */
    public function toSelf()
    {
        $this->binding->setTarget(BindingTarget::SELF());
        $this->binding->setImplementationType($this->binding->getService());
        $this->binding->setProviderCallback(StandardProvider::getCreationCallback($this->binding->getService()));
        return $this;
    }

    /**
     * Indicates that the service should be bound to the specified provider.
     *
     * @param \YAM\DI\Activation\Providers\Provider $provider
     * @return $this
     * @api
     */
    public function toProvider(\YAM\DI\Activation\Providers\Provider $provider)
    {
        $this->binding->setTarget(BindingTarget::PROVIDER());
        $this->binding->setImplementationType($this->binding->getService());
        $this->binding->setProviderCallback(function() use ($provider) {
            return $provider;
        });
        return $this;
    }

    /**
     * Indicates that the service should be bound to the specified constant value.
     *
     * @param object $constant The constant value.
     * @return $this
     * @api
     */
    public function toConstant($constant)
    {
        $this->binding->setTarget(BindingTarget::CONSTANT());
        $this->binding->setImplementationType($this->binding->getService());
        $this->binding->setIsSingleton(true);
        $this->binding->setProviderCallback(function() use ($constant) {
            return new ConstantProvider($constant);
        });
        return $this;
    }

    /**
     * Indicates that the service should be bound to the specified callback method.
     *
     * @param callable $method
     * @return $this
     * @api
     */
    public function toMethod(callable $method)
    {
        // TODO static callable string is not supported before PHP7
        $this->binding->setTarget(BindingTarget::METHOD());
        $this->binding->setImplementationType($this->binding->getService());
        $this->binding->setProviderCallback(function() use ($method) {
            return new CallbackProvider($method);
        });
        return $this;
    }

    /**
     * Indicates that only a single instance of the binding should be created, and then
     * should be re-used for all subsequent requests.
     *
     * @return $this
     * @api
     */
    public function inSingletonScope()
    {
        $this->binding->setIsSingleton(true);
        return $this;
    }

    /**
     * Indicates that instances should not be re-used.
     *
     * @return $this
     * @api
     */
    public function inTransientScope()
    {
        $this->binding->setIsSingleton(false);
        return $this;
    }

    /**
     * Indicates that the specified constructor argument should be overridden with the specified value.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     * @api
     */
    public function withConstructorArgument($name, $value)
    {
        $this->binding->addConstructorArgument($name, $value);
        return $this;
    }

    /**
     * Indicates that the binding should be registered with the specified name. Names are not
     * necessarily unique; multiple bindings for a given service may be registered with the same name.
     *
     * @param string $name
     * @return $this
     * @api
     */
    public function named($name)
    {
        $this->binding->setName((string) $name);
        return $this;
    }
} 