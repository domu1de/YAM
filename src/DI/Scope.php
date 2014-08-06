<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI;

use YAM\Utility\Enum;

/**
 * Describes the scope of an object.
 *
 * @package YAM\DI
 *
 * @method static \YAM\DI\Scope SINGLETON()
 * @method static \YAM\DI\Scope PROTOTYPE()
 */
final class Scope extends Enum
{
    const SINGLETON = 'singleton';
    const PROTOTYPE = 'prototype';
}