<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Activation\Providers;


use YAM\DI\Activation\Context;
use YAM\DI\Activation\Providers\CallbackProvider;

class CallbackProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $mock = $this->getMock('stdClass', ['callback']);
        $mock->expects($this->once())->method('callback')->will($this->returnValue(123));

        $provider = new CallbackProvider([$mock, 'callback']);
        $this->assertEquals(123, $provider->create($this->getMock(Context::class, [], [], '', false)));
    }
}
 