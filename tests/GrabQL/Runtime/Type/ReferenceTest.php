<?php
namespace Type;

use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Reference;
use GrabQL\Runtime\Type\Scalar;

class ReferenceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testInit()
    {

        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
        $this->assertEquals(3, count($ref->getReference()->getValues()));
        $this->assertEquals(1, $ref->getReference()->getValues()[0]->getValue());
        $this->assertEquals(2.3, $ref->getReference()->getValues()[1]->getValue());
        $this->assertEquals('a', $ref->getReference()->getValues()[2]->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testSetReference()
    {
        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testSetInvalidReference()
    {
        $var = 1234;
        $this->setExpectedException('\Exception', 'Invalid reference');
        new Reference('b', $var);
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::asFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testToFlat()
    {
        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
        $this->assertEquals(array(1, 2.3, "a"), $ref->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::asFlat
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToFlatWithNullReference()
    {
        $ref = new Reference('b');
        $this->assertEquals('b', $ref->getId());
        $this->assertNull($ref->getReference());
        $this->assertNull($ref->toFlat());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::asString
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testToString()
    {
        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
        $this->assertEquals('1, 2.3, a', $ref->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::asString
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testToStringWithNullReference()
    {
        $ref = new Reference('b');
        $this->assertEquals('b', $ref->getId());
        $this->assertNull($ref->getReference());
        $this->assertEquals('', $ref->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::copy
     * @covers GrabQL\Runtime\Type\Reference::copyObject
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testCopyObject()
    {
        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
        $this->assertEquals(array(1, 2.3, 'a'), $ref->getReference()->toFlat());
        $this->assertEquals('1, 2.3, a', $ref->getReference()->toString());

        $var2 = new Map('c', array('a', 'b'));
        $ref->copy($var2);
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('c', $ref->getReference()->getId());
        $this->assertEquals(array('a', 'b'), $ref->getReference()->toFlat());
        $this->assertEquals('a, b', $ref->getReference()->toString());
    }

    /**
     * @covers GrabQL\Runtime\Type\Reference::init
     * @covers GrabQL\Runtime\Type\Reference::setReference
     * @covers GrabQL\Runtime\Type\Reference::getReference
     * @covers GrabQL\Runtime\Type\Reference::copy
     * @covers GrabQL\Runtime\Type\Reference::copyObject
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Scalar::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testCopyObjectInvalid()
    {
        $var = new Map('a', array(1, 2.3, 'a'));
        $ref = new Reference('b', $var);
        $this->assertEquals('b', $ref->getId());
        $this->assertInstanceOf('GrabQL\Runtime\Type\Map', $ref->getReference());
        $this->assertEquals('a', $ref->getReference()->getId());
        $this->assertEquals(array(1, 2.3, 'a'), $ref->getReference()->toFlat());
        $this->assertEquals('1, 2.3, a', $ref->getReference()->toString());

        $this->setExpectedException('\Exception', 'Unable to copy, the source object is not matching the destination object');
        $var2 = new Scalar('c', '123');
        $ref->copy($var2);
    }

} 