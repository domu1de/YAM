<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Reflection;

trait DocCommentTrait
{
    /**
     * Holds an instance of the doc comment parser for this class
     * @var \YAM\Reflection\DocCommentParser
     */
    protected $docCommentParser;

    /**
     * Checks if the doc comment of this method is tagged with
     * the specified tag
     *
     * @param string $tag Tag name to check for
     * @return boolean TRUE if such a tag has been defined, otherwise FALSE
     */
    public function isTaggedWith($tag)
    {
        return $this->getDocCommentParser()->isTaggedWith($tag);
    }

    /**
     * Returns an array of tags and their values
     *
     * @return array Tags and values
     */
    public function getTags()
    {
        return $this->getDocCommentParser()->getTags();
    }

    /**
     * Returns the values of the specified tag
     *
     * @param string $tag The tag to filter for
     * @return array Values of the given tag
     */
    public function getTagValues($tag)
    {
        return $this->getDocCommentParser()->getTagValues($tag);
    }

    /**
     * Returns the description part of the doc comment
     *
     * @return string Doc comment description
     */
    public function getDescription()
    {
        return $this->getDocCommentParser()->getDescription();
    }

    /**
     * Returns an instance of the doc comment parser and
     * runs the parseDocComment() method.
     *
     * @return \YAM\Reflection\DocCommentParser
     */
    protected function getDocCommentParser()
    {
        if ($this->docCommentParser === null) {
            $this->docCommentParser = new \YAM\Reflection\DocCommentParser;
            $this->docCommentParser->parseDocComment($this->getDocComment());
        }
        return $this->docCommentParser;
    }
}