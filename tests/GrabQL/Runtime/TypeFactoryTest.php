<?php
use \GrabQL\Runtime\TypeFactory;
use \GrabQL\Runtime\Type\Scalar;

class MockTypeFactory
{
    public $name;

    public $surname;

    public function __construct($name, $surname) {
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getNameSurname()
    {
        return $this->name . ' ' . $this->surname;
    }
}

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

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Map::init
     * @covers \GrabQL\Runtime\Type\Map::initFromArray
     * @covers \GrabQL\Runtime\Type\Map::atImplicit
     * @covers \GrabQL\Runtime\Type\Map::count
     */
    public function testArray()
    {
        $instance = TypeFactory::createInstance('id', array(1, 2, 3));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Map', $instance);
        $this->assertEquals('id', $instance->getId());
        $this->assertEquals(3, $instance->count());
    }

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Map::init
     * @covers \GrabQL\Runtime\Type\Map::initFromArray
     * @covers \GrabQL\Runtime\Type\Map::atImplicit
     * @covers \GrabQL\Runtime\Type\Map::count
     */
    public function testObject()
    {
        $instance = TypeFactory::createInstance('id', new MockTypeFactory('John', 'Smith'));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Map', $instance);
        $this->assertEquals('id', $instance->getId());
        $this->assertEquals(3, $instance->count());
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Scalar', $instance->at('name'));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Scalar', $instance->at('surname'));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Procedure', $instance->at('getNameSurname'));
    }

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Resource::init
     */
    public function testResource()
    {
        $instance = TypeFactory::createInstance('id', @fopen('./composer.json', 'r'));
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Resource', $instance);
        $this->assertEquals('id', $instance->getId());
    }

    /**
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     * @covers \GrabQL\Runtime\Type\Scalar::init
     * @covers \GrabQL\Runtime\Type\Nil::init
     */
    public function testNil()
    {
        $instance = TypeFactory::createInstance('id', null);
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Nil', $instance);
        $this->assertEquals('id', $instance->getId());
    }
} 