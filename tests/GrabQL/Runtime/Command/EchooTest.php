<?php
namespace Command;

use GrabQL\Runtime\Command\Echoo;

class EchooTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Echoo::execute
     */
    public function testExecute()
    {
        // @todo
        $echo = new Echoo();
        $text = '123';
        //$this->expectOutputString($text);
        $echo->execute(array($text));
    }

} 