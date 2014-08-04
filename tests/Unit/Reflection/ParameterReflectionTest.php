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
use YAM\UnitTest\Reflection\Fixtures\SampleClass;

class ParameterReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getTypeShouldReturnNullIfNoType()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithoutType'], 'a');
        $this->assertNull($parameter->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldReturnTypeFromTypeHint()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithTypeHint'], 'a');
        $this->assertEquals('stdClass', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass::class, 'methodWithArrayType'], 'a');
        $this->assertEquals('array', $parameter->getType());

        $parameter = new ParameterReflection([SampleClass::class, 'methodWithCallableTypeHint'], 'a');
        $this->assertEquals('callable', $parameter->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldReturnTypeFromPhpDoc()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithPhpDoc'], 'a');
        $this->assertEquals('stdClass', $parameter->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldReturnTypeSpecificArrayTypeIfPossible()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithArrayTypeAndPhpDoc'], 'a');
        $this->assertEquals('stdClass[]', $parameter->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldReturnArrayIfPhpDocIsNotMatchingParam()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithArrayTypeAndUnmatchedPhpDoc'], 'a');
        $this->assertEquals('array', $parameter->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldIgnorePhpDocIfTypeHintIsPresent()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithTypeHintAndPhpDoc'], 'a');
        $this->assertEquals('stdClass', $parameter->getType());
    }

    /**
     * @test
     */
    public function getDeclaringClassShouldReturnClassReflection()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithoutType'], 'a');
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
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithTypeHint'], 'a');
        $this->assertInstanceOf(ClassReflection::class, $parameter->getClass());
    }

    /**
     * @test
     */
    public function getClassShouldReturnNullForNonObjectType()
    {
        $parameter = new ParameterReflection([SampleClass::class, 'methodWithPhpDoc'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass::class, 'methodWithoutType'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass::class, 'methodWithArrayType'], 'a');
        $this->assertNull($parameter->getClass());

        $parameter = new ParameterReflection([SampleClass::class, 'methodWithCallableTypeHint'], 'a');
        $this->assertNull($parameter->getClass());
    }
}

function testFunction($a)
{

}
