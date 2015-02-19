<?php
use \GrabQL\Runtime\Collection;
use \GrabQL\Runtime\Type\Nil;

class FooMock {

}

class CollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     */
    public function testConstruct()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     */
    public function testConstructWithNotValidClass()
    {
        // @todo It shouldn't raise this exception, but "not valid"
        $this->setExpectedException('\Exception', '[GrabQL\Runtime\Collection] Class FooMock not available');
        new Collection('FooMock');
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     */
    public function testConstructWithNotAvailableClass()
    {
        $this->setExpectedException('\Exception', '[GrabQL\Runtime\Collection] Class FooFeeFoo not available');
        new Collection('FooFeeFoo');
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     */
    public function testAdd()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil());
        $this->assertEquals(1, $collection->count());
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     */
    public function testAddInvalid()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new FooMock());
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     * @covers \GrabQL\Runtime\Collection::get
     */
    public function testGet()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil('id'));
        $this->assertEquals(1, $collection->count());
        $this->assertEquals('id', $collection->get('id')->getId());
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     * @covers \GrabQL\Runtime\Collection::get
     */
    public function testGetInvalid()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil('id'));
        $this->assertEquals(1, $collection->count());
        $this->assertNull($collection->get('id2'));
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     * @covers \GrabQL\Runtime\Collection::delete
     */
    public function testDelete()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil('id'));
        $this->assertEquals(1, $collection->count());
        $collection->delete('id');
        $this->assertNull($collection->get('id'));
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     * @covers \GrabQL\Runtime\Collection::delete
     */
    public function testDeleteInvalid()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil('id'));
        $this->assertEquals(1, $collection->count());
        $collection->delete('id2');
        $this->assertNotNull($collection->get('id'));
    }

    /**
     * @covers \GrabQL\Runtime\Collection::__construct
     * @covers \GrabQL\Runtime\Collection::getClass
     * @covers \GrabQL\Runtime\Collection::count
     * @covers \GrabQL\Runtime\Collection::add
     * @covers \GrabQL\Runtime\Collection::getIterator
     */
    public function testGetIterator()
    {
        $collection = new Collection('\GrabQL\Runtime\Type\Nil');
        $this->assertEquals('\GrabQL\Runtime\Type\Nil', $collection->getClass());
        $this->assertEquals(0, $collection->count());
        $collection->add(new Nil('id1'));
        $collection->add(new Nil('id2'));
        $collection->add(new Nil('id3'));
        $this->assertEquals(3, $collection->count());
        foreach ($collection as $id => $obj) {
            $this->assertNotNull($id);
            $this->assertNotNull($obj);
            $this->assertInstanceOf('\GrabQL\Runtime\Type\Nil', $obj);
        }
    }

}