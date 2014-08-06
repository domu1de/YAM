<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning;


use YAM\DI\Planning\Directives\Directive;
use YAM\DI\Planning\Plan;

class PlanTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $plan = new Plan('TestType');
        $this->assertEquals('TestType', $plan->getType());
    }

    public function testGetOne()
    {
        $mock = $this->getMock(Directive::class);
        $mock2 = $this->getMock(Directive::class);

        $plan = new Plan('TestType');
        $plan->add($mock);
        $plan->add($mock2);

        $directive = $plan->getOne(Directive::class);
        $this->assertNotNull($directive);
        $this->assertFalse(is_array($directive));

        $directive = $plan->getOne('UnknownDirectiveType');
        $this->assertNull($directive);
    }

    public function testGetAll()
    {
        $mock = $this->getMock(Directive::class, [], [], 'DirectiveMock1');
        $mock2 = $this->getMock(Directive::class, [], [], 'DirectiveMock2');

        $plan = new Plan('TestType');
        $plan->add($mock);
        $plan->add($mock2);

        $directives = $plan->getAll(Directive::class);
        $this->assertNotNull($directives);
        $this->assertCount(2, $directives);

        $directives = $plan->getAll(get_class($mock));
        $this->assertNotNull($directives);
        $this->assertCount(1, $directives);
        $this->assertSame($mock, $directives[0]);

        $directives = $plan->getAll('UnknownDirectiveType');
        $this->assertEmpty($directives);
    }
}
 