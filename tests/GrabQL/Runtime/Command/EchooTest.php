<?php
namespace Command;

use GrabQL\Runtime\Command\Echoo;

class EchooTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Command\Echoo::execute
     */
    public function testExecute()
    {
        $echo = new Echoo();
        $text = '123';
        $this->expectOutputString($text . "\n");
        $echo->execute(array($text));
    }

} 