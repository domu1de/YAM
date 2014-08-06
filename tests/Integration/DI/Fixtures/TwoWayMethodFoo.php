<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;

class TwoWayMethodFoo
{
    /**
     * @Inject
     * @param \YAM\IntegrationTest\DI\Fixtures\TwoWayMethodBar $bar
     */
    public function setBar($bar)
    {

    }
} 