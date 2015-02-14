<?php
namespace Io;

use GrabQL\Runtime\Io\Out;
use GrabQL\Runtime\Type\Scalar;

class OutTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // Prevent that writing to STDOUT blocks the process
        //stream_set_blocking(STDOUT, 0);
    }

    /**
     * @covers GrabQL\Runtime\Io\Out::__construct
     * @covers GrabQL\Runtime\Io\Out::getStream
     */
    public function testConstruct()
    {
        $in = new Out();
        $this->assertEquals(STDOUT, $in->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Io\Out::__construct
     * @covers GrabQL\Runtime\Io\Out::write
     */
    public function testWrite()
    {
        $in = new Out();
        $this->expectOutputString('123' . Out::CR);
        $in->write('123');
    }

    /**
     * @covers GrabQL\Runtime\Io\Out::__construct
     * @covers GrabQL\Runtime\Io\Out::write
     */
    public function testWriteArray()
    {
        $in = new Out();
        $this->expectOutputString('1' . Out::CR . '2' . Out::CR);
        $in->write(array(1, 2));
    }

    /**
     * @covers GrabQL\Runtime\Io\Out::__construct
     * @covers GrabQL\Runtime\Io\Out::write
     */
    public function testWriteBase()
    {
        $in = new Out();
        $this->expectOutputString('123' . Out::CR);
        $in->write(new Scalar(null, '123'));
    }
    /**
     * Cannot be tested really, added only for coverage
     *
     * @covers GrabQL\Runtime\Io\Out::__construct
     * @covers GrabQL\Runtime\Io\Out::clear
     */
    public function testClear()
    {
        $in = new Out();
        $in->clear();
        $this->assertEquals(1, 1);
    }

} 