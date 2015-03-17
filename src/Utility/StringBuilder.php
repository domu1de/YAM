<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Utility;

/**
 * A mutable sequence of characters.
 *
 * @package YAM\Utility
 */
class StringBuilder
{
    /**
     * @var string
     */
    private $internalString = '';

    /**
     * Appends the (formatted) string to the sequence.
     *
     * @param string $string
     * @param mixed ...$args
     * @return $this
     * @api
     */
    public function append($string, ...$args)
    {
        if (!empty($args)) {
            $string = vsprintf($string, $args);
        }
        $this->internalString .= (string) $string;
        return $this;
    }

    /**
     * Appends the (formatted) string followed by a line terminator to the sequence.
     *
     * @param string $string
     * @param mixed ...$args
     * @return $this
     * @api
     */
    public function appendLine($string = '', ...$args)
    {
        if (!empty($args)) {
            $string = vsprintf($string, $args);
        }
        $this->internalString .= (string) $string . PHP_EOL;
        return $this;
    }

    /**
     * Converts the value of this instance to a string.
     *
     * @return string
     * @api
     */
    public function __toString()
    {
        return $this->internalString;
    }

    /**
     * Removes all characters from the current StringBuilder instance.
     *
     * @return $this
     * @api
     */
    public function clear()
    {
        $this->internalString = '';
        return $this;
    }
} 