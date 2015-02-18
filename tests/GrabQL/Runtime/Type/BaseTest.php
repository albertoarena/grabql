<?php
namespace Type;

use GrabQL\Runtime\Type\Base;
use GrabQL\Runtime\Filter\Json;

class BaseTest extends \PHPUnit_Framework_TestCase
{

    /** @var Base */
    protected $base;

    /** @var Json */
    protected $filter;

    protected function createMock()
    {
        $base = $this->getMockBuilder('GrabQL\\Runtime\\Type\\Base')
            ->setMethods(array('init', 'asFlat', 'asString', 'copyObject'))
            ->getMock();

        $base->expects($this->any())
            ->method('init')
            ->with($this->equalTo(array(1, 2)))
            ->will($this->returnValue(null));

        $base->expects($this->any())
            ->method('asFlat')
            ->will($this->returnValue(array(1, 2)));

        $base->expects($this->any())
            ->method('asString')
            ->will($this->returnValue('1,2'));

        $base->expects($this->any())
            ->method('copyObject')
            ->will($this->returnValue(null));

        return $base;
    }

    protected function assertPreConditions()
    {
        $this->filter = new Json();
        $this->base = $this->createMock();
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::setFilter
     */
    public function testConstruct()
    {
        $this->assertNull($this->base->getFilter());
        $this->base->setFilter($this->filter);
        $this->assertEquals($this->filter, $this->base->getFilter());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asFlat
     * @covers GrabQL\Runtime\Type\Base::toFlat
     */
    public function testToFlat()
    {
        $this->assertEquals(array(1, 2), $this->base->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asFlat
     * @covers GrabQL\Runtime\Type\Base::toFlat
     */
    public function testToFlatWithFilter()
    {
        $this->base->setFilter($this->filter);
        $this->assertEquals('[1,2]', $this->base->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asFlat
     * @covers GrabQL\Runtime\Type\Base::asJson
     * @covers GrabQL\Runtime\Type\Base::toJson
     */
    public function testToJson()
    {
        $this->assertEquals('[1,2]', $this->base->toJSON());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asFlat
     * @covers GrabQL\Runtime\Type\Base::asJson
     * @covers GrabQL\Runtime\Type\Base::toJson
     */
    public function testToJsonWithFilter()
    {
        $this->base->setFilter($this->filter);
        $this->assertEquals('"[1,2]"', $this->base->toJSON());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asString
     * @covers GrabQL\Runtime\Type\Base::toString
     */
    public function testToString()
    {
        $this->assertEquals('1,2', $this->base->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asFlat
     * @covers GrabQL\Runtime\Type\Base::asString
     * @covers GrabQL\Runtime\Type\Base::toString
     */
    public function testToStringWithFilter()
    {
        $this->base->setFilter($this->filter);
        $this->assertEquals('[1,2]', $this->base->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::asString
     * @covers GrabQL\Runtime\Type\Base::toString
     * @covers GrabQL\Runtime\Type\Base::__toString
     */
    public function testTo__String()
    {
        $this->expectOutputString('1,2');
        echo $this->base;
    }

    /**
     * No real test, only coverage
     *
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\Base::copyObject
     */
    public function testCopy()
    {
        $obj = $this->createMock();
        $this->base->copy($obj);
        $this->assertEquals(1, 1);
    }

    /**
     * No real test, only coverage
     *
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\Base::copyObject
     */
    public function testCopyException()
    {
        $obj = new Json();
        $this->setExpectedException('\Exception', 'Unable to copy, the source object is not matching the destination object');
        $this->base->copy($obj);
    }

    /**
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::debugLog
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\Base::copyObject
     */
    public function testDebug()
    {
        $mock2 = $this->createMock();
        $this->base->copy($mock2);
        $this->base->setDebug(true);
        $this->expectOutputRegex('/\nBase::copy\nArray\n\(\n'.
            '[\s]*\[id\][\s]=>[\s]([a-z][a-z0-9]*)\n' .
            '[\s]*\[class\][\s]=>[\s][A-Za-z0-9_]*\n'.
            '\)\n'.
            '/'
        );
        $this->base->copy($mock2);
    }
}