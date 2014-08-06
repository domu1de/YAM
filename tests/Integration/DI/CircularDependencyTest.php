<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI;


use YAM\IntegrationTest\DI\Fixtures\TwoWayConstructorBar;
use YAM\IntegrationTest\DI\Fixtures\TwoWayConstructorFoo;
use YAM\IntegrationTest\DI\Fixtures\TwoWayMethodBar;
use YAM\IntegrationTest\DI\Fixtures\TwoWayMethodFoo;
use YAM\IntegrationTest\DI\Fixtures\TwoWayPropertyBar;
use YAM\IntegrationTest\DI\Fixtures\TwoWayPropertyFoo;

class CircularDependencyTest extends TestCase
{
    /**
     * @test
     * @expectedException \YAM\DI\Exception\ActivationException
     */
    public function twoWayDependencyBetweenConstructorsShouldThrowException()
    {
        $this->bind(TwoWayConstructorBar::class)->toSelf();
        $this->bind(TwoWayConstructorFoo::class)->toSelf();

        $this->objectManager->get(TwoWayConstructorFoo::class);
    }

    /**
     * @test
     * @expectedException \YAM\DI\Exception\ActivationException
     */
    public function twoWayDependencyBetweenPropertiesShouldThrowException()
    {
        $this->bind(TwoWayPropertyFoo::class)->toSelf();
        $this->bind(TwoWayPropertyBar::class)->toSelf();

        $this->objectManager->get(TwoWayPropertyFoo::class);
    }

    /**
     * @test
     * @expectedException \YAM\DI\Exception\ActivationException
     */
    public function twoWayDependencyBetweenMethodsShouldThrowException()
    {
        $this->bind(TwoWayMethodFoo::class)->toSelf();
        $this->bind(TwoWayMethodBar::class)->toSelf();

        $this->objectManager->get(TwoWayMethodFoo::class);
    }
}
 