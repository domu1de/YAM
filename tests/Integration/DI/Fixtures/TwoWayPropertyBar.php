<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;

class TwoWayPropertyBar
{
    /**
     * @Inject
     * @var \YAM\IntegrationTest\DI\Fixtures\TwoWayPropertyFoo
     */
    public $foo;
} 