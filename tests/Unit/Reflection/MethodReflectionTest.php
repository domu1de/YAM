<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Reflection;


use YAM\Reflection\ClassReflection;
use YAM\Reflection\MethodReflection;
use YAM\Reflection\ParameterReflection;

class MethodReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getParametersShouldReturnArrayOfParameterReflection()
    {
        $method = new MethodReflection('\ReflectionMethod', '__construct');
        $this->assertContainsOnlyInstancesOf(ParameterReflection::class, $method->getParameters());
    }

    /**
     * @test
     */
    public function getDeclaringClassShouldReturnClassReflection()
    {
        $method = new MethodReflection(MethodReflection::class, 'getPrototype');
        $this->assertInstanceOf(ClassReflection::class, $method->getDeclaringClass());
    }

    /**
     * @test
     */
    public function getPrototypeShouldReturnMethodReflection()
    {
        $method = new MethodReflection(MethodReflection::class, 'getDeclaringClass');
        $this->assertInstanceOf(MethodReflection::class, $method->getPrototype());
    }
}
