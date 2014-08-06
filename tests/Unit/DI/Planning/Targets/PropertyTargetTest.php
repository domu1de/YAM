<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning\Targets;


use YAM\DI\Planning\Targets\PropertyTarget;
use YAM\Reflection\PropertyReflection;

class PropertyTargetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \stdClass
     */
    private $testProperty;

    private function getPropertyTarget()
    {
        return new PropertyTarget(new PropertyReflection(PropertyTargetTest::class, 'testProperty'));
    }

    public function testToString()
    {
        $propertyTarget = $this->getPropertyTarget();
        $this->assertEquals('property testProperty of type stdClass', (string) $propertyTarget);
    }
}
 