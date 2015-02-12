<?php
/**
 * This class defines the nil type of GrabQL Runtime.
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

class Nil extends Base
{
    /**
     * @param null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
    }

    /**
     * @return mixed|null
     */
    protected function asFlat()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function asString()
    {
        return 'NIL';
    }

    /**
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        // NOP
    }

}