<?php
namespace Parser;

use GrabQL\Interpreter\Parser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $code, $codeInvalid, $codeEmpty;

    public function setUp()
    {
        $this->code = array('echo \'Hello world!\'');
        $this->codeInvalid = array('< print \'Hello world!\'');
        $this->codeEmpty = array('');
    }
    
    /**
     * @covers GrabQL\Interpreter\Parser\Parser::__construct
     * @covers GrabQL\Interpreter\Parser\Parser::getSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::getLine
     */
    public function testConstructor()
    {
        $parser = new Parser($this->code[0]);
        $this->assertEquals(array(), $parser->getSyntaxTree());
        $this->assertEquals(0, $parser->getLine());
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser::parse
     * @covers GrabQL\Interpreter\Parser\Parser::createSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::getSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::genericStatement
     */
    public function testParse()
    {
        $parser = new Parser();
        $parser->parse($this->code);
        $syntaxTree = $parser->getSyntaxTree();

        $this->assertArrayHasKey('0', $syntaxTree);
        $this->assertArrayHasKey('token', $syntaxTree[0]);
        $this->assertArrayHasKey('data', $syntaxTree[0]);
        $this->assertArrayHasKey('type', $syntaxTree[0]['token']);
        $this->assertEquals(Parser\Lexer::T_ECHO, $syntaxTree[0]['token']['type']);
        $this->assertEquals('echo', $syntaxTree[0]['token']['value']);
        $this->assertEquals(Parser\Lexer::T_STRING, $syntaxTree[0]['data'][0]['type']);
        $this->assertEquals('Hello world!', $syntaxTree[0]['data'][0]['value']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser::parse
     * @covers GrabQL\Interpreter\Parser\Parser::createSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::getSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::genericStatement
     * @covers GrabQL\Interpreter\Parser\Parser::setTriggerSyntaxErrorException
     * @covers GrabQL\Interpreter\Parser\Parser::syntaxError
     */
    public function testSyntaxErrorException()
    {
        $parser = new Parser();
        $parser->setTriggerSyntaxErrorException(true);
        $this->setExpectedException('\Exception', 'Syntax error: Invalid token: <');
        $parser->parse($this->codeInvalid);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser::parse
     * @covers GrabQL\Interpreter\Parser\Parser::createSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::getSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::genericStatement
     * @covers GrabQL\Interpreter\Parser\Parser::setTriggerSyntaxErrorException
     * @covers GrabQL\Interpreter\Parser\Parser::syntaxError
     */
    public function testEmptyCodeParse()
    {
        $parser = new Parser();
        $parser->setTriggerSyntaxErrorException(true);
        $parser->parse($this->codeEmpty);
        $syntaxTree = $parser->getSyntaxTree();
        $this->assertEquals(array(), $syntaxTree);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser::parse
     * @covers GrabQL\Interpreter\Parser\Parser::createSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::getSyntaxTree
     * @covers GrabQL\Interpreter\Parser\Parser::genericStatement
     * @covers GrabQL\Interpreter\Parser\Parser::setTriggerSyntaxErrorException
     * @covers GrabQL\Interpreter\Parser\Parser::syntaxError
     */
    public function testSyntaxErrorNoException()
    {
        $parser = new Parser();
        $parser->setTriggerSyntaxErrorException(false);
        $parser->parse($this->codeInvalid);
        $syntaxTree = $parser->getSyntaxTree();

        $this->assertArrayHasKey('0', $syntaxTree);
        $this->assertArrayHasKey('token', $syntaxTree[0]);
        $this->assertArrayHasKey('data', $syntaxTree[0]);
        $this->assertArrayHasKey('type', $syntaxTree[0]['token']);
        $this->assertEquals(Parser\Lexer::T_LOWER_THAN, $syntaxTree[0]['token']['type']);
        $this->assertEquals('<', $syntaxTree[0]['token']['value']);
        $this->assertNull($syntaxTree[0]['data']);
    }
} 