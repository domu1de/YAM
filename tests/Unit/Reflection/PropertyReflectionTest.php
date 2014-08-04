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
use YAM\Reflection\PropertyReflection;

class PropertyReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \stdClass
     */
    private $propertyWithType;

    private $propertyWithoutType;

    /**
     * @test
     */
    public function getTypeShouldReturnTypeIfTypeIsPresent()
    {
        $property = new PropertyReflection(self::class, 'propertyWithType');
        $this->assertEquals('stdClass', $property->getType());
    }

    /**
     * @test
     */
    public function getTypeShouldReturnNullIfNoType()
    {
        $property = new PropertyReflection(self::class, 'propertyWithoutType');
        $this->assertNull($property->getType());
    }

    /**
     * @test
     */
    public function getDeclaringClassShouldReturnClassReflection()
    {
        $property = new PropertyReflection(PropertyReflection::class, 'name');
        $this->assertEquals(\ReflectionProperty::class, $property->getDeclaringClass()->name);
        $this->assertInstanceOf(ClassReflection::class, $property->getDeclaringClass());
    }
}
