<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning\Strategies;


use YAM\DI\Planning\Directives\MethodInjectionDirective;
use YAM\DI\Planning\Plan;
use YAM\DI\Planning\Strategies\MethodReflectionStrategy;
use YAM\Reflection\ReflectionService;

class MethodReflectionStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $plan = new Plan(C::class);
        $strategy = new MethodReflectionStrategy($this->getReflectionServiceMock());
        $strategy->execute($plan);

        $directives = $plan->getAll(MethodInjectionDirective::class);
        $this->assertCount(2, $directives);

        $this->assertCount(1, $directives[0]->getTargets());
        $this->assertEquals('stdClass', $directives[0]->getTargets()[0]->getType());
        $this->assertNotNull($directives[0]->getInjector());

        $this->assertEmpty($directives[1]->getTargets());
        $this->assertNotNull($directives[1]->getInjector());
    }

    private function getReflectionServiceMock()
    {
        $mock = $this->getMock(ReflectionService::class, ['isMethodAnnotatedWith', 'getMethodAnnotation'], [], '', false);
        $mock->expects($this->any())->method('isMethodAnnotatedWith')->willReturn(true);

        return $mock;
    }
}

class C
{
    /**
     * @Inject
     * @param \stdClass $a
     */
    public function testMethod(\stdClass $a)
    {

    }

    /**
     * @Inject
     */
    public function testMethod2()
    {

    }
}
 