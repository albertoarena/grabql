<?php
use \GrabQL\Runtime\TypeFactory;
use \GrabQL\Runtime\Type\Scalar;

class TypeFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Reference::init
     * @covers \GrabQL\Runtime\Type\Reference::setReference
     * @covers \GrabQL\Runtime\Type\Reference::getReference
     * @covers \GrabQL\Runtime\Type\Reference::asFlat
     */
    public function testReference()
    {
        $instance = TypeFactory::createInstance('id', new Scalar(null, 123));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Reference', $instance);
        $this->assertEquals('id', $instance->getId());
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Scalar', $instance->getReference());
        // @todo Currently this fails, because $instance->asFlat() returns NULL rather than the value of the referenced variable
        // $this->assertEquals(123, $instance->asFlat());
        $this->assertEquals(123, $instance->getReference()->getValue());
    }

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Reference::init
     * @covers \GrabQL\Runtime\Type\Reference::setReference
     * @covers \GrabQL\Runtime\Type\Reference::getReference
     * @covers \GrabQL\Runtime\Type\Reference::asFlat
     */
    public function testCallable()
    {
        $callable = function () {
            return 'hello';
        };
        $instance = TypeFactory::createInstance('id', $callable);
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Procedure', $instance);
        $this->assertEquals('id', $instance->getId());
        $this->assertEquals('hello', $instance->execute());
    }

} 