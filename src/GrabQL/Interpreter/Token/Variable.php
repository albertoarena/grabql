<?php
/**
 * This class defines the variable token of GrabQL Interpreter.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter\Token;

use GrabQL\Interpreter\TokenFactory;
use GrabQL\Interpreter\Parser\Parser\Lexer;
use GrabQL\Runtime\TypeFactory;

class Variable extends AbstractToken
{

    /**
     * @return \GrabQL\Runtime\Type\Base|mixed|null
     * @throws \Exception
     *
     * Format: T_IDENTIFIER T_EQUALS SCALAR
     */
    public function internalProcess()
    {
        $instance = null;

        // Check ID
        if ($this->data[0]['type'] == Lexer::T_IDENTIFIER) {
            $id = $this->data[0]['value'];
        } else {
            throw new \Exception('Invalid variable definition: expected identifier');
        }

        // Check equals
        if ($this->data[1]['type'] == Lexer::T_EQUALS) {
            $data = array_slice($this->data, 2);

            // Process data
            $scalarToken = TokenFactory::build(TokenFactory::SPECIAL_SCALAR);
            $scalar = $scalarToken->process($this->runtime, null, $data);

            // Create variable instance and add to runtime
            $instance = TypeFactory::createInstance($id, $scalar);
            $this->runtime->symbols()->add($instance);

        } else {
            throw new \Exception('Invalid variable definition: expected equals');
        }

        return $instance;
    }

} 