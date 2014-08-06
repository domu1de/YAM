<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI;


use YAM\IntegrationTest\DI\Fixtures\ClassB1;
use YAM\IntegrationTest\DI\Fixtures\ClassB2;
use YAM\IntegrationTest\DI\Fixtures\InterfaceB;

class StandardObjectManagerTest extends TestCase
{
    #region When StandardObjectManager::get is called for interface-bound service

    /**
     * @test
     */
    public function singleInstanceIsReturnedWhenOneBindingIsRegistered()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class);

        $object = $this->objectManager->get(InterfaceB::class);

        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object);
    }

    /**
     * @test
     * @expectedException \YAM\DI\Exception\PlanningException
     * @expectedExceptionMessage More than one matching bindings are available
     */
    public function planningExceptionThrownWhenMultipleBindingsAreRegistered()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class);
        $this->bind(InterfaceB::class)->to(ClassB2::class);

        $this->objectManager->get(InterfaceB::class);
    }

    #endregion

    #region When StandardObjectManager::get is called for self-bound service

    /**
     * @test
     */
    public function singleInstanceIsReturnedWhenOneSelfBindingIsRegistered()
    {
        $this->bind(ClassB1::class)->toSelf();

        $object = $this->objectManager->get(ClassB1::class);

        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object);
    }

    #endregion

    #region When StandardObjectManager::get is called for unbound service

    /**
     * @test
     */
    public function jitBindingIsRegisteredAndActivated()
    {
        $object = $this->objectManager->get(ClassB1::class);

        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object);
    }

    /**
     * @test
     * @expectedException \YAM\DI\Exception\PlanningException
     * @expectedExceptionMessage No matching bindings are available, and the type is not self-bindable.
     */
    public function throwsExceptionIfUnboundInterfaceIsRequested()
    {
        $this->objectManager->get(InterfaceB::class);
    }

    #endregion

    #region When StandardObjectManager::getAll is called for interface-bound service

    /**
     * @test
     */
    public function arrayOfInstancesIsReturnedWhenOneOrMoreBindingsAreRegistered()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class);
        $this->bind(InterfaceB::class)->to(ClassB2::class);

        $objects = $this->objectManager->getAll(InterfaceB::class);

        $this->assertCount(2, $objects);
        $this->assertInstanceOf(ClassB1::class, $objects[0]);
        $this->assertInstanceOf(ClassB2::class, $objects[1]);
    }

    #endregion

    #region When StandardObjectManager::getAll is called for unbound service

    /**
     * @test
     */
    public function jitBindingsAreRegisteredAndActivated()
    {
        $objects = $this->objectManager->getAll(ClassB1::class);

        $this->assertCount(1, $objects);
        $this->assertInstanceOf(ClassB1::class, $objects[0]);
    }

    /**
     * @test
     */
    public function emptyArrayIsReturnedIfTypeIsNotSelfBindable()
    {
        $object = $this->objectManager->getAll(InterfaceB::class);

        $this->assertCount(0, $object);
    }

    #endregion

    #region StandardObjectManager::tryGet is called

    /**
     * @test
     */
    public function nullIsReturnedForTryGetIfUnresolvable()
    {
        $this->assertNull($this->objectManager->tryGet(InterfaceB::class));
    }

    /**
     * @test
     */
    public function objectIsReturnedForTryGetIfResolvable()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class);

        $object = $this->objectManager->tryGet(InterfaceB::class);
        $this->assertNotNull($object);
        $this->assertInstanceOf(ClassB1::class, $object);
    }

    #endregion
}
 