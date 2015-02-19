<?php
use GrabQL\Runtime\Runtime;

class RuntimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Runtime::getStatus
     */
    public function testStatus()
    {
        $runtime = new Runtime();
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $this->assertEquals(Runtime::STATUS_BOOTSTRAP, $runtime->bootstrap()->getStatus());
        $this->assertEquals(Runtime::STATUS_RUN, $runtime->execute()->getStatus());
        $this->assertEquals(Runtime::STATUS_STOP, $runtime->stop()->getStatus());
        $this->expectOutputString('Error: error' . "\n");
        $this->assertEquals(Runtime::STATUS_ERROR, $runtime->error('error')->getStatus());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::setMain
     * @covers GrabQL\Runtime\Runtime::execute
     */
    public function testSetMainCallback()
    {
        $runtime = new Runtime();
        $callback = function () use ($runtime) {
            $this->assertEquals(Runtime::STATUS_RUN, $runtime->getStatus());
        };
        $runtime->setMain($callback);
        $runtime->execute();
    }

    /**
     * @covers GrabQL\Runtime\Runtime::setMain
     * @covers GrabQL\Runtime\Runtime::execute
     */
    public function testSetMainProcedure()
    {
        $runtime = new Runtime();
        $callback = function () use ($runtime) {
            $this->assertEquals(Runtime::STATUS_RUN, $runtime->getStatus());
        };
        $procedure = new \GrabQL\Runtime\Type\Procedure(null, $callback);
        $runtime->setMain($procedure);
        $runtime->execute();
    }

    /**
     * @covers GrabQL\Runtime\Runtime::execute
     * @covers GrabQL\Runtime\Runtime::setStatus
     * @covers GrabQL\Runtime\Runtime::setStatus
     */
    public function testExecute()
    {
        $runtime = new Runtime();
        $var = 1;
        $callback = function () use (&$var) {
            $var = 2;
        };
        $runtime->setMain($callback);
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $runtime->execute();
        $this->assertEquals(Runtime::STATUS_RUN, $runtime->getStatus());
        $this->assertEquals(2, $var);
    }

    /**
     * @covers GrabQL\Runtime\Runtime::bootstrap
     * @covers GrabQL\Runtime\Runtime::setStatus
     * @covers GrabQL\Runtime\Runtime::loadDefaultLibraries
     */
    public function testBootstrap()
    {
        $runtime = new Runtime();
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $runtime->bootstrap();
        $this->assertEquals(Runtime::STATUS_BOOTSTRAP, $runtime->getStatus());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::stop
     * @covers GrabQL\Runtime\Runtime::setStatus
     * @covers GrabQL\Runtime\Runtime::loadDefaultLibraries
     */
    public function testStop()
    {
        $runtime = new Runtime();
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $runtime->stop();
        $this->assertEquals(Runtime::STATUS_STOP, $runtime->getStatus());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::error
     * @covers GrabQL\Runtime\Runtime::setStatus
     * @covers GrabQL\Runtime\Runtime::loadDefaultLibraries
     */
    public function testError()
    {
        $runtime = new Runtime();
        $this->assertEquals(Runtime::STATUS_IDLE, $runtime->getStatus());
        $this->expectOutputString('Error: message' . "\n");
        $runtime->error('message');
        $this->assertEquals(Runtime::STATUS_ERROR, $runtime->getStatus());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::procedures
     */
    public function testProcedures()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Collection', $runtime->procedures());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::symbols
     */
    public function testSymbols()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Collection', $runtime->symbols());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::out
     */
    public function testOut()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Io\Out', $runtime->out());
    }

    /**
     * @covers GrabQL\Runtime\Runtime::in
     */
    public function testIn()
    {
        $runtime = new Runtime();
        $this->assertInstanceOf('GrabQL\Runtime\Io\In', $runtime->in());
    }
}