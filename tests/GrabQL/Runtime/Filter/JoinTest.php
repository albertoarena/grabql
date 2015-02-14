<?php
namespace Filter;

use GrabQL\Runtime\Filter\Join;
use GrabQL\Runtime\Filter\Json;
use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Nil;
use GrabQL\Runtime\Type\Reference;
use GrabQL\Runtime\Type\Scalar;

class JoinTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Filter\Join::__construct
     * @covers GrabQL\Runtime\Filter\Filter::getType
     * @covers GrabQL\Runtime\Filter\Filter::__construct
     */
    public function testConstruct()
    {
        $filter = new Join();
        $this->assertEquals(Join::TYPE, $filter->getType());
    }

    /**
     * @covers GrabQL\Runtime\Filter\Join::__construct
     * @covers GrabQL\Runtime\Filter\Join::apply
     * @covers GrabQL\Runtime\Filter\Join::join
     */
    public function testApply()
    {
        $join = new Join();
        $result = $join->apply(array(1, 2, 3));
        $this->assertEquals('1,2,3', $result);
    }

    /**
     * @covers GrabQL\Runtime\Filter\Join::__construct
     * @covers GrabQL\Runtime\Filter\Join::apply
     * @covers GrabQL\Runtime\Filter\Join::join
     */
    public function testMapApply()
    {
        $join = new Join();
        $result = $join->apply(new Map(null, array(1, 2, 3)));
        $this->assertEquals('1,2,3', $result);
    }

    /**
     * @covers GrabQL\Runtime\Filter\Join::__construct
     * @covers GrabQL\Runtime\Filter\Join::apply
     * @covers GrabQL\Runtime\Filter\Join::join
     */
    public function testArrayBaseApply()
    {
        $var = new Scalar(null, '2');
        $join = new Join();
        $result = $join->apply(array(new Scalar(null, '1'), new Reference(null, $var), new Nil()));
        $this->assertEquals('1,2,NIL', $result);
    }

} 