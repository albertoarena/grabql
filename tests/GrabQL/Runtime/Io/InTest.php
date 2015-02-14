<?php
namespace Io;

use GrabQL\Runtime\Io\In;

class InTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // Prevent that reading from STDIN blocks the process
        stream_set_blocking(STDIN, 0);
    }

    /**
     * @covers GrabQL\Runtime\Io\In::__construct
     * @covers GrabQL\Runtime\Io\In::getStream
     */
    public function testConstruct()
    {
        $in = new In();
        $this->assertEquals(STDIN, $in->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Io\In::__construct
     * @covers GrabQL\Runtime\Io\In::read
     */
    public function testRead()
    {
        $in = new In();
        $ln = $in->read();
        $this->assertEquals(false, $ln);
    }

    /**
     * @covers GrabQL\Runtime\Io\In::__construct
     * @covers GrabQL\Runtime\Io\In::readChar
     */
    public function testReadChar()
    {
        $in = new In();
        $char = $in->readChar();
        $this->assertEquals(false, $char);
    }

} 