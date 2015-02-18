<?php
namespace Type;

use GrabQL\Runtime\Type\Procedure;

class ProcedureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithNull()
    {
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithCallback()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure('var', $callback);
        $this->assertEquals('var', $procedure->getId());

        $this->assertInstanceOf('GrabQL\Runtime\Type\Procedure', $procedure);
        $this->assertEquals('world', $procedure->execute());
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithArray()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure('var', array('code' => $callback, 'params' => array('parameter')));
        $this->assertEquals('var', $procedure->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Procedure', $procedure);
        $this->assertEquals(array('parameter'), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInitWithInvalid()
    {
        $this->setExpectedException('\Exception', 'Invalid callback, cannot initialise a procedure');
        new Procedure('var', 1234);
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Procedure::setCode
     */
    public function testSetCode()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure();
        $procedure->setCode($callback);
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::asFlat
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToFlatWithEmptyCodeAndParams()
    {
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
        $this->assertEquals('{"procedure":{"params":[],"code":null}}', $procedure->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::asFlat
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToFlatWithCodeAndEmptyParams()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
        $procedure->setCode($callback);
        $this->assertEquals('{"procedure":{"params":[],"code":"[Closure]"}}', $procedure->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::asFlat
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToFlatWithCodeAndParams()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
        $procedure->setCode($callback);
        $this->assertEquals('{"procedure":{"params":[],"code":"[Closure]"}}', $procedure->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::execute
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testExecute()
    {
        $callback = function ($id) {
            return $id . ' world';
        };
        $procedure = new Procedure();
        $procedure->setCode($callback);
        $procedure->setParams(array('id'));
        $this->assertInstanceOf('\Closure', $procedure->getCode());
        $this->assertEquals(array('id'), $procedure->getParams());
        $this->assertEquals('hello world', $procedure->execute(array('id' => 'hello')));
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::execute
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testExecuteWithNullCode()
    {
        $procedure = new Procedure();
        $procedure->setParams(array('id'));
        $this->assertNull($procedure->getCode());
        $this->assertEquals(array('id'), $procedure->getParams());
        $this->assertNull($procedure->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::execute
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testExecuteWithEmptyParams()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure();
        $procedure->setCode($callback);
        $this->assertInstanceOf('\Closure', $procedure->getCode());
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertEquals('world', $procedure->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::execute
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testExecuteWithNullCodeAndEmptyParams()
    {
        $procedure = new Procedure();
        $this->assertNull($procedure->getCode());
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::asString
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::toString
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToString()
    {
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
        $this->assertEquals('[procedure]', $procedure->toString());
    }

    /**
     * @todo Currently, copy object is not implemented
     *
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::copyObject
     * @covers GrabQL\Runtime\Type\Base::__construct
     * @covers GrabQL\Runtime\Type\Base::copy
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testCopy()
    {
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
        $procedure->copy(new Procedure());
        $this->assertNull($procedure->getCode());
    }

} 