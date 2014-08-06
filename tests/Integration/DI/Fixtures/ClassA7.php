<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI\Fixtures;


class ClassA7 implements InterfaceA
{
    /**
     * @var \YAM\IntegrationTest\DI\Fixtures\InterfaceB[]
     */
    public $b1;

    /**
     * @Inject
     * @var \YAM\IntegrationTest\DI\Fixtures\InterfaceB[]
     */
    public $b2;

    /**
     * @var \YAM\IntegrationTest\DI\Fixtures\InterfaceB[]
     */
    public $b3;

    /**
     * @param \YAM\IntegrationTest\DI\Fixtures\InterfaceB[] $bs
     */
    public function __construct(array $bs)
    {
        $this->b1 = $bs;
    }

    /**
     * @Inject
     * @param \YAM\IntegrationTest\DI\Fixtures\InterfaceB[] $bs
     */
    public function addMoreWeapons(array $bs)
    {
        $this->b3 = $bs;
    }
} 