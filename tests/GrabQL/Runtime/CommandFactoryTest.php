<?php
use \GrabQL\Runtime\CommandFactory;

class CommandFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Runtime\CommandFactory::build
     * @covers \GrabQL\Runtime\Command\Select::__construct
     */
    public function testBuildSelect()
    {
        $cmd = CommandFactory::build('select');
        $this->assertInstanceOf('GrabQL\\Runtime\\Command\\Select', $cmd);
    }

    /**
     * @covers \GrabQL\Runtime\CommandFactory::build
     * @covers \GrabQL\Runtime\Command\Echoo::__construct
     */
    public function testBuildEchoo()
    {
        $cmd = CommandFactory::build('echo');
        $this->assertInstanceOf('GrabQL\\Runtime\\Command\\Echoo', $cmd);
    }

    /**
     * @covers \GrabQL\Runtime\CommandFactory::build
     */
    public function testBuildInvalid()
    {
        $cmd = CommandFactory::build('foo');
        $this->assertNull($cmd);
    }
} 