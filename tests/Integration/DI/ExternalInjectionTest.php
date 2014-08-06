<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI;

use YAM\IntegrationTest\DI\Fixtures\ClassA7;
use YAM\IntegrationTest\DI\Fixtures\ClassB1;
use YAM\IntegrationTest\DI\Fixtures\ClassB2;
use YAM\IntegrationTest\DI\Fixtures\InterfaceB;

class ExternalInjectionTest extends TestCase
{
    /**
     * @test
     */
    public function injectionsShouldBePerformedWhenInjectIsCalled()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class);
        $this->bind(InterfaceB::class)->to(ClassB2::class);

        $object = new ClassA7([new ClassB1()]);
        $this->objectManager->inject($object);

        $this->assertCount(1, $object->b1);
        $this->assertCount(2, $object->b2);
        $this->assertCount(2, $object->b3);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b1);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b2);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b3);
    }
}
 