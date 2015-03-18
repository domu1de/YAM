<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2015 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Reflection;


use YAM\Reflection\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTypes
     */
    public function testConstructor($typeToTest)
    {
        $type = new Type($typeToTest);
        $this->assertSame($typeToTest, (string) $type);
    }

    /**
     * @dataProvider provideArrayTypes
     */
    public function testIsArray($typeToTest, $result)
    {
        $type = new Type($typeToTest);
        $this->assertEquals($result, $type->isArray(), 'Type: ' . $typeToTest);
    }

    /**
     * @dataProvider provideScalarTypes
     */
    public function testIsScalar($typeToTest, $result)
    {
        $type = new Type($typeToTest);
        $this->assertEquals($result, $type->isScalar(), 'Type: ' . $typeToTest);
    }

    public function testIsClass()
    {
        $type = new Type(\stdClass::class);
        $this->assertTrue($type->isClass());

        $type = new Type('array');
        $this->assertFalse($type->isClass());

        $type = new Type(\Countable::class);
        $this->assertFalse($type->isClass());
    }

    public function testIsInterface()
    {
        $type = new Type(\Countable::class);
        $this->assertTrue($type->isInterface());

        $type = new Type(\stdClass::class);
        $this->assertFalse($type->isInterface());

        $type = new Type('array');
        $this->assertFalse($type->isInterface());
    }

    /**
     * @dataProvider provideEqualsTypes
     */
    public function testEquals($type1, $type2, $result)
    {
        $this->assertEquals($result, (new Type($type1))->equals(new Type($type2)), 'Type1: ' . $type1 . ', Type2:' . $type2);
        $this->assertEquals($result, (new Type($type2))->equals(new Type($type1)), 'Type2: ' . $type2 . ', Type1:' . $type1);
    }

    /**
     * @dataProvider provideElementTypes
     */
    public function testGetElementType($typeToTest, $result)
    {
        $type = new Type($typeToTest);
        $this->assertEquals($result, $type->getElementType(), 'Type: ' . $typeToTest);
    }

    public function provideTypes()
    {
        return [
            ['array'],
            ['string[]'],
            ['bool[]'],
            ['object'],
            ['stdClass'],
            ['Countable'],
        ];
    }

    public function provideArrayTypes()
    {
        return [
            ['array', true],
            ['string[]', true],
            ['bool[]', true],
            ['object', false]
        ];
    }

    public function provideScalarTypes()
    {
        return [
            ['bool', true],
            ['boolean', true],
            ['string', true],
            ['int', true],
            ['integer', true],
            ['float', true],
            ['double', true],
            ['bool[]', false],
            ['object', false],
            ['stdClass', false]
        ];
    }

    public function provideElementTypes()
    {
        return [
            ['array', null],
            ['string[]', new Type('string')],
            ['bool[]', new Type('bool')],
            ['object', null],
            ['stdClass[]', new Type('stdClass')],
        ];
    }

    public function provideEqualsTypes()
    {
        return [
            ['array', 'array', true],
            ['string[]', 'string[]', true],
            ['bool[]', 'string[]', false],
            ['object', 'int', false],
            ['stdClass', 'stdClass', true],
            ['Countable', 'stdClass', false],
        ];
    }
}
