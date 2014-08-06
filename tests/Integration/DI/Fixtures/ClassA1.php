<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;


class ClassA1 implements InterfaceA
{
    public $b;

    public function __construct(InterfaceB $b)
    {
        $this->b = $b;
    }
} 