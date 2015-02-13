<?php
namespace Parser;

use GrabQL\Interpreter\Parser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $code = array('echo \'Hello world!\'');

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

} 