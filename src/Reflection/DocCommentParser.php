<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Reflection;

/**
 * A little parser which creates tag objects from doc comments
 */
class DocCommentParser
{
    /**
     * The description as found in the doc comment
     * @var string
     */
    protected $description = '';

    /**
     * An array of tag names and their values (multiple values are possible)
     * @var array
     */
    protected $tags = [];

    /**
     * Parses the given doc comment and saves the result (description and
     * tags) in the parser's object. They can be retrieved by the
     * getTags() getTagValues() and getDescription() methods.
     *
     * @param string $docComment A doc comment as returned by the reflection getDocComment() method
     * @return void
     */
    public function parseDocComment($docComment)
    {
        $this->description = '';
        $this->tags = [];

        // Strips the asterisks from the DocBlock comment
        $docComment = trim(preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]{0,1}(.*)?#u', '$1', $docComment));

        // The regex above is not able to remove */ from a single line DocBlock
        if (substr($docComment, -2) === '*/') {
            $docComment = trim(substr($docComment, 0, -2));
        }

        $lines = explode(chr(10), $docComment);
        foreach ($lines as $line) {
            $line = trim($line);
            if (isset($line[0]) && $line[0] === '@') {
                $this->parseTag($line);
            } else {
                if (count($this->tags) === 0) {
                    $this->description .= $line . chr(10);
                }
            }
        }
        $this->description = trim($this->description);
    }

    /**
     * Returns the tags which have been previously parsed
     *
     * @return array Array of tag names and their (multiple) values
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Returns the values of the specified tag. The doc comment
     * must be parsed with parseDocComment() before tags are
     * available.
     *
     * @param string $tagName The tag name to retrieve the values for
     * @return array The tag's values
     * @throws \OutOfBoundsException
     */
    public function getTagValues($tagName)
    {
        if (!$this->isTaggedWith($tagName)) {
            throw new \OutOfBoundsException('Tag "' . $tagName . '" does not exist.');
        }
        return $this->tags[$tagName];
    }

    /**
     * Checks if a tag with the given name exists
     *
     * @param string $tagName The tag name to check for
     * @return boolean TRUE the tag exists, otherwise FALSE
     */
    public function isTaggedWith($tagName)
    {
        return (isset($this->tags[$tagName]));
    }

    /**
     * Returns the description which has been previously parsed
     *
     * @return string The description which has been parsed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Parses a line of a doc comment for a tag and its value.
     * The result is stored in the internal tags array.
     *
     * @param string $line A line of a doc comment which starts with an @-sign
     * @return void
     */
    protected function parseTag($line)
    {
        $tagAndValue = [];
        if (preg_match('/@[A-Za-z0-9\\\\]+\\\\([A-Za-z0-9]+)(?:\\((.*)\\))?$/', $line, $tagAndValue) === 0) {
            $tagAndValue = preg_split('/\s/', $line, 2);
        } else {
            array_shift($tagAndValue);
        }
        $tag = strtolower(trim($tagAndValue[0], '@'));
        if (count($tagAndValue) > 1) {
            $this->tags[$tag][] = trim($tagAndValue[1], ' "');
        } else {
            $this->tags[$tag] = [];
        }
    }
}