<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning;

/**
 * Generates plans for how to activate instances.
 *
 * @package YAM\DI\Planning
 */
class Planner
{
    /**
     * Strategies that contribute to the planning process.
     *
     * @var \YAM\DI\Planning\Strategies\PlanningStrategy[]
     */
    private $strategies;

    /**
     * @var \YAM\DI\Planning\Plan
     */
    private $plans = [];

    /**
     * @param \YAM\DI\Planning\Strategies\PlanningStrategy[] $planningStrategies
     */
    public function __construct(array $planningStrategies)
    {
        $this->strategies = $planningStrategies;
    }

    /**
     * Gets or creates an activation plan for the specified type.
     *
     * @param string $type
     * @return \YAM\DI\Planning\Plan
     */
    public function getPlan($type)
    {
        return isset($this->plans[$type]) ? $this->plans[$type] : $this->createNewPlan($type);
    }

    /**
     * Creates a new plan for the specified type.
     *
     * @param string $type
     * @return \YAM\DI\Planning\Plan
     */
    private function createNewPlan($type)
    {
        $plan = new Plan($type);
        $this->plans[$type] = $plan;

        foreach ($this->strategies as $strategy) {
            $strategy->execute($plan);
        }

        return $plan;
    }
}