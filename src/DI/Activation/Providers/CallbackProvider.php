<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Activation\Providers;


class CallbackProvider implements Provider
{
    /**
     * @var callable
     */
    private $method;

    public function __construct(callable $callback)
    {
        $this->method = $callback;
    }

    public function create(\YAM\DI\Activation\Context $context)
    {
        return call_user_func($this->method, $context);
    }
} 