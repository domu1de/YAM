<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI;


use YAM\IntegrationTest\DI\Fixtures\ClassA1;
use YAM\IntegrationTest\DI\Fixtures\ClassA2;
use YAM\IntegrationTest\DI\Fixtures\ClassA3;
use YAM\IntegrationTest\DI\Fixtures\ClassA4;
use YAM\IntegrationTest\DI\Fixtures\ClassA5;
use YAM\IntegrationTest\DI\Fixtures\ClassA6;
use YAM\IntegrationTest\DI\Fixtures\ClassA7;
use YAM\IntegrationTest\DI\Fixtures\ClassB1;
use YAM\IntegrationTest\DI\Fixtures\ClassB2;
use YAM\IntegrationTest\DI\Fixtures\InterfaceA;
use YAM\IntegrationTest\DI\Fixtures\InterfaceB;

class InjectionsTest extends TestCase
{
    #region ConstructorInjections

    /**
     * @test
     */
    public function constructorShouldBeInjected()
    {
        $this->bind(InterfaceA::class)->to(ClassA1::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class);

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    /**
     * @test
     */
    public function namedAnnotationsOnConstructorAreRespected()
    {
        $this->bind(InterfaceA::class)->to(ClassA2::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class)->named('namedB1');
        $this->bind(InterfaceB::class)->to(ClassB2::class)->named('namedB2');

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    #endregion

    #region PropertyInjections

    /**
     * @test
     */
    public function propertiesShouldBeInjected()
    {
        $this->bind(InterfaceA::class)->to(ClassA3::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class);

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    /**
     * @test
     */
    public function namedAnnotationOnPropertyIsRespected()
    {
        $this->bind(InterfaceA::class)->to(ClassA4::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class)->named('namedB1');
        $this->bind(InterfaceB::class)->to(ClassB2::class)->named('namedB2');

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    #endregion

    #region MethodInjections

    /**
     * @test
     */
    public function methodsShouldBeInjected()
    {
        $this->bind(InterfaceA::class)->to(ClassA5::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class);

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    /**
     * @test
     */
    public function namedAnnotationsOnMethodAreRespected()
    {
        $this->bind(InterfaceA::class)->to(ClassA6::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class)->named('namedB1');
        $this->bind(InterfaceB::class)->to(ClassB2::class)->named('namedB2');

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertInstanceOf(ClassB1::class, $object->b);
    }

    #endregion

    #region MultiInjections

    public function testMultiInjections()
    {
        $this->bind(InterfaceA::class)->to(ClassA7::class);
        $this->bind(InterfaceB::class)->to(ClassB1::class);
        $this->bind(InterfaceB::class)->to(ClassB2::class);

        $object = $this->objectManager->get(InterfaceA::class);
        $this->assertCount(2, $object->b1);
        $this->assertCount(2, $object->b2);
        $this->assertCount(2, $object->b3);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b1);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b2);
        $this->assertContainsOnlyInstancesOf(InterfaceB::class, $object->b3);
    }

    #endregion
}



 