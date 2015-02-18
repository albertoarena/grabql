<?php
namespace Type;

use GrabQL\Runtime\Type\Scalar;

class ScalarTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithNull()
    {
        $procedure = new Scalar('id', null);
        $this->assertEquals('id', $procedure->getId());
        $this->assertNull($procedure->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithNumber()
    {
        $procedure = new Scalar('id1', 123);
        $this->assertEquals('id1', $procedure->getId());
        $this->assertEquals(123, $procedure->getValue());
        $procedure = new Scalar('id2', 3.14);
        $this->assertEquals('id2', $procedure->getId());
        $this->assertEquals(3.14, $procedure->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithString()
    {
        $procedure = new Scalar('id1', '');
        $this->assertEquals('id1', $procedure->getId());
        $this->assertEquals('', $procedure->getValue());
        $procedure = new Scalar('id2', 'abc');
        $this->assertEquals('id2', $procedure->getId());
        $this->assertEquals('abc', $procedure->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithBoolean()
    {
        $procedure = new Scalar('id', true);
        $this->assertEquals('id', $procedure->getId());
        $this->assertEquals(1, $procedure->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithArrayInvalid()
    {
        $procedure = new Scalar('id', array());
        $this->assertEquals('id', $procedure->getId());
        $this->assertNull($procedure->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithObjectInvalid()
    {
        $this->setExpectedException('\Exception', 'Invalid value for scalar');
        new Scalar('id', new \Exception('message'));
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Scalar::asFlat
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToFlat()
    {
        $procedure = new Scalar('id', 123);
        $this->assertEquals('id', $procedure->getId());
        $this->assertEquals(123, $procedure->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Scalar::asString
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toString
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToString()
    {
        $procedure = new Scalar('id', 123);
        $this->assertEquals('id', $procedure->getId());
        $this->assertEquals('123', $procedure->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Scalar::setValue
     * @covers GrabQL\Runtime\Type\Scalar::copyObject
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testCopy()
    {
        $procedure = new Scalar('id1', 123);
        $this->assertEquals('id1', $procedure->getId());
        $this->assertEquals('123', $procedure->toString());
        $procedure->copy(new Scalar('id2', 'abc'));
        $this->assertEquals('abc', $procedure->toString());
    }

} 