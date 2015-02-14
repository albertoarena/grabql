<?php
namespace Parser\Parser;

use GrabQL\Interpreter\Parser\Parser\Lexer;

class LexerTestBox extends Lexer
{
    public function getCatchablePatterns() {
        return parent::getCatchablePatterns();
    }

    public function getNonCatchablePatterns() {
        return parent::getNonCatchablePatterns();
    }
}

class LexerTest extends \PHPUnit_Framework_TestCase
{
    protected $input, $emptyInput, $inputTokens;

    public function setUp()
    {
        $this->input = 'echo \'Hello\'';
        $this->emptyInput = '';
        $this->inputTokens = array(
            Lexer::T_DOT => '.',
            Lexer::T_COMMA => ',',
            Lexer::T_OPEN_PARENTHESIS => '(',
            Lexer::T_CLOSE_PARENTHESIS => ')',
            Lexer::T_EQUALS => '=',
            Lexer::T_GREATER_THAN => '>',
            Lexer::T_LOWER_THAN => '<',
            Lexer::T_PLUS => '+',
            Lexer::T_MINUS => '-',
            Lexer::T_MULTIPLY => '*',
            Lexer::T_DIVIDE => '/',
            Lexer::T_NEGATE => '!',
            Lexer::T_OPEN_CURLY_BRACE => '{',
            Lexer::T_CLOSE_CURLY_BRACE => '}',
            Lexer::T_HASH_TAG => '#',
            Lexer::T_COLON => ':',
        );
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::__construct
     */
    public function testEmptyInput()
    {
        $lexer = new Lexer($this->emptyInput);
        $this->assertNull($lexer->lookahead);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testIdentifierGetType()
    {
        $lexer = new Lexer(null);
        $lexer->setInput($this->input);
        $lexer->moveNext();
        $this->assertArrayHasKey('type', $lexer->lookahead);
        $this->assertArrayHasKey('value', $lexer->lookahead);
        $this->assertArrayHasKey('position', $lexer->lookahead);
        $this->assertEquals(Lexer::T_ECHO, $lexer->lookahead['type']);
        $this->assertEquals('echo', $lexer->lookahead['value']);
        $this->assertEquals(0, $lexer->lookahead['position']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testIntegerNumericGetType()
    {
        $lexer = new Lexer(null);
        $lexer->setInput('123');
        $lexer->moveNext();
        $this->assertArrayHasKey('type', $lexer->lookahead);
        $this->assertArrayHasKey('value', $lexer->lookahead);
        $this->assertArrayHasKey('position', $lexer->lookahead);
        $this->assertEquals(Lexer::T_INTEGER, $lexer->lookahead['type']);
        $this->assertEquals(123, $lexer->lookahead['value']);
        $this->assertEquals(0, $lexer->lookahead['position']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testFloatNumericGetType()
    {
        $lexer = new Lexer(null);
        $lexer->setInput('12.3');
        $lexer->moveNext();
        $this->assertArrayHasKey('type', $lexer->lookahead);
        $this->assertArrayHasKey('value', $lexer->lookahead);
        $this->assertArrayHasKey('position', $lexer->lookahead);
        $this->assertEquals(Lexer::T_FLOAT, $lexer->lookahead['type']);
        $this->assertEquals(12.3, $lexer->lookahead['value']);
        $this->assertEquals(0, $lexer->lookahead['position']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testGenericIdentifierGetType()
    {
        $lexer = new Lexer(null);
        $lexer->setInput('print \'Hello\'');
        $lexer->moveNext();
        $this->assertArrayHasKey('type', $lexer->lookahead);
        $this->assertArrayHasKey('value', $lexer->lookahead);
        $this->assertArrayHasKey('position', $lexer->lookahead);
        $this->assertEquals(Lexer::T_IDENTIFIER, $lexer->lookahead['type']);
        $this->assertEquals('print', $lexer->lookahead['value']);
        $this->assertEquals(0, $lexer->lookahead['position']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testInputParameterGetType()
    {
        $lexer = new Lexer(null);
        $lexer->setInput('? input');
        $lexer->moveNext();
        $this->assertArrayHasKey('type', $lexer->lookahead);
        $this->assertArrayHasKey('value', $lexer->lookahead);
        $this->assertArrayHasKey('position', $lexer->lookahead);
        $this->assertEquals(Lexer::T_INPUT_PARAMETER, $lexer->lookahead['type']);
        $this->assertEquals('?', $lexer->lookahead['value']);
        $this->assertEquals(0, $lexer->lookahead['position']);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::setInput
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getType
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::moveNext
     */
    public function testTokensGetType()
    {
        $lexer = new Lexer(null);
        foreach ($this->inputTokens as $token => $input) {
            $lexer->setInput($input);
            $lexer->moveNext();
            $this->assertArrayHasKey('type', $lexer->lookahead);
            $this->assertArrayHasKey('value', $lexer->lookahead);
            $this->assertArrayHasKey('position', $lexer->lookahead);
            $this->assertEquals($token, $lexer->lookahead['type']);
            $this->assertEquals($input, $lexer->lookahead['value']);
            $this->assertEquals(0, $lexer->lookahead['position']);
        }
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getCatchablePatterns
     */
    public function testGetCatchablePatterns()
    {
        $lexer = new LexerTestBox(null);
        $catchablePatterns = $lexer->getCatchablePatterns();
        $this->assertArrayHasKey('0', $catchablePatterns);
        $this->assertArrayHasKey('1', $catchablePatterns);
        $this->assertArrayHasKey('2', $catchablePatterns);
        $this->assertArrayHasKey('3', $catchablePatterns);
        $this->assertRegExp('/\[a\-z/', $catchablePatterns[0]);
    }

    /**
     * @covers GrabQL\Interpreter\Parser\Parser\Lexer::getNonCatchablePatterns
     */
    public function testGetNonCatchablePatterns()
    {
        $lexer = new LexerTestBox(null);
        $nonCatchablePatterns = $lexer->getNonCatchablePatterns();
        $this->assertArrayHasKey('0', $nonCatchablePatterns);
        $this->assertArrayHasKey('1', $nonCatchablePatterns);
        $this->assertEquals(2, count($nonCatchablePatterns));
    }
} 