<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Bindings;

/**
 * Contains information about a service registration.
 *
 * @package YAM\DI\Bindings
 */
class Binding
{
    /**
     * @var string
     */
    private $service;

    /**
     * @var \YAM\DI\Planning\Bindings\BindingTarget
     */
    private $target;

    /**
     * @var bool
     */
    private $isSingleton;

    /**
     * @var string
     */
    private $implementationType;

    /**
     * @var callable
     */
    private $providerCallback;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $constructorArguments = [];

    /**
     * @var callable
     */
    private $condition;

    /**
     * @param string $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Determines whether the given request satisfies the binding's conditions.
     *
     * @param \YAM\DI\Activation\Request $request
     * @return bool
     */
    public function matches(\YAM\DI\Activation\Request $request)
    {
        return $this->condition === null || call_user_func($this->condition, $request);
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return \YAM\DI\Planning\Bindings\BindingTarget
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param \YAM\DI\Planning\Bindings\BindingTarget $target
     */
    public function setTarget(\YAM\DI\Planning\Bindings\BindingTarget $target)
    {
        $this->target = $target;
    }

    /**
     * @param string $type
     */
    public function setImplementationType($type)
    {
        $this->implementationType = $type;
    }

    /**
     * @return string
     */
    public function getImplementationType()
    {
        return $this->implementationType;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addConstructorArgument($name, $value)
    {
        $this->constructorArguments[$name] = $value;
    }

    /**
     * @return array
     */
    public function getConstructorArguments()
    {
        return $this->constructorArguments;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \YAM\DI\Activation\Context $context
     * @return \YAM\DI\Activation\Providers\Provider
     */
    public function getProvider($context)
    {
        return call_user_func($this->providerCallback, $context);
    }

    /**
     * @param string|callable $provider
     */
    public function setProviderCallback($provider)
    {
        $this->providerCallback = $provider;
    }

    /**
     * @param callable $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return bool|null Return TRUE or FALSE if scope was explicitly set, otherwise NULL.
     */
    public function isSingleton()
    {
        return $this->isSingleton;
    }

    /**
     * @param bool $isSingleton
     */
    public function setIsSingleton($isSingleton)
    {
        $this->isSingleton = $isSingleton;
    }

    public function __toString()
    {
        $string = '';

        if ($this->condition !== null) {
            $string .= 'conditional ';
        }

        switch ($this->target->getValue()) {
            case BindingTarget::SELF:
                $string .= sprintf('self-binding of %s', $this->service);
                break;
            case BindingTarget::TYPE:
                $string .= sprintf('binding from %s to %s', $this->service, $this->implementationType);
                break;
            case BindingTarget::PROVIDER:
                $provider = (new \ReflectionFunction($this->providerCallback))->getStaticVariables()['provider'];
                $string .= sprintf('provider binding from %s to %s (via %s)', $this->service, $this->implementationType, get_class($provider));
                break;
            case BindingTarget::METHOD:
                $string .= sprintf('binding from %s to method', $this->service);
                break;
            case BindingTarget::CONSTANT:
                $string .= sprintf('binding from %s to constant value', $this->service);
        }

        return $string;
    }
}