<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Planning;


class Plan
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var \YAM\DI\Planning\Directives\Directive[]
     */
    private $directives = [];

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Adds the specified directive to the plan.
     *
     * @param \YAM\DI\Planning\Directives\Directive $directive
     */
    public function add($directive)
    {
        $this->directives[] = $directive;
    }

    /**
     * Gets the first directive of the specified type from the plan.
     *
     * @param string $directiveType
     * @return \YAM\DI\Planning\Directives\Directive
     */
    public function getOne($directiveType)
    {
        $result = $this->getAll($directiveType);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Gets all directives of the specified type that exist in the plan.
     *
     * @param string $directiveType
     * @return \YAM\DI\Planning\Directives\Directive[]
     */
    public function getAll($directiveType)
    {
        return array_filter($this->directives, function($directive) use ($directiveType) {
            return $directive instanceof $directiveType;
        });
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
} 