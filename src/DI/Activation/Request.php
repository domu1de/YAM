<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation;

/**
 * Describes the request for a service resolution.
 *
 * @package YAM\DI
 */
class Request
{
    /**
     * The service that was requested.
     *
     * @var string
     */
    private $service;

    /**
     * The constraint that is used to filter bindings.
     *
     * @var callable|null
     */
    private $constraint;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Indicates whether a single service is requested.
     *
     * @var boolean
     */
    private $isUnique;

    /**
     * Indicated whether the request is optional.
     *
     * @var boolean
     */
    private $isOptional;

    /**
     * Recursion depth the request occurs at.
     *
     * @var int
     */
    private $depth;

    /**
     * @var \SplObjectStorage
     */
    private $activeBindings;

    /**
     * @var \YAM\DI\Activation\Request
     */
    private $parentRequest;

    /**
     * @var \YAM\DI\Activation\Context
     */
    private $parentContext;

    /**
     * @var \YAM\DI\Planning\Targets\Target
     */
    private $target;

    /**
     * @param string $service
     * @param callable|null $constraint
     * @param array $parameters
     * @param boolean $isUnique
     * @param boolean $isOptional
     */
    public function __construct($service, $constraint, array $parameters, $isUnique, $isOptional)
    {
        $this->service = $service;
        $this->constraint = $constraint;
        $this->parameters = $parameters;
        $this->isUnique = $isUnique;
        $this->isOptional = $isOptional;

        $this->depth = 0;

        $this->activeBindings = new \SplObjectStorage;
    }

    /**
     * Determines whether the given binding satisfies the request's constraints.
     *
     * @param \YAM\DI\Planning\Bindings\Binding $binding
     * @return boolean
     */
    public function matches(\YAM\DI\Planning\Bindings\Binding $binding)
    {
        $constraint = $this->constraint;
        return $this->constraint === null || $constraint($binding);
    }

    /**
     * Creates a child request.
     *
     * @param string $service
     * @param \YAM\DI\Activation\Context $parentContext
     * @param \YAM\DI\Planning\Targets\Target $target
     * @param boolean $isUnique
     * @param boolean $isOptional
     * @return \YAM\DI\Activation\Request
     */
    public function createChild($service, $parentContext, $target, $isUnique, $isOptional)
    {
        $request = new Request($service, null, [], $isUnique, $isOptional);

        $request->depth++;
        $request->parentContext = $parentContext;
        $request->parentRequest = $this;
        $request->activeBindings = $this->activeBindings;
        $request->target = $target;
        $request->constraint = $target->getConstraint();

        return $request;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->target === null) {
            return 'Request for ' . $this->service;
        } else {
            return 'Injection of dependency ' . $this->service . ' into ' . $this->target;
        }
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return boolean
     */
    public function isUnique()
    {
        return $this->isUnique;
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        return $this->isOptional;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getActiveBindings()
    {
        return $this->activeBindings;
    }

    /**
     * @return Request
     */
    public function getParentRequest()
    {
        return $this->parentRequest;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return \YAM\DI\Planning\Targets\Target
     */
    public function getTarget()
    {
        return $this->target;
    }
} 