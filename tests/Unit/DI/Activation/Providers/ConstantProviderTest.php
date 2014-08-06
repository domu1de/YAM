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
use YAM\DI\Activation\Providers\ConstantProvider;

class ConstantProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $object = new \stdClass();
        $provider = new ConstantProvider($object);
        $this->assertSame($object, $provider->create($this->getMock(Context::class, [], [], '', false)));
    }
}
 