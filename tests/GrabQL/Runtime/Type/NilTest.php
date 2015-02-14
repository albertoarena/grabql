<?php
namespace Type;

use GrabQL\Runtime\Type\Nil;
use GrabQL\Runtime\Type\Scalar;

class NilTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Nil::init
     */
    public function testInit()
    {
        $nil = new Nil('nil');
        $this->assertEquals('nil', $nil->getId());
    }

    /**
     * @covers GrabQL\Runtime\Type\Nil::init
     * @covers GrabQL\Runtime\Type\Nil::asFlat
     */
    public function testAsFlat()
    {
        $nil = new Nil();
        $this->assertNull($nil->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Nil::init
     * @covers GrabQL\Runtime\Type\Nil::asString
     */
    public function testAsString()
    {
        $nil = new Nil();
        $this->assertEquals('NIL', $nil->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Nil::init
     * @covers GrabQL\Runtime\Type\Nil::copyObject
     */
    public function testCopyObject()
    {
        $nil = new Nil();
        $nil->copy(new Nil());
        $this->assertNull($nil->toFlat());
    }
} 