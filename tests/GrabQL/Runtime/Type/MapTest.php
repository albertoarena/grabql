<?php
namespace Type;

use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Procedure;
use GrabQL\Runtime\Type\Scalar;

class MapTestClass
{
    protected $name;
    protected $surname;
    public $city;

    public function __construct($name, $surname, $city)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->city = $city;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getFullAddress()
    {
        return $this->name . ' ' . $this->surname . ', ' . $this->city;
    }
}

class MapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testInitWithEmptyArray()
    {
        $map = new Map('map', array());
        $this->assertEquals('map', $map->getId());
        $this->assertEquals(0, count($map->getValues()));
        $this->assertEquals(array(), $map->getValues());
        $this->assertEquals(false, $map->getOrdered());
    }


    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::initFromArray
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testInitWithArrayOfValues()
    {
        $map = new Map('map', array(1, 2));
        $this->assertEquals('map', $map->getId());
        $this->assertEquals(false, $map->getOrdered());
        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(1, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals(2, $values[1]->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     */
    public function testInitWithNull()
    {
        $map = new Map('map', null);
        $this->assertEquals('map', $map->getId());
        $this->assertEquals(0, count($map->getValues()));
        $this->assertEquals(array(), $map->getValues());
        $this->assertEquals(false, $map->getOrdered());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::copyObject
     * @covers GrabQL\Runtime\Type\Map::offsetSet
     */
    public function testInitWithMap()
    {
        $map = new Map('map', new Map('map1', array(1, 2)));
        $this->assertEquals('map', $map->getId());
        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(1, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals(2, $values[1]->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::initFromArray
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     * @covers GrabQL\Runtime\Type\Map::copyObject
     * @covers GrabQL\Runtime\Type\Map::offsetSet
     */
    public function testInitWithArrayOfScalars()
    {
        $map = new Map('map', array(new Scalar('scalar1', 1), new Scalar('scalar2', 2)));
        $this->assertEquals('map', $map->getId());
        $this->assertEquals(false, $map->getOrdered());
        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(1, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals(2, $values[1]->getValue());
    }


    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::initFromObject
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\TypeFactory::createInstance
     * @covers GrabQL\Runtime\Type\Map::offsetSet
     * @covers GrabQL\Runtime\Type\Procedure::init
     * @covers GrabQL\Runtime\Type\Procedure::arrayCombine
     * @covers GrabQL\Runtime\Type\Procedure::execute
     */
    public function testInitWithObject()
    {
        $mapTest = new MapTestClass('John', 'Smith', 'London');
        $map = new Map('map', $mapTest);
        $this->assertEquals('map', $map->getId());
        $this->assertEquals(false, $map->getOrdered());
        $values = $map->getValues();
        $this->assertEquals(4, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values['city']);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values['getName']);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values['getSurname']);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values['getFullAddress']);
        $this->assertEquals('London', $values['city']->getValue());
        $this->assertEquals('John', $values['getName']->execute());
        $this->assertEquals('Smith', $values['getSurname']->execute());
        $this->assertEquals('John Smith, London', $values['getFullAddress']->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     */
    public function testInitWithInvalid()
    {
        $this->setExpectedException('\Exception', 'Invalid value for a map (only Map, array or object accepted)');
        new Map('map', 123);
        $this->setExpectedException('\Exception', 'Invalid value for a map (only Map, array or object accepted)');
        new Map('map', 'string');
        $this->setExpectedException('\Exception', 'Invalid value for a map (only Map, array or object accepted)');
        new Map('map', function () {
            return 'hello';
        });
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     */
    public function testPushBase()
    {
        $map = new Map('map');
        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));

        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals('var1', $values[0]->getId());
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var2', $values[1]->getId());
        $this->assertEquals('abc', $values[1]->getValue());

        $map->push(new Procedure('procedure', function () {
            return 'hello';
        }));

        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals('var1', $values[0]->getId());
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var2', $values[1]->getId());
        $this->assertEquals('abc', $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[2]);
        $this->assertEquals('procedure', $values[2]->getId());
        $this->assertEquals('hello', $values[2]->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers \GrabQL\Runtime\TypeFactory::createInstance
     */
    public function testPushValue()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push('abc');
        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('abc', $values[1]->getValue());

        $map->push(function () {
            return 'hello';
        });

        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('abc', $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[2]);
        $this->assertEquals('hello', $values[2]->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::pop
     */
    public function testPop()
    {
        $map = new Map('map');
        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));
        $map->push(new Procedure('procedure', function () {
            return 'hello';
        }));
        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('abc', $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[2]);
        $this->assertEquals('hello', $values[2]->execute());

        $pop = $map->pop();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $pop);
        $this->assertEquals('hello', $pop->execute());

        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('abc', $values[1]->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::unshift
     */
    public function testUnshiftBase()
    {
        $map = new Map('map');
        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));

        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals('var1', $values[0]->getId());
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var2', $values[1]->getId());
        $this->assertEquals('abc', $values[1]->getValue());

        $map->unshift(new Procedure('procedure', function () {
            return 'hello';
        }));

        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[0]);
        $this->assertEquals('procedure', $values[0]->getId());
        $this->assertEquals('hello', $values[0]->execute());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var1', $values[1]->getId());
        $this->assertEquals(123, $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[2]);
        $this->assertEquals('var2', $values[2]->getId());
        $this->assertEquals('abc', $values[2]->getValue());
    }


    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::unshift
     */
    public function testUnshiftValue()
    {
        $map = new Map('map');
        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));

        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals('var1', $values[0]->getId());
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var2', $values[1]->getId());
        $this->assertEquals('abc', $values[1]->getValue());

        $map->unshift(function () {
            return 'hello';
        });

        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[0]);
        $this->assertEquals('hello', $values[0]->execute());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('var1', $values[1]->getId());
        $this->assertEquals(123, $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[2]);
        $this->assertEquals('var2', $values[2]->getId());
        $this->assertEquals('abc', $values[2]->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::shift
     */
    public function testShift()
    {
        $map = new Map('map');
        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));
        $map->push(new Procedure('procedure', function () {
            return 'hello';
        }));
        $values = $map->getValues();
        $this->assertEquals(3, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals(123, $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[1]);
        $this->assertEquals('abc', $values[1]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[2]);
        $this->assertEquals('hello', $values[2]->execute());

        $shift = $map->shift();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $shift);
        $this->assertEquals(123, $shift->getValue());

        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $values[0]);
        $this->assertEquals('abc', $values[0]->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $values[1]);
        $this->assertEquals('hello', $values[1]->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::pop
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::count
     */
    public function testCount()
    {
        $map = new Map('map');
        $this->assertEquals(0, $map->count());

        $map->push(new Scalar('var1', 123));
        $map->push(new Scalar('var2', 'abc'));
        $this->assertEquals(2, $map->count());

        $map->push(new Procedure('procedure', function () {
            return 'hello';
        }));
        $this->assertEquals(3, $map->count());

        $map->pop();
        $map->pop();
        $this->assertEquals(1, $map->count());
    }


    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     */
    public function testAtNullIndex()
    {
        $map = new Map('map');

        $map->atImplicit(0, 123);
        $map->atImplicit(null, 'abc');
        $this->assertEquals(2, count($map->getValues()));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(0));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(1));

        $map->atImplicit(1, function() {});
        $this->assertEquals(2, count($map->getValues()));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(0));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(1));
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     */
    public function testAtIntIndex()
    {
        $map = new Map('map');
        $this->assertNull($map->at(0));

        $map->push(new Scalar('var1', 123));
        $map->push(new Procedure('procedure', function () {
            return 'hello';
        }));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(0));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->at(1));
        $this->assertNull($map->at(2));

        $map->at(0)->setValue('abc');
        $this->assertEquals('abc', $map->at(0)->getValue());
        $this->assertEquals('hello', $map->at(1)->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     */
    public function testAtStringIndex()
    {
        $map = new Map('map');
        $this->assertNull($map->at('a'));

        $map->atImplicit('a', new Scalar('var1', 123));
        $map->atImplicit('b', new Procedure('procedure', function () {
            return 'hello';
        }));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at('a'));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->at('b'));
        $this->assertNull($map->at('c'));

        $map->at('a')->setValue('abc');
        $this->assertEquals('abc', $map->at('a')->getValue());
        $this->assertEquals('hello', $map->at('b')->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     */
    public function testAtImplicitIntIndex()
    {
        $map = new Map('map');
        $this->assertNull($map->at(0));

        $map->atImplicit(0, new Scalar('var1', 123));
        $map->atImplicit(1, new Procedure('procedure', function () {
            return 'hello';
        }));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(0));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->at(1));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->atImplicit(0));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->atImplicit(1));

        $map->atImplicit(0)->setValue('abc');
        $this->assertEquals('abc', $map->atImplicit(0)->getValue());
        $this->assertEquals('hello', $map->atImplicit(1)->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     */
    public function testAtImplicitIntString()
    {
        $map = new Map('map');
        $this->assertNull($map->at('a'));

        $map->atImplicit('a', new Scalar('var1', 123));
        $map->atImplicit('b', new Procedure('procedure', function () {
            return 'hello';
        }));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at('a'));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->at('b'));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->atImplicit('a'));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->atImplicit('b'));

        $map->atImplicit('a')->setValue('abc');
        $this->assertEquals('abc', $map->atImplicit('a')->getValue());
        $this->assertEquals('hello', $map->atImplicit('b')->execute());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::asFlat
     */
    public function testToFlatValues()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push('abc');
        $map->push(null);
        $flat = $map->toFlat();
        $this->assertEquals(3, count($flat));
        $this->assertEquals(array(123, 'abc', null), $flat);
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::asString
     */
    public function testToStringValues()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push('abc');
        $map->push('');
        $map->push(null);
        $string = $map->toString();
        $this->assertEquals('123, abc, "", NIL', $string);
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::delete
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetUnset
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     */
    public function testDeleteIndex()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push('abc');
        $map->push('hello');
        $this->assertEquals(3, count($map->getValues()));

        $map->delete(1);
        $values = $map->getValues();
        $this->assertEquals(2, count($values));

        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(0));
        $this->assertNull($map->at(1));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at(2));
        $this->assertEquals(123, $map->atImplicit(0)->getValue());
        $this->assertEquals('hello', $map->atImplicit(2)->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::delete
     * @covers GrabQL\Runtime\Type\Map::at
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetUnset
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetGet
     */
    public function testDeleteString()
    {
        $map = new Map('map');
        $map->atImplicit('a', 123);
        $map->atImplicit('b', 'abc');
        $map->atImplicit('c', 'hello');
        $this->assertEquals(3, count($map->getValues()));

        $map->delete('b');
        $values = $map->getValues();
        $this->assertEquals(2, count($values));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at('a'));
        $this->assertNull($map->at('b'));
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->at('c'));
        $this->assertEquals(123, $map->atImplicit('a')->getValue());
        $this->assertEquals('hello', $map->atImplicit('c')->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\BaseIterator::next
     * @covers GrabQL\Runtime\Type\BaseIterator::current
     */
    public function testCurrent()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push(function () {
        });
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->current());
        $map->next();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->current());
    }


    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\BaseIterator::next
     * @covers GrabQL\Runtime\Type\BaseIterator::key
     */
    public function testKey()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push(function () {
        });
        $this->assertEquals(0, $map->key());
        $map->next();
        $this->assertEquals(1, $map->key());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\BaseIterator::next
     * @covers GrabQL\Runtime\Type\BaseIterator::rewind
     * @covers GrabQL\Runtime\Type\BaseIterator::current
     * @covers GrabQL\Runtime\Type\BaseIterator::key
     */
    public function testRewind()
    {
        $map = new Map('map');
        $map->push(123);
        $map->push(function () {
        });
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->current());
        $this->assertEquals(0, $map->key());
        $map->next();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Procedure', $map->current());
        $this->assertEquals(1, $map->key());
        $map->rewind();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $map->current());
        $this->assertEquals(0, $map->key());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\BaseIterator::valid
     */
    public function testValid()
    {
        $map = new Map();
        $this->assertFalse($map->valid());
        $map->push(123);
        $this->assertTrue($map->valid());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\BaseIterator::clear
     */
    public function testClear()
    {
        $map = new Map();
        $map->push(123);
        $map->push('abc');
        $this->assertEquals(2, count($map->getValues()));
        $map->clear();
        $this->assertEquals(0, count($map->getValues()));
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::has
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetExists
     */
    public function testHasString()
    {
        $map = new Map();
        $map->atImplicit('a', 123);
        $map->atImplicit('b', 'abc');
        $this->assertTrue($map->has('a'));
        $this->assertTrue($map->has('b'));
        $this->assertFalse($map->has('c'));
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::push
     * @covers GrabQL\Runtime\Type\Map::has
     * @covers GrabQL\Runtime\Type\Map::atImplicit
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetSet
     * @covers GrabQL\Runtime\Type\BaseIterator::offsetExists
     */
    public function testHasIndex()
    {
        $map = new Map();
        $map->push(123);
        $map->push('abc');
        $this->assertTrue($map->has(0));
        $this->assertTrue($map->has(1));
        $this->assertFalse($map->has(2));
    }
}