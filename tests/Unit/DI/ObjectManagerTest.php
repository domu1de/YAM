<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI;



use YAM\DI\Planning\Bindings\Binding;

class ObjectManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\DI\ObjectManager
     */
    private $objectManager;

    /**
     * @var \YAM\DI\Planning\Module
     */
    private $module;

    public function setUp()
    {
        $this->objectManager = $this->getMockForAbstractClass('YAM\DI\ObjectManager');
        $this->module = $this->getMockForAbstractClass('YAM\DI\Planning\Module');
    }

    /**
     * @expectedException \YAM\DI\Exception\PlanningException
     * @expectedExceptionMessage Module must be a subtype of
     */
    public function testUnsupportedModuleType()
    {
        $this->objectManager->load([new \stdClass()]);
    }

    public function testHasModuleAndLoadModule()
    {
        $this->assertFalse($this->objectManager->hasModule(get_class($this->module)));
        $this->objectManager->load([$this->module]);
        $this->assertTrue($this->objectManager->hasModule(get_class($this->module)));
    }

    /**
     * @expectedException \YAM\DI\Exception\PlanningException
     * @expectedExceptionMessage another module with the same name has already been loaded
     */
    public function testModuleAlreadyLoaded()
    {
        $this->objectManager->load([$this->module]);
        $this->objectManager->load([$this->module]);
    }

    public function testUnloadModule()
    {
        $this->objectManager->load([$this->module]);
        $this->assertTrue($this->objectManager->hasModule(get_class($this->module)));
        $this->objectManager->unload(get_class($this->module));
        $this->assertFalse($this->objectManager->hasModule(get_class($this->module)));
    }

    /**
     * @expectedException \YAM\DI\Exception\PlanningException
     * @expectedExceptionMessage no such module has been loaded
     */
    public function testUnloadUnknownModule()
    {
        $this->objectManager->unload(get_class($this->module));
    }

    public function testRemoveBinding()
    {
        $binding = new Binding('stdClass');
        $this->objectManager->addBinding($binding);
        $binding2 = new Binding('stdClass');
        $this->objectManager->addBinding($binding2);

        $bindings = $this->readAttribute($this->objectManager, 'bindings');
        $this->assertContains($binding, $bindings['stdClass']);
        $this->assertContains($binding2, $bindings['stdClass']);

        $this->objectManager->removeBinding($binding);
        $bindings = $this->readAttribute($this->objectManager, 'bindings');
        $this->assertNotContains($binding, $bindings['stdClass']);
        $this->assertContains($binding2, $bindings['stdClass']);
    }

    public function testRemoveAllBindings()
    {
        $binding = new Binding('stdClass');
        $this->objectManager->addBinding($binding);
        $binding = new Binding('stdClass');
        $this->objectManager->addBinding($binding);

        $bindings = $this->readAttribute($this->objectManager, 'bindings');
        $this->assertCount(2, $bindings['stdClass']);

        $this->objectManager->removeAllBindings('stdClass');
        $bindings = $this->readAttribute($this->objectManager, 'bindings');
        $this->assertArrayNotHasKey('stdClass', $bindings);
    }
}
 