<?php
namespace Command;

use GrabQL\Runtime\Command\Echoo;

class EchooTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Command\Echoo::__construct
     */
    public function testConstruct()
    {
        $echo = new Echoo();
        $this->assertInstanceOf('GrabQL\Runtime\Io\Out', $echo->getOut());
    }

    /**
     * @covers GrabQL\Runtime\Command\Echoo::execute
     * @covers GrabQL\Runtime\Command\Echoo::__construct
     */
    public function testExecute()
    {
        $echo = new Echoo();
        $text = '123';
        $this->expectOutputString($text . "\n");
        $echo->execute(array($text));
    }

} 