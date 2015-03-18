<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2015 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Bindings\Resolvers;

/**
 * Determines which bindings to use for a request when other attempts have failed.
 *
 * @package DI\Planning\Bindings\Resolvers
 */
interface MissingBindingResolver
{
    /**
     * Returns any bindings from the given collection that match the given request.
     *
     * @param \YAM\DI\Planning\Bindings\Binding[] $bindings Collection of all registered bindings.
     * @param \YAM\DI\Activation\Request $request The request in question
     * @return \YAM\DI\Planning\Bindings\Binding[] Collection of matching bindings
     */
    public function resolve(array $bindings, $request);
}