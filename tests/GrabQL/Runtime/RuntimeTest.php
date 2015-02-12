<?php
use GrabQL\Runtime\Runtime;

class RuntimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Runtime::getStatus
     */
    public function testStatus()
    {
        $runtime = new Runtime();
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $this->assertEquals(Runtime::STATUS_BOOTSTRAP, $runtime->bootstrap()->getStatus());
        $this->assertEquals(Runtime::STATUS_RUN, $runtime->execute()->getStatus());
        $this->assertEquals(Runtime::STATUS_STOP, $runtime->stop()->getStatus());
        $this->assertEquals(Runtime::STATUS_ERROR, $runtime->error('')->getStatus());
    }

    /**
     * @covers Runtime::setMain
     */
    public function testSetMain()
    {
        $runtime = new Runtime();
        $callback = function () use ($runtime) {
            $this->assertEquals(Runtime::STATUS_RUN, $runtime->getStatus());
        };
        $runtime->setMain($callback);
        $runtime->execute();
    }

    /**
     * @covers Runtime::execute
     */
    public function testExecute()
    {
        $runtime = new Runtime();
        $var = 1;
        $callback = function () use (&$var) {
            $var = 2;
        };
        $runtime->setMain($callback);
        $runtime->execute();
        $this->assertEquals(2, $var);
    }

    /**
     * @covers Runtime::procedures
     */
    public function testProcedures()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Collection', $runtime->procedures());
    }

    /**
     * @covers Runtime::symbols
     */
    public function testSymbols()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Collection', $runtime->symbols());
    }

    /**
     * @covers Runtime::out
     */
    public function testOut()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Io\Out', $runtime->out());
    }

    /**
     * @covers Runtime::in
     */
    public function testIn()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Io\In', $runtime->in());
    }
}