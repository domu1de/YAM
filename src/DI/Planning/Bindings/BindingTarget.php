<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning\Bindings;

use YAM\Utility\Enum;

/**
 * Describes the target of a binding.
 *
 * @package YAM\DI\Bindings
 *
 * @method static \YAM\DI\Planning\Bindings\BindingTarget TYPE()
 * @method static \YAM\DI\Planning\Bindings\BindingTarget PROVIDER()
 * @method static \YAM\DI\Planning\Bindings\BindingTarget CONSTANT()
 * @method static \YAM\DI\Planning\Bindings\BindingTarget SELF()
 * @method static \YAM\DI\Planning\Bindings\BindingTarget METHOD()
 */
class BindingTarget extends Enum
{
    const TYPE = 'type';
    const PROVIDER = 'provider';
    const CONSTANT = 'constant';
    const SELF = 'self';
    const METHOD = 'method';
} 