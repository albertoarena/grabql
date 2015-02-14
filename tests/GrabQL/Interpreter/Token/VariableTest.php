<?php
use \GrabQL\Interpreter\Token\Variable;
use \GrabQL\Interpreter\Parser\Parser\Lexer;

class VariableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Interpreter\Token\Variable::internalProcess
     */
    public function testProcess()
    {
        $variable = new Variable;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => 'id',
                'position' => 0,
            ),
            array(
                'type' => Lexer::T_EQUALS,
                'value' => '=',
                'position' => 2,
            ),
            array(
                'type' => Lexer::T_STRING,
                'value' => 'Hello world!',
                'position' => 3
            )
        );

        $instance = $variable->process($runtime, $token, $data);
        $this->assertEquals('id', $instance->getId());
        $this->assertEquals('Hello world!', $instance->getValue());
        $this->assertNull($instance->getFilter());
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Variable::internalProcess
     */
    public function testInvalidVariableExpectedIdentifierProcessException()
    {
        $variable = new Variable;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_STRING,
                'value' => 'Hello world!',
                'position' => 3
            )
        );

        $this->setExpectedException('\Exception', 'Invalid variable definition: expected identifier');
        $variable->process($runtime, $token, $data);
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Variable::internalProcess
     */
    public function testInvalidVariableExpectedEqualsProcessException()
    {
        $variable = new Variable;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => 'id',
                'position' => 0,
            ),
            array(
                'type' => Lexer::T_STRING,
                'value' => 'Hello world!',
                'position' => 3
            )
        );

        $this->setExpectedException('\Exception', 'Invalid variable definition: expected equals');
        $variable->process($runtime, $token, $data);
    }
}