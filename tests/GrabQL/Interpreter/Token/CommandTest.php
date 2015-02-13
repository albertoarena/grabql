<?php
use \GrabQL\Interpreter\Token\Command;
use \GrabQL\Interpreter\Parser\Parser\Lexer;

class CommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Interpreter\Token\Command::internalProcess
     */
    public function testProcess()
    {
        $command = new Command;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array('type' => Lexer::T_ECHO, 'value' => 'echo', 'position' => 0);
        $data = array(
            array(
                'type' => Lexer::T_STRING,
                'value' => 'Hello world!',
                'position' => 5
            )
        );

        $this->expectOutputString('Hello world!' . "\n");
        $command->process($runtime, $token, $data);
    }

    public function testProcessCommandNotFoundException()
    {
        $command = new Command;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array('type' => Lexer::T_NONE, 'value' => 'NONE', 'position' => 0);
        $data = array(
            array(
                'type' => Lexer::T_STRING,
                'value' => 'Hello world!',
                'position' => 5
            )
        );

        $this->setExpectedException('\Exception', 'Command not found: NONE');
        $command->process($runtime, $token, $data);
    }

}