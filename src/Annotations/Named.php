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
 * Used to enable named injections.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 * @Attributes({
 *   @Attribute("value", type = "mixed", required = true)
 * })
 */
final class Named
{
    /**
     * Defines which injection to use based on the given name.
     *
     * @var string|string[]
     */
    public $value;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            if (!is_string($values['value']) && !is_array($values['value'])) {
                throw new \InvalidArgumentException(
                    sprintf('@Named expects either a string value, or an array of strings, "%s" given.',
                        is_object($values['value']) ? get_class($values['value']) : gettype($values['value'])
                    )
                );
            }
            $this->value = $values['value'];
        } else {
            throw new \InvalidArgumentException('@Named must be used with a value, "null" given.');
        }
    }
} 