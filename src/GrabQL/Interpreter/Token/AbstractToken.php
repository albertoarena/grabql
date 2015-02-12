<?php
/**
 * This class defines the abstract token of GrabQL Interpreter.
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

abstract class AbstractToken {

    /** @var \GrabQL\Runtime\Runtime */
    protected $runtime;

    /** @var array */
    protected $token;

    /** @var array */
    protected $data;

    /**
     * @param \GrabQL\Runtime\Runtime $runtime
     * @param array $token
     * @param array $data
     * @return mixed
     */
    public function process($runtime, $token, $data)
    {
        $this->runtime = $runtime;
        $this->token = $token;
        $this->data = $data;
        return $this->internalProcess();
    }

    /**
     * @return mixed
     */
    abstract public function internalProcess();

} 