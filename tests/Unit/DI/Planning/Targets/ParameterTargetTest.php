<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning\Targets;


use YAM\DI\Planning\Targets\ParameterTarget;
use YAM\Reflection\MethodReflection;
use YAM\Reflection\ParameterReflection;

class ParameterTargetTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $parameterTarget = new ParameterTarget(
            new MethodReflection(TestClass::class, 'testMethod'),
            new ParameterReflection([new TestClass, 'testMethod'], 'param')
        );
        $parameterTarget2 = new ParameterTarget(
            new MethodReflection(TestClass::class, '__construct'),
            new ParameterReflection([new TestClass, '__construct'], 'param')
        );
        $this->assertEquals('parameter param of method testMethod of type stdClass', (string) $parameterTarget);
        $this->assertEquals('parameter param of constructor of type stdClass', (string) $parameterTarget2);
    }
}

class TestClass
{
    public function __construct(\stdClass $param = null)
    {

    }

    private function testMethod(\stdClass $param)
    {

    }
}
 