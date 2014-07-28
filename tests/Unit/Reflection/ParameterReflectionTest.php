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
use YAM\Reflection\ParameterReflection;

class ParameterReflectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithoutType'], 'a');
        $this->assertNull($parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithTypeHint'], 'a');
        $this->assertEquals('stdClass', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithPhpDoc'], 'a');
        $this->assertEquals('stdClass', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithArrayType'], 'a');
        $this->assertEquals('array', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithArrayTypeAndPhpDoc'], 'a');
        $this->assertEquals('stdClass[]', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithArrayTypeAndUnmatchedPhpDoc'], 'a');
        $this->assertEquals('array', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithCallableTypeHint'], 'a');
        $this->assertEquals('callable', $parameter->getType());
    }

    /**
     * @test
     */
    public function getDeclaringClassShouldReturnClassReflection()
    {
        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithoutType'], 'a');
        $this->assertInstanceOf(ClassReflection::class, $parameter->getDeclaringClass());
    }

    /**
     * @test
     */
    public function getDeclaringClassShouldReturnNullIfFunctionIsOutsideAClass()
    {
        $parameter = new ParameterReflection('YAM\UnitTest\Reflection\testFunction', 'a');
        $this->assertNull($parameter->getDeclaringClass());
    }

    /**
     * @test
     */
    public function getClassShouldReturnClassReflection()
    {
        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithTypeHint'], 'a');
        $this->assertInstanceOf(ClassReflection::class, $parameter->getClass());
    }

    /**
     * @test
     */
    public function getClassShouldReturnNullForNonObjectType()
    {
        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithPhpDoc'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithoutType'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithArrayType'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass2::class, 'methodWithCallableTypeHint'], 'a');
        $this->assertNull($parameter->getClass());
    }
}

class SampleClass2
{
    public function methodWithTypeHint(\stdClass $a)
    {
    }

    /**
     * @param \stdClass $a
     */
    public function methodWithPhpDoc($a)
    {
    }

    public function methodWithoutType($a)
    {

    }

    public function methodWithArrayType(array $a)
    {

    }

    /**
     * @param \stdClass[] $a
     */
    public function methodWithArrayTypeAndPhpDoc(array $a)
    {

    }

    /**
     * @param int $b
     */
    public function methodWithArrayTypeAndUnmatchedPhpDoc(array $a, $b)
    {

    }

    public function methodWithCallableTypeHint(callable $a)
    {

    }
}

function testFunction($a)
{

}
