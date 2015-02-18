<?php
namespace Type;

use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Resource;
use GrabQL\Runtime\Type\Scalar;

class ResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInit()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testGetStream()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $this->assertEquals('', $resource->getStream());
    }

    /**
     * @todo Currently the read method doesn't do anything, only for code coverage
     *
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::read
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testRead()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $this->assertNull($resource->read(null));
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteString()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write('12345');
        $this->assertEquals('12345', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteStringWithLength()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write('12345', 2);
        $this->assertEquals('12', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteMap()
    {
        $map = new Map('b', array(1, 2.3, "a"));
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write($map);
        $this->assertEquals('12.3a', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteMapWithLength()
    {
        $map = new Map('b', array(1, 2.3, "a"));
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write($map, 2);
        $this->assertEquals('12.3', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteNull()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write(null);
        $this->assertEquals('', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteScalar()
    {
        $var = new Scalar('b', 123);
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write($var);
        $this->assertEquals('123', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteScalarWithLength()
    {
        $var = new Scalar('b', 123);
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->write($var, 2);
        $this->assertEquals('12', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteResource()
    {
        $fp = @fopen('./composer.json', 'r');
        if ($fp) {
            $resource = new Resource('a');
            $this->assertEquals('a', $resource->getId());
            $resource->write($fp);
            $this->assertRegExp('/^\{\n[\s]*"name": "albertoarena\/grabql"/', $resource->getStream());
        }
        else {
            // File not open
            $this->assertEquals(1, 1);
        }
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::write
     * @covers GrabQL\Runtime\Type\Resource::getStream
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testWriteResourceWithLength()
    {
        $fp = @fopen('./composer.json', 'r');
        if ($fp) {
            $resource = new Resource('a');
            $this->assertEquals('a', $resource->getId());
            $resource->write($fp, 11);
            $this->assertRegExp('/^\{\n[\s]*"name/', $resource->getStream());
        }
        else {
            // File not open
            $this->assertEquals(1, 1);
        }
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::asFlat
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testAsFlat()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $this->assertEquals('[resource]', $resource->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::asString
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toString
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testAsString()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $this->assertEquals('[resource]', $resource->toString());
    }

    /**
     * @todo copyObject has to be implemented
     *
     * @covers GrabQL\Runtime\Type\Resource::init
     * @covers GrabQL\Runtime\Type\Resource::copyObject
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testCopyObject()
    {
        $resource = new Resource('a');
        $this->assertEquals('a', $resource->getId());
        $resource->copy(new Resource('b'));
        $this->assertEquals('', $resource->getStream());
    }

}