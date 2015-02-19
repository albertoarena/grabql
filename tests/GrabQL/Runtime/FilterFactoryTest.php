<?php
use \GrabQL\Runtime\FilterFactory;

class FilterFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Runtime\FilterFactory::build
     * @covers \GrabQL\Runtime\Filter\Join::__construct
     */
    public function testBuildJoin()
    {
        $filter = FilterFactory::build('join');
        $this->assertInstanceOf('GrabQL\\Runtime\\Filter\\Join', $filter);
    }

    /**
     * @covers \GrabQL\Runtime\FilterFactory::build
     * @covers \GrabQL\Runtime\Filter\Json::__construct
     */
    public function testBuildJson()
    {
        $filter = FilterFactory::build('json');
        $this->assertInstanceOf('GrabQL\\Runtime\\Filter\\Json', $filter);
    }

    /**
     * @covers \GrabQL\Runtime\FilterFactory::build
     */
    public function testBuildInvalid()
    {
        $filter = FilterFactory::build('foo');
        $this->assertNull($filter);
    }
} 