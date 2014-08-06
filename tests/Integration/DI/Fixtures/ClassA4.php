<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;


class ClassA4 implements InterfaceA
{
    /**
     * @Inject
     * @Named("namedB1")
     * @var \YAM\IntegrationTest\DI\Fixtures\InterfaceB
     */
    public $b;
} 