<?php
namespace Io;

use GrabQL\Runtime\Io\Io;

class IoMock extends Io
{
    public function __construct($stream = null)
    {
        parent::__construct();
        if (!is_null($stream)) {
            $this->stream = $stream;
        }
    }
}

class IoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Io\Io::__construct
     * @covers GrabQL\Runtime\Io\Io::getStream
     */
    public function testConstruct()
    {
        $io = new IoMock();
        $this->assertNull($io->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Io\Io::__construct
     * @covers GrabQL\Runtime\Io\Io::getStream
     */
    public function testGetStream()
    {
        $io = new IoMock(STDERR);
        $this->assertEquals(STDERR, $io->getStream());
    }

} 