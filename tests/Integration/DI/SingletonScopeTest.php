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
use YAM\IntegrationTest\DI\Fixtures\ClassB3;
use YAM\IntegrationTest\DI\Fixtures\InterfaceB;
use YAM\IntegrationTest\DI\Fixtures\ProviderClassB1;

class SingletonScopeTest extends TestCase
{
    /**
     * @test
     */
    public function firstActivatedInstanceIsReusedIfInterfaceBoundService()
    {
        $this->bind(InterfaceB::class)->to(ClassB1::class)->inSingletonScope();

        $instance1 = $this->objectManager->get(InterfaceB::class);
        $instance2 = $this->objectManager->get(InterfaceB::class);

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function singletonAnnotationIsRespectedForInterfaceBoundService()
    {
        $this->bind(InterfaceB::class)->to(ClassB3::class);

        $instance1 = $this->objectManager->get(InterfaceB::class);
        $instance2 = $this->objectManager->get(InterfaceB::class);

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function firstActivatedInstanceIsReusedIfSelfbound()
    {
        $this->bind(ClassB1::class)->toSelf()->inSingletonScope();

        $instance1 = $this->objectManager->get(ClassB1::class);
        $instance2 = $this->objectManager->get(ClassB1::class);

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function singletonAnnotationIsRespectedForSelfboundService()
    {
        $this->bind(ClassB3::class)->toSelf();

        $instance1 = $this->objectManager->get(ClassB3::class);
        $instance2 = $this->objectManager->get(ClassB3::class);

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function firstActivatedInstanceIsReusedIfBoundToProvider()
    {
        $this->bind(InterfaceB::class)->toProvider(new ProviderClassB1())->inSingletonScope();

        $instance1 = $this->objectManager->get(InterfaceB::class);
        $instance2 = $this->objectManager->get(InterfaceB::class);

        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function firstActivatedInstanceIsReusedIfBoundToMethod()
    {
        $this->bind(InterfaceB::class)->toMethod(function() {
            return new ClassB1();
        })->inSingletonScope();

        $instance1 = $this->objectManager->get(InterfaceB::class);
        $instance2 = $this->objectManager->get(InterfaceB::class);

        $this->assertSame($instance1, $instance2);
    }
} 