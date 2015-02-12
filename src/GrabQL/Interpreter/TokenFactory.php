<?php
/**
 * This class defines a factory for tokens of GrabQL Interpreter.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter;

use GrabQL\Interpreter\Parser\Parser\Lexer;

class TokenFactory {

    /** @internal */
    const PREFIX = 'GrabQL\\Interpreter\\Token\\';

    /** @internal */
    const SPECIAL_SCALAR = -1;

    /** @var array */
    protected static $tokens = array(
        Lexer::T_VAR => 'Variable',
        Lexer::T_INTEGER => 'Scalar',
        Lexer::T_STRING => 'Scalar',
        Lexer::T_FLOAT => 'Scalar',
        self::SPECIAL_SCALAR => 'Scalar',
        Lexer::T_ECHO => 'Command',
        Lexer::T_SELECT => 'Command',
    );

    /**
     * @param string $type
     * @return \GrabQL\Interpreter\Token|null
     */
    public static function build($type)
    {
        if (array_key_exists($type, self::$tokens)) {
            $className = self::PREFIX . self::$tokens[$type];
            return new $className();
        }
        return null;
    }

}