<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning\Strategies;


use YAM\DI\Planning\Directives\ConstructorInjectionDirective;
use YAM\DI\Planning\Plan;
use YAM\DI\Planning\Strategies\ConstructorReflectionStrategy;
use YAM\Reflection\ReflectionService;

class ConstructorReflectionStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteWithConstructor()
    {
        $plan = new Plan(A::class);
        $strategy = new ConstructorReflectionStrategy($this->getReflectionServiceMock());
        $strategy->execute($plan);

        $directive = $plan->getOne(ConstructorInjectionDirective::class);
        $this->assertNotNull($directive);
        $this->assertCount(1, $directive->getTargets());
        $this->assertEquals('stdClass', $directive->getTargets()[0]->getType());
        $this->assertNotNull($directive->getInjector());
    }

    public function testExecuteWithNonPublicConstructor()
    {
        $plan = new Plan(B::class);
        $strategy = new ConstructorReflectionStrategy($this->getReflectionServiceMock());
        $strategy->execute($plan);

        $directive = $plan->getOne(ConstructorInjectionDirective::class);
        $this->assertNull($directive);
    }

    public function testExecuteWithoutConstructor()
    {
        $plan = new Plan('stdClass');
        $strategy = new ConstructorReflectionStrategy($this->getReflectionServiceMock());
        $strategy->execute($plan);

        $directive = $plan->getOne(ConstructorInjectionDirective::class);
        $this->assertNull($directive);
    }

    private function getReflectionServiceMock()
    {
        return $this->getMock(ReflectionService::class, [], [], '', false);
    }
}

class A
{
    public function __construct(\stdClass $a)
    {

    }
}

class B
{
    private function __construct()
    {

    }
}
 