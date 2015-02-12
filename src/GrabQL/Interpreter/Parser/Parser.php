<?php
/**
 * This class defines the parser of GrabQL Interpreter.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter\Parser;

use GrabQL\Interpreter\Parser\Parser\Lexer;
use GrabQL\Utils\Logger;

class Parser
{
    /** @internal */
    const ME = 'Interpreter\\Parser';

    /** @var \GrabQL\Interpreter\Parser\Parser\Lexer */
    private $lexer;

    /** @var array */
    private $syntaxTree;

    /** @var int */
    protected $line;

    /**
     * @param string|null $gql
     */
    public function __construct($gql = null)
    {
        $this->lexer = new Lexer($gql);
        $this->line = 0;
        $this->syntaxTree = array();
    }

    /**
     * @return array
     */
    public function getSyntaxTree()
    {
        return $this->syntaxTree;
    }

    /**
     * @param string|null $gql
     * @return array
     */
    public function parse($gql = null)
    {
        // Process input
        $this->syntaxTree = array();
        for ($this->line = 0; $this->line < count($gql); $this->line++) {

            $statement = $this->createSyntaxTree($gql[$this->line]);
            if ($statement === null) {
                continue;
            }
            $this->syntaxTree[] = $statement;
        }
        return $this->syntaxTree;
    }

    /**
     * @param $row
     * @return array|null
     */
    protected function createSyntaxTree($row)
    {
        $this->lexer->setInput($row);

        $this->lexer->moveNext();

        $token = $this->lexer->lookahead;

        if ($token === null) {
            // Empty row
            return null;
        }

        switch ($this->lexer->lookahead['type']) {
            case Lexer::T_IDENTIFIER:
            case Lexer::T_ECHO:
            case Lexer::T_SELECT:
            case Lexer::T_VAR:
            case Lexer::T_HASH_TAG:
                // Statement
                $statement = $this->genericStatement();
                break;

            default:
                // Error
                $statement = '';
                $this->syntaxError('Invalid token', $this->lexer->lookahead);
                break;
        }

        // Check for end of string
        if ($this->lexer->lookahead !== null) {
            $this->syntaxError('end of string');
        }

        return array('token' => $token, 'data' => $statement);
    }

    /**
     * @return array
     */
    protected function genericStatement()
    {
        $data = array();
        $this->lexer->moveNext();
        do {
            $data[] = $this->lexer->lookahead;
            $this->lexer->moveNext();
        } while ($this->lexer->lookahead !== null);

        return $data;
    }

    /**
     * @param $error
     * @param mixed|null $info
     *
     * @todo It should generate an Exception
     */
    protected function syntaxError($error, $info = null)
    {
        Logger::writePrefix(self::ME, 'Syntax error: ' . $error, $info);
    }

} 