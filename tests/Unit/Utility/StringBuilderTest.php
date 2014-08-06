<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Utility;


use YAM\Utility\StringBuilder;

class StringBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testAppend()
    {
        $sb = new StringBuilder();
        $sb->append('TestString');
        $sb->append('TestString2');
        $this->assertEquals('TestStringTestString2', (string) $sb);
    }

    public function testAppendWithFormat()
    {
        $sb = new StringBuilder();
        $sb->append('Hello %s!', 'Mike');
        $this->assertEquals('Hello Mike!', (string) $sb);

        $sb = new StringBuilder();
        $sb->append('2 + %d = %d', 2, 4);
        $this->assertEquals('2 + 2 = 4', (string) $sb);
    }

    public function testAppendLine()
    {
        $sb = new StringBuilder();
        $sb->appendLine('Line1');
        $sb->appendLine('Line2');
        $this->assertEquals('Line1' . PHP_EOL . 'Line2' . PHP_EOL, (string) $sb);
    }

    public function testAppendBlankLine()
    {
        $sb = new StringBuilder();
        $sb->appendLine();
        $this->assertEquals(PHP_EOL, (string) $sb);
    }

    public function testAppendLineWithFormat()
    {
        $sb = new StringBuilder();
        $sb->appendLine('Hello %s!', 'Mike');
        $this->assertEquals('Hello Mike!' . PHP_EOL, (string) $sb);

        $sb = new StringBuilder();
        $sb->appendLine('2 + %d = %d', 2, 4);
        $this->assertEquals('2 + 2 = 4' . PHP_EOL, (string) $sb);
    }

    public function testClear()
    {
        $sb = new StringBuilder();
        $sb->appendLine('Line1');
        $this->assertNotEmpty((string) $sb);
        $sb->clear();
        $this->assertEmpty((string) $sb);
    }

    public function testToString()
    {
        $sb = new StringBuilder();
        $sb->append('Test');
        $this->assertEquals('Test', (string) $sb);
        $this->assertEquals('Test', $sb->__toString());
    }

    public function testFluentInterface()
    {
        $sb = new StringBuilder();
        $this->assertSame($sb, $sb->append('String'));
        $this->assertSame($sb, $sb->append('String', 1, 2));
        $this->assertSame($sb, $sb->appendLine('Line'));
        $this->assertSame($sb, $sb->appendLine('Line', 1, 2));
        $this->assertSame($sb, $sb->clear());
    }
}
 