<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testOnLoad()
    {
        /** @var $moduleMock \YAM\DI\Planning\Module|\PHPUnit_Framework_MockObject_MockObject */
        $moduleMock = $this->getMockForAbstractClass('YAM\DI\Planning\Module', [], '', true, true, true, ['load']);
        $moduleMock->expects($this->once())->method('load');

        $objectManager = $this->getMockForAbstractClass('YAM\DI\ObjectManager');
        $moduleMock->onLoad($objectManager);
        $this->assertSame($objectManager, $moduleMock->getObjectManager());
    }

    public function testOnUnload()
    {
        /** @var $moduleMock \YAM\DI\Planning\Module|\PHPUnit_Framework_MockObject_MockObject */
        $moduleMock = $this->getMockForAbstractClass('YAM\DI\Planning\Module', [], '', true, true, true, ['unload']);
        $moduleMock->expects($this->once())->method('unload');

        $moduleMock->onLoad($this->getMockForAbstractClass('YAM\DI\ObjectManager'));
        $moduleMock->onUnload();
        $this->assertNull($moduleMock->getObjectManager());
    }
}