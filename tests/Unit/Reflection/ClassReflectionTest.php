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
use YAM\Reflection\PropertyReflection;

class ClassReflectionTest extends \PHPUnit_Framework_TestCase
{
    private $testProperty;

    /**
     * @test
     */
    public function getConstructorShouldReturnMethodReflection()
    {
        $class = new ClassReflection('\ReflectionClass');
        $this->assertInstanceOf(MethodReflection::class, $class->getConstructor());
    }

    /**
     * @test
     */
    public function getInterfacesShouldReturnArrayOfClassReflection()
    {
        $class = new ClassReflection('\SplObjectStorage');
        $this->assertContainsOnlyInstancesOf(ClassReflection::class, $class->getInterfaces());
    }

    /**
     * @test
     */
    public function getParentClassShouldReturnClassReflection()
    {
        $class = new ClassReflection($this);
        $this->assertInstanceOf(ClassReflection::class, $class->getParentClass());

        $class = new ClassReflection('\stdClass');
        $this->assertFalse($class->getParentClass());
    }

    /**
     * @test
     */
    public function getMethodShouldReturnMethodReflection()
    {
        $class = new ClassReflection($this);
        $this->assertInstanceOf(MethodReflection::class, $class->getMethod('getConstructorShouldReturnMethodReflection'));
    }

    /**
     * @test
     * TODO: test filter
     */
    public function getMethodsShouldReturnArrayOfMethodReflection()
    {
        $class = new ClassReflection($this);
        $this->assertContainsOnlyInstancesOf(MethodReflection::class, $class->getMethods());
    }

    /**
     * @test
     */
    public function getPropertyShouldReturnPropertyReflection()
    {
        $class = new ClassReflection($this);
        $this->assertInstanceOf(PropertyReflection::class, $class->getProperty('testProperty'));
    }

    /**
     * @test
     * TODO: test filter
     */
    public function getPropertiesShouldReturnArrayOfPropertyReflection()
    {
        $class = new ClassReflection($this);
        $this->assertContainsOnlyInstancesOf(PropertyReflection::class, $class->getProperties());
    }
}
 