<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Reflection\Fixtures;


class SampleClass
{
    private $testProperty;

    public $testProperty2;

    protected $testProperty3;

    public function methodWithTypeHint(\stdClass $a)
    {
    }

    /**
     * @param \SplObjectStorage $a
     */
    public function methodWithTypeHintAndPhpDoc(\stdClass $a)
    {
    }

    /**
     * @param \stdClass $a
     */
    public function methodWithPhpDoc($a)
    {
    }

    public function methodWithoutType($a)
    {

    }

    public function methodWithArrayType(array $a)
    {

    }

    /**
     * @param \stdClass[] $a
     */
    public function methodWithArrayTypeAndPhpDoc(array $a)
    {

    }

    /**
     * @param int $b
     */
    public function methodWithArrayTypeAndUnmatchedPhpDoc(array $a, $b)
    {

    }

    public function methodWithCallableTypeHint(callable $a)
    {

    }

    private static function fakeMethod()
    {

    }
} 