<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\IntegrationTest\DI;

use YAM\DI\Planning\Bindings\Binding;
use YAM\DI\Planning\Bindings\BindingBuilder;
use YAM\DI\StandardObjectManager;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\DI\ObjectManager
     */
    protected $objectManager;

    public function setUp()
    {
        $this->objectManager = new StandardObjectManager();
    }

    protected function bind($service)
    {
        $binding = new Binding($service);
        $this->objectManager->addBinding($binding);

        return new BindingBuilder($binding);
    }
} 