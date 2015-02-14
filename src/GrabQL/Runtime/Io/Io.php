<?php
/**
 * This class defines the abstract Input/Output of GrabQL Runtime.
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

abstract class Io
{
    /** @var resource */
    protected $stream;

    public function __construct()
    {
        $this->stream = NULL;
    }

    /**
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }
} 