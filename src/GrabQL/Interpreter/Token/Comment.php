<?php
/**
 * This class defines the comment token of GrabQL Interpreter.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter\Token;

class Comment extends AbstractToken
{

    /**
     * @return mixed|void
     */
    public function internalProcess()
    {
        // NOP
    }
} 