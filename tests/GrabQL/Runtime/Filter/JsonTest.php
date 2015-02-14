<?php
namespace Filter;

use GrabQL\Runtime\Filter\Json;
use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Scalar;

class JsonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Filter\Json::__construct
     * @covers GrabQL\Runtime\Filter\Filter::getType
     * @covers GrabQL\Runtime\Filter\Filter::__construct
     */
    public function testConstruct()
    {
        $filter = new Json();
        $this->assertEquals(Json::TYPE, $filter->getType());
    }

    /**
     * @covers GrabQL\Runtime\Filter\Json::__construct
     * @covers GrabQL\Runtime\Filter\Json::apply
     */
    public function testFilter()
    {
        $filter = new Json();
        $result = $filter->apply(array('a' => '1', 'b' => 123, 'c' => new Scalar('var', 'hello')));
        $this->assertEquals('{"a":"1","b":123,"c":{}}', $result);
    }

    /**
     * @covers GrabQL\Runtime\Filter\Json::__construct
     * @covers GrabQL\Runtime\Filter\Json::apply
     * @covers GrabQL\Runtime\Type\Base::setFilter
     */
    public function testBaseFilter()
    {
        $filter = new Json();
        $result = $filter->apply(new Map(null, array(1.0, '2', 3)));
        $this->assertEquals('[1,"2",3]', $result);
    }

    /**
     * @covers GrabQL\Runtime\Filter\Json::__construct
     * @covers GrabQL\Runtime\Filter\Json::apply
     * @covers GrabQL\Runtime\Type\Base::setFilter
     */
    public function testFilterInScalar()
    {
        $var = new Map('var', array(1, 2, 3));
        $var->setFilter(new Json);
        $this->assertEquals('[1,2,3]', $var->toString());
    }

} 