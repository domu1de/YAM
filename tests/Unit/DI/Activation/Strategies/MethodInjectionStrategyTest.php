<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Activation\Strategies;


use YAM\DI\Activation\Context;
use YAM\DI\Activation\Strategies\MethodInjectionStrategy;
use YAM\DI\Planning\Directives\MethodInjectionDirective;
use YAM\DI\Planning\Plan;
use YAM\DI\Planning\Targets\Target;
use YAM\Reflection\MethodReflection;

class MethodInjectionStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\DI\Activation\Strategies\ActivationStrategy
     */
    private $strategy;

    private $context;

    private $injector1WasCalled = false;
    private $injector2WasCalled = false;

    private $targets = [];

    public function setUp()
    {
        $this->targets = [
            $this->getMock(Target::class, [], [], '', false),
            $this->getMock(Target::class, [], [], '', false)
        ];

        $directives = [
            new MethodInjectionDirective(new MethodReflection(self::class, 'setUp'), function() {
                $this->injector1WasCalled = true;
            }, [$this->targets[0]]),
            new MethodInjectionDirective(new MethodReflection(self::class, 'setUp'),  function() {
                $this->injector2WasCalled = true;
            }, [$this->targets[1]])
        ];

        $plan = $this->getMock(Plan::class, [], [], '', false);
        $plan->expects($this->any())
             ->method('getAll')
             ->with($this->equalTo(MethodInjectionDirective::class))
             ->will($this->returnValue($directives));

        $this->context = $this->getMock(Context::class, [], [], '', false);
        $this->context->expects($this->any())
                      ->method('getPlan')
                      ->will($this->returnValue($plan));

        $this->strategy = new MethodInjectionStrategy();
    }

    public function testInvokesInjectorsForEachDirective()
    {
        $this->strategy->activate($this->context, new \stdClass);
        $this->assertTrue($this->injector1WasCalled, 'Injector1 should have been called.');
        $this->assertTrue($this->injector2WasCalled, 'Injector2 should have been called.');
    }

    public function testResolvesValuesForEachTargetOfEachDirective()
    {
        foreach ($this->targets as $target) {
            $target->expects($this->atLeastOnce())->method('resolveWithIn');
        }
        $this->strategy->activate($this->context, new \stdClass);
    }
}
 