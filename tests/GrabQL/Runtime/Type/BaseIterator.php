<?php
namespace Type;

use GrabQL\Runtime\Type\BaseIterator;

class BaseIteratorMock extends BaseIterator
{

    public function asFlat()
    {
        return json_encode($this->values);
    }

    public function asString()
    {
        return strval($this->values);
    }

    public function copyObject($obj)
    {
        // NOP
    }

}

class BaseIteratorTest extends \PHPUnit_Framework_TestCase
{

    /** @var BaseIteratorMock */
    protected $base;

    protected function assertPreConditions()
    {
        $this->base = new BaseIteratorMock('mock', array(1, 2));
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testInit()
    {
        $this->assertEquals(array(), $this->base->getValues());
    }

    /**
     * @covers GrabQL\Runtime\Type\BaseIterator::init
     */
    public function testSetValues()
    {
        $this->assertEquals(array(), $this->base->getValues());
        $this->base->setValues(array(1, 2));
        $this->assertEquals(array(1, 2), $this->base->getValues());
    }

} 