<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2015 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Bindings\Resolvers;


use YAM\DI\Planning\Bindings\Binding;
use YAM\DI\Planning\Bindings\BindingBuilder;
use YAM\Reflection\Type;

/**
 * Resolves bindings by creating just-in-time self-bindings, if possible.
 *
 * @package YAM\DI\Planning\Bindings\Resolvers
 */
class JITBindingResolver implements MissingBindingResolver
{
    /**
     * Returns any bindings from the given collection that match the given request.
     *
     * @param \YAM\DI\Planning\Bindings\Binding[] $bindings Collection of all registered bindings.
     * @param \YAM\DI\Activation\Request $request The request in question
     * @return \YAM\DI\Planning\Bindings\Binding[] Collection of matching bindings
     */
    public function resolve(array $bindings, $request)
    {
        $service = $request->getService();
        if (!$this->isTypeSelfBindable($service)) {
            return [];
        }

        $selfBinding = new Binding($service);
        (new BindingBuilder($selfBinding))->toSelf();

        return [$selfBinding];
    }

    /**
     * Return whether the given service is self-bindable or not.
     *
     * @param string $service The service in question
     * @return boolean
     */
    private function isTypeSelfBindable($service)
    {
        return (new Type($service))->isInstantiable();
    }
}