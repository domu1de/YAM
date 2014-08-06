<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;


class ClassA6 implements InterfaceA
{
    public $b;

    /**
     * @Inject
     * @Named({"b":"namedB1"})
     * @param InterfaceB $b
     */
    public function method1(InterfaceB $b)
    {
        $this->b = $b;
    }
}