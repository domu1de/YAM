<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\UnitTest\Reflection;


use Doctrine\Common\Annotations\Annotation;
use YAM\Reflection\ReflectionService;
use YAM\UnitTest\Fixtures\DummyAnnotation;

class ReflectionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \YAM\Reflection\ReflectionService
     */
    protected $reflectionService;

    public function setUp()
    {
        $mock = $this->getMock('Doctrine\Common\Annotations\Reader');
        $annotationArray = [new DummyAnnotation([]), new Annotation([])];

        $mock->expects($this->any())
             ->method('getClassAnnotations')
             ->will($this->returnValue($annotationArray));

        $mock->expects($this->at(1))
             ->method('getClassAnnotation')
             ->will($this->returnValue(new DummyAnnotation([])));

        $mock->expects($this->any())
             ->method('getMethodAnnotations')
             ->will($this->returnValue($annotationArray));

        $mock->expects($this->at(1))
             ->method('getMethodAnnotation')
             ->will($this->returnValue(new DummyAnnotation([])));

        $mock->expects($this->any())
             ->method('getPropertyAnnotations')
             ->will($this->returnValue($annotationArray));

        $mock->expects($this->at(1))
             ->method('getPropertyAnnotation')
             ->will($this->returnValue(new DummyAnnotation([])));

        $this->reflectionService = new ReflectionService($mock);
    }

    public function testIsClassAnnotatedWith()
    {
        $this->assertFalse($this->reflectionService->isClassAnnotatedWith('stdClass', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
        $this->assertTrue($this->reflectionService->isClassAnnotatedWith('stdClass', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
    }

    public function testGetClassAnnotations()
    {
        $resultWithoutFilter = $this->reflectionService->getClassAnnotations('stdClass');
        $this->assertCount(2, $resultWithoutFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithoutFilter[0]);
        $this->assertInstanceOf('Doctrine\Common\Annotations\Annotation', $resultWithoutFilter[1]);

        $resultWithFilter = $this->reflectionService->getClassAnnotations('stdClass', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertCount(1, $resultWithFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithFilter[0]);
    }

    public function testGetClassAnnotation()
    {
        $this->assertNull($this->reflectionService->getClassAnnotation('stdClass', 'YAM\UnitTest\Fixtures\DummyAnnotation'));

        $result = $this->reflectionService->getClassAnnotation('stdClass', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertNotNull($result);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $result);
    }

    public function testIsMethodAnnotatedWith()
    {
        $this->assertFalse($this->reflectionService->isMethodAnnotatedWith('Exception', 'getMessage', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
        $this->assertTrue($this->reflectionService->isMethodAnnotatedWith('Exception', 'getMessage', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
    }

    public function testGetMethodAnnotations()
    {
        $resultWithoutFilter = $this->reflectionService->getMethodAnnotations('Exception', 'getMessage');
        $this->assertCount(2, $resultWithoutFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithoutFilter[0]);
        $this->assertInstanceOf('Doctrine\Common\Annotations\Annotation', $resultWithoutFilter[1]);

        $resultWithFilter = $this->reflectionService->getMethodAnnotations('Exception', 'getMessage', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertCount(1, $resultWithFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithFilter[0]);
    }

    public function testGetMethodAnnotation()
    {
        $this->assertNull($this->reflectionService->getMethodAnnotation('Exception', 'getMessage', 'YAM\UnitTest\Fixtures\DummyAnnotation'));

        $result = $this->reflectionService->getMethodAnnotation('Exception', 'getMessage', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertNotNull($result);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $result);
    }

    public function testIsPropertyAnnotatedWith()
    {
        $this->assertFalse($this->reflectionService->isPropertyAnnotatedWith('Exception', 'message', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
        $this->assertTrue($this->reflectionService->isPropertyAnnotatedWith('Exception', 'message', 'YAM\UnitTest\Fixtures\DummyAnnotation'));
    }

    public function testGetPropertyAnnotations()
    {
        $resultWithoutFilter = $this->reflectionService->getPropertyAnnotations('Exception', 'message');
        $this->assertCount(2, $resultWithoutFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithoutFilter[0]);
        $this->assertInstanceOf('Doctrine\Common\Annotations\Annotation', $resultWithoutFilter[1]);

        $resultWithFilter = $this->reflectionService->getPropertyAnnotations('Exception', 'message', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertCount(1, $resultWithFilter);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $resultWithFilter[0]);
    }

    public function testGetPropertyAnnotation()
    {
        $this->assertNull($this->reflectionService->getPropertyAnnotation('Exception', 'message', 'YAM\UnitTest\Fixtures\DummyAnnotation'));

        $result = $this->reflectionService->getPropertyAnnotation('Exception', 'message', 'YAM\UnitTest\Fixtures\DummyAnnotation');
        $this->assertNotNull($result);
        $this->assertInstanceOf('YAM\UnitTest\Fixtures\DummyAnnotation', $result);
    }
}
 