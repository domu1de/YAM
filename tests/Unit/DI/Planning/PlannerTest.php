<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\DI\Planning;


use YAM\DI\Planning\Planner;
use YAM\DI\Planning\Strategies\PlanningStrategy;

class PlannerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\DI\Planning\Planner
     */
    private $planner;

    private $strategy;

    public function setUp()
    {
        $this->strategy = $this->getMock(PlanningStrategy::class);
        $this->planner = new Planner([$this->strategy]);
    }

    public function testGetPlan()
    {
        $plan = $this->planner->getPlan('TestType');
        $this->assertNotNull($plan);
        $this->assertEquals('TestType', $plan->getType());
    }

    public function testGetPlanExecutesStrategies()
    {
        $this->strategy->expects($this->atLeastOnce())->method('execute');
        $this->planner->getPlan('TestType');
    }

    public function testGetPlanIsCached()
    {
        $plan = $this->planner->getPlan('TestType');
        $this->assertNotNull($plan);
        $plan2 = $this->planner->getPlan('TestType');
        $this->assertNotNull($plan2);
        $this->assertSame($plan, $plan2);
    }
}
 