<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning\Bindings;


use YAM\DI\Activation\Context;
use YAM\DI\Activation\Request;
use YAM\DI\Planning\Bindings\Binding;
use YAM\DI\Planning\Bindings\BindingTarget;

class BindingTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $binding = new Binding('stdClass');

        $this->assertTrue($binding->matches($this->getRequestMock()));

        $binding->setCondition(function() { return false; });

        $this->assertFalse($binding->matches($this->getRequestMock()));

        $binding->setCondition(function() { return true; });

        $this->assertTrue($binding->matches($this->getRequestMock()));
    }

    public function testGetProvider()
    {
        $binding = new Binding('stdClass');
        $callback = $this->getMock('stdClass', ['testMethod']);
        $callback->expects($this->once())->method('testMethod')->willReturn(10);
        $binding->setProviderCallback([$callback, 'testMethod']);
        $this->assertEquals(10, $binding->getProvider($this->getMock(Context::class, [], [], '', false)));
    }

    public function testToString()
    {
        $binding = new Binding('stdClass');
        $binding->setImplementationType('stdClass2');

        $binding->setTarget(BindingTarget::TYPE());
        $this->assertEquals('binding from stdClass to stdClass2', (string) $binding);

        $binding->setTarget(BindingTarget::SELF());
        $this->assertEquals('self-binding of stdClass', (string) $binding);

        $binding->setTarget(BindingTarget::METHOD());
        $this->assertEquals('binding from stdClass to method', (string) $binding);

        $binding->setTarget(BindingTarget::CONSTANT());
        $this->assertEquals('binding from stdClass to constant value', (string) $binding);

        $binding->setTarget(BindingTarget::PROVIDER());
        $provider = function() {};
        $binding->setProviderCallback(function() use ($provider) {
            return $provider;
        });
        $this->assertEquals(sprintf('provider binding from stdClass to stdClass2 (via %s)', \Closure::class), (string) $binding);

        $binding->setCondition(function(){});
        $this->assertStringStartsWith('conditional ', (string) $binding);
    }

    /**
     * @return \YAM\DI\Activation\Request
     */
    private function getRequestMock()
    {
        return $this->getMock(Request::class, [], [], '', false);
    }
}
 