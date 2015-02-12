<?php
/**
 * This class defines the resource type of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Type;

class Resource extends Base
{
    /** @var resource */
    protected $resource;

    /**
     * @param null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        // @todo
    }

    /**
     * @param resource $buffer
     * @param int|null $length
     * @return mixed|null
     */
    public function read($buffer, $length = null)
    {
        // @todo
        return null;
    }

    /**
     * @param resource $buffer
     * @param int|null $length
     * @return mixed|null
     */
    public function write($buffer, $length = null)
    {
        // @todo
        return null;
    }

    /**
     * @return mixed|string
     */
    protected function asFlat()
    {
        return '[resource]';
    }

    /**
     * @return string
     */
    protected function asString()
    {
        return '[resource]';
    }

    /**
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        // @todo
    }

}