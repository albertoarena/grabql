<?php
/**
 * This class defines the output stream (wrapper of STDOUT) of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Io;

use GrabQL\Runtime\Type\Base;

class Out extends Io
{
    /** @internal carriage return */
    const CR = "\n";

    public function __construct()
    {
        $this->stream = STDOUT;
    }

    /**
     * Clear console, if available in the O.S.
     */
    public function clear()
    {
        @system('clear');
    }

    /**
     * @param array $args
     */
    public function write($args = array())
    {
        $args = func_get_args();
        if (func_num_args() == 1) {
            $args = $args[0];
        }

        if (is_array($args)) {
            foreach ($args as $arg) {
                $this->write($arg);
            }
        } else if ($args instanceof Base) {
            //fwrite($this->stream, $args->toString() . self::CR);
            echo $args->toString() . self::CR;
        } else {
            //fwrite($this->stream, strval($args) . self::CR);
            echo strval($args) . self::CR;
        }
    }
} 