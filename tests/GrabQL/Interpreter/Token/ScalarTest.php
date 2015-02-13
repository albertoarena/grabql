<?php
use \GrabQL\Runtime\Type\Base;
use \GrabQL\Interpreter\Token\Scalar;
use \GrabQL\Interpreter\Parser\Parser\Lexer;

class ScalarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param \GrabQL\Runtime\Runtime $runtime
     * @param $id
     * @param $value
     * @return Base
     */
    protected function createScalar($runtime, $id, $value)
    {
        $runtime->symbols()->add(new \GrabQL\Runtime\Type\Scalar($id, $value));
        $var = $runtime->symbols()->get($id);
        $this->assertEquals($value, $var->toString());
        return $var;
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::isCommand
     */
    public function testIsCommand()
    {
        $scalar = new Scalar;
        $this->assertEquals(false, $scalar->isCommand());
        $scalar->isCommand(true);
        $this->assertEquals(true, $scalar->isCommand());
        $scalar->isCommand(false);
        $this->assertEquals(false, $scalar->isCommand());
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::internalProcess
     * @covers \GrabQL\Interpreter\Token\Scalar::parseElement
     */
    public function testProcess()
    {
        $runtime = new \GrabQL\Runtime\Runtime();

        $var = $this->createScalar($runtime, 'test', '123');
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => $var->getId(),
                'position' => 0
            ),
        );

        $scalar = new Scalar;
        $reference = $scalar->process($runtime, $token, $data);

        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Reference', $reference);
        $this->assertEquals('test', $reference->getReference()->getId());
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::internalProcess
     * @covers \GrabQL\Interpreter\Token\Scalar::parseElement
     */
    public function testProcessReferenceNotFoundException()
    {
        $runtime = new \GrabQL\Runtime\Runtime();

        $var = $this->createScalar($runtime, 'test', '123');
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => 'test1',
                'position' => 0
            ),
        );

        $scalar = new Scalar;
        $this->setExpectedException('\Exception', 'Referenced object not found: test1');
        $scalar->process($runtime, $token, $data);
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::internalProcess
     * @covers \GrabQL\Interpreter\Token\Scalar::parseElement
     */
    public function testProcessFilterNotFoundException()
    {
        $runtime = new \GrabQL\Runtime\Runtime();

        $var = $this->createScalar($runtime, 'test', '123');
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => 'test:dummy',
                'position' => 0
            ),
        );

        $scalar = new Scalar;
        $this->setExpectedException('\Exception', 'Filter not found dummy');
        $scalar->process($runtime, $token, $data);
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::internalProcess
     * @covers \GrabQL\Interpreter\Token\Scalar::parseElement
     */
    public function testProcessCurlyBraces()
    {
        $runtime = new \GrabQL\Runtime\Runtime();

        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_OPEN_CURLY_BRACE,
                'value' => '{',
                'position' => 0
            ),
            array(
                'type' => Lexer::T_STRING,
                'value' => 'hello',
                'position' => 1
            ),
            array(
                'type' => Lexer::T_COMMA,
                'value' => ',',
                'position' => 6
            ),
            array(
                'type' => Lexer::T_STRING,
                'value' => 'world',
                'position' => 7
            ),
            array(
                'type' => Lexer::T_CLOSE_CURLY_BRACE,
                'value' => '}',
                'position' => 12
            ),
        );

        $scalar = new Scalar;
        $reference = $scalar->process($runtime, $token, $data);

        $this->assertEquals(array('hello', 'world'), $reference);
    }

    /**
     * @covers \GrabQL\Interpreter\Token\Scalar::internalProcess
     * @covers \GrabQL\Interpreter\Token\Scalar::parseElement
     */
    public function testProcessFilter()
    {
        $runtime = new \GrabQL\Runtime\Runtime();

        $var = $this->createScalar($runtime, 'test', '123');
        $token = array();
        $data = array(
            array(
                'type' => Lexer::T_IDENTIFIER,
                'value' => $var->getId() . ':json',
                'position' => 0
            ),
        );

        $scalar = new Scalar;
        $reference = $scalar->process($runtime, $token, $data);

        $this->assertInstanceOf('\\GrabQL\\Runtime\\Type\\Reference', $reference);
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Filter\\Json', $reference->getFilter());
    }

}