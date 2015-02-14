<?php
namespace Type;

use GrabQL\Runtime\Type\Procedure;

class ProcedureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     */
    public function testInitWithNull()
    {
        $procedure = new Procedure();
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertNull($procedure->getCode());
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     */
    public function testInitWithCallback()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure($callback);

        // @todo
        $this->assertInstanceOf('GrabQL\Runtime\Type\Procedure', $procedure);
        /*
        $this->assertEquals('world', $procedure->execute());
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
        */
    }

    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     */
    public function testInitWithArray()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure(array('code' => $callback, 'params' => array('parameter')));

        // @todo
        $this->assertInstanceOf('GrabQL\Runtime\Type\Procedure', $procedure);
        /*
        $this->assertEquals('world', $procedure->execute());
        $this->assertEquals(array('parameter'), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
        */
    }


    /**
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::setCode
     */
    public function testSetCode()
    {
        $callback = function () {
            return 'world';
        };
        $procedure = new Procedure();
        $procedure->setDebug(true);
        $this->expectOutputString("\n" . 'Procedure::setCode' . "\n");
        $procedure->setCode($callback);
        $procedure->setDebug(false);
        $this->assertEquals('world', $procedure->execute());
        $this->assertEquals(array(), $procedure->getParams());
        $this->assertInstanceOf('\Closure', $procedure->getCode());
    }
} 