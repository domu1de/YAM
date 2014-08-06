<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI;


use YAM\DI\ComponentContainer;

class ComponentContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\DI\ComponentContainer
     */
    private $components;

    public function setUp()
    {
        $this->components = new ComponentContainer($this->getMockForAbstractClass('YAM\DI\ObjectManager'));
    }

    public function testAddWithInstance()
    {
        $c = new C();
        $this->components->add(C::class, C::class, $c);
        $this->assertSame($c, $this->components->get(C::class));
    }

    public function testGet()
    {
        $this->components->add(A::class, A::class);
        $this->components->add(B::class, B::class);
        $this->components->add(C::class, C::class);
        $this->assertInstanceOf(A::class, $this->components->get(A::class));
    }

    public function testGetFirst()
    {
        $this->components->add(B::class, B::class);
        $this->components->add(B::class, C::class);
        $this->components->add(C::class, C::class);
        $this->assertInstanceOf(B::class, $this->components->get(B::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such component has been registered
     */
    public function testGetNoSuchComponent()
    {
        $this->components->get('UnknownComponent');
    }

    public function testGetAll()
    {
        $this->components->add(C::class, C::class);
        $this->components->add(C::class, C::class);
        $this->components->add(C::class, C::class);
        $this->components->add(D::class, D::class);

        $this->assertCount(3, $this->components->getAll(C::class));
        $this->assertNotNull($this->components->get(D::class)); // internal getAll
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such component has been registered
     */
    public function testGetAllNoSuchComponent()
    {
        $this->components->getAll('UnknownComponent');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such component has been registered
     */
    public function testRemove()
    {
        $this->components->add(C::class, C::class);
        $c = $this->components->get(C::class);
        $this->assertNotNull($c);

        $this->components->remove(C::class, C::class);
        $this->components->get(C::class);
    }

    public function testInstanceRemoved()
    {
        $this->components->add(C::class, C::class);
        $c = $this->components->get(C::class);
        $this->assertNotNull($c);

        $this->components->remove(C::class, C::class);
        $this->components->add(C::class, C::class);

        $this->assertNotSame($c, $this->components->get(C::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such component has been registered
     */
    public function testRemoveAll()
    {
        $this->components->add(C::class, C::class);
        $this->components->add(C::class, C::class);
        $c = $this->components->get(C::class);
        $this->assertNotNull($c);

        $this->components->removeAll(C::class);
        $this->components->get(C::class);
    }

    public function testAllInstancesRemoved()
    {
        $this->components->add(C::class, C::class);
        $this->components->add(C::class, C::class);
        $c = $this->components->get(C::class);
        $this->assertNotNull($c);

        $this->components->remove(C::class, C::class);
        $this->components->add(C::class, C::class);

        $this->assertNotSame($c, $this->components->get(C::class));
    }

}

class A
{
    public function __construct(B $b)
    {

    }
}

class B
{
    public function __construct(C $b)
    {

    }
}

class C
{
}

class D
{
    /**
     * @param \YAM\UnitTest\DI\C[] $c
     */
    public function __construct(array $c)
    {

    }
}