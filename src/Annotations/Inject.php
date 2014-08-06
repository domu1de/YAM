<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Annotations;

/**
 * Used to enable property and setter injection.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class Inject
{
    /**
     * Whether the dependency should be injected instantly or if a lazy dependency
     * proxy should be injected instead
     *
     * @var boolean
     */
    public $lazy = true;
}