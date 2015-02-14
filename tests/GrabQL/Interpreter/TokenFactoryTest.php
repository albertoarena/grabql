<?php
use \GrabQL\Interpreter\TokenFactory;
use \GrabQL\Interpreter\Parser\Parser\Lexer;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $list;

    protected function setUp()
    {
        $this->list = array(
            Lexer::T_VAR => '\GrabQL\Interpreter\Token\Variable',
            Lexer::T_INTEGER => '\GrabQL\Interpreter\Token\Scalar',
            Lexer::T_STRING => '\GrabQL\Interpreter\Token\Scalar',
            Lexer::T_FLOAT => '\GrabQL\Interpreter\Token\Scalar',
            TokenFactory::SPECIAL_SCALAR => '\GrabQL\Interpreter\Token\Scalar',
            Lexer::T_ECHO => '\GrabQL\Interpreter\Token\Command',
            Lexer::T_SELECT => '\GrabQL\Interpreter\Token\Command',
        );
    }

    /**
     * @covers \GrabQL\Interpreter\TokenFactory::build
     */
    public function testBuild()
    {
        foreach ($this->list as $token => $class) {
            $obj = TokenFactory::build($token);
            $this->assertInstanceOf($class, $obj);
        }
    }

    /**
     * @covers \GrabQL\Interpreter\TokenFactory::build
     */
    public function testInvalidBuild()
    {
        $obj = TokenFactory::build(Lexer::T_NONE);
        $this->assertNull($obj);
    }

}