<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Reflection;


use YAM\Reflection\DocCommentParser;

class DocCommentParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getTagsShouldReturnArray()
    {
        $parser = new DocCommentParser();
        $this->assertTrue(is_array($parser->getTags()));
        $this->assertEmpty($parser->getTags());
    }

    /**
     * @test
     */
    public function getDescriptionShouldReturnString()
    {
        $parser = new DocCommentParser();
        $this->assertTrue(is_string($parser->getDescription()));
        $this->assertEmpty($parser->getDescription());
    }

    /**
     * @test
     */
    public function parseDocComment()
    {
        $parser = new DocCommentParser();
        $parser->parseDocComment('/**' . chr(10) . ' * Testcase for DocCommentParser' . chr(10) . ' */');
        $this->assertEquals('Testcase for DocCommentParser', $parser->getDescription());

        $parser->parseDocComment('/**' . chr(10) . ' * @var integer' . chr(10) . ' * @var string' . chr(10) . ' * @return array' . chr(10) . ' */');
        $this->assertEquals(['var' => ['integer', 'string'], 'return' => ['array']], $parser->getTags());
        $this->assertEmpty($parser->getDescription());
    }

    /**
     * @test
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage Tag "var" does not exist.
     */
    public function getTagValuesShouldThrowExceptionForNonExistingTag()
    {
        $parser = new DocCommentParser();
        $parser->getTagValues('var');
    }
}
 