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
use YAM\UnitTest\Reflection\Fixtures\SampleClass;

class ClassReflectionTest extends \PHPUnit_Framework_TestCase
{
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
        $class = new ClassReflection(SampleClass::class);
        $this->assertInstanceOf(MethodReflection::class, $class->getMethod('fakeMethod'));
    }

    /**
     * @test
     */
    public function getMethodsShouldReturnArrayOfMethodReflection()
    {
        $class = new ClassReflection(SampleClass::class);
        $this->assertContainsOnlyInstancesOf(MethodReflection::class, $class->getMethods());
    }

    /**
     * @test
     */
    public function getMethodsShouldRespectFilterArgument()
    {
        $class = new ClassReflection(SampleClass::class);
        $methods = $class->getMethods(MethodReflection::IS_PRIVATE | MethodReflection::IS_STATIC);
        $this->assertContainsOnlyInstancesOf(MethodReflection::class, $methods);
        $this->assertCount(1, $methods);
        $this->assertEquals('fakeMethod', $methods[0]->name);
    }

    /**
     * @test
     */
    public function getPropertyShouldReturnPropertyReflection()
    {
        $class = new ClassReflection(SampleClass::class);
        $this->assertInstanceOf(PropertyReflection::class, $class->getProperty('testProperty'));
    }

    /**
     * @test
     */
    public function getPropertiesShouldReturnArrayOfPropertyReflection()
    {
        $class = new ClassReflection(SampleClass::class);
        $this->assertContainsOnlyInstancesOf(PropertyReflection::class, $class->getProperties());
    }

    /**
     * @test
     */
    public function getPropertiesShouldRespectFilterArgument()
    {
        $class = new ClassReflection(SampleClass::class);
        $properties = $class->getProperties(PropertyReflection::IS_PROTECTED);
        $this->assertContainsOnlyInstancesOf(PropertyReflection::class, $properties);
        $this->assertCount(1, $properties);
        $this->assertEquals('testProperty3', $properties[0]->name);
    }
}
 