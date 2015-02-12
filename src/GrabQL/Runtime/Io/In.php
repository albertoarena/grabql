<?php
/**
 * This class defines the input stream (wrapper of STDIN) of GrabQL Runtime.
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

class In extends Io
{
    public function __construct()
    {
        $this->stream = STDIN;
    }

    /**
     * @return string
     */
    public function read()
    {
        return fgets($this->stream, 1024);
    }

    /**
     * @return string
     */
    public function readChar()
    {
        return fgetc($this->stream);
    }
} 