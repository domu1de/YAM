<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Strategies;


/**
 * Contributes to the generation of a plan.
 *
 * @package YAM\DI\Planning\Strategies
 */
interface PlanningStrategy
{
    /**
     * Contributes to the specified plan.
     *
     * @param \YAM\DI\Planning\Plan $plan
     */
    public function execute($plan);
} 